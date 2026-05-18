<?php
session_start();

// ── Protección: solo administradores ─────────────────────
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_tipo'], ['admin', 'vendedor'])) {
    header("Location: login.php?error=acceso");
    exit;
}

$nombre_admin  = htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Administrador');
$avatar_admin  = $_SESSION['usuario_avatar'] ?? null;
$inicial       = strtoupper(substr($nombre_admin, 0, 1));

// ── Conexión a BD ─────────────────────────────────────────
require_once "conexion.php";

// ── KPIs principales ──────────────────────────────────────
// Ajusta los nombres de tabla/columna a tu esquema real.
// Por ahora usamos datos de demo si las tablas no existen.

$kpis = [
    'ventas_hoy'     => 0,
    'ventas_mes'     => 0,
    'total_ingresos' => 0,
    'clientes'       => 0,
    'ordenes_pend'   => 0,
    'productos'      => 0,
];

// ── Leer KPIs desde las tablas reales ────────────────────
$tablas_ok = false;

$test = @$conn->query("SHOW TABLES LIKE 'pedidos'");
if ($test && mysqli_num_rows($test) > 0) {
    $tablas_ok = true;

    // Ventas hoy (pedidos NO cancelados)
    $r = $conn->query("SELECT COUNT(*) c, COALESCE(SUM(total),0) t FROM pedidos WHERE DATE(created_at)=CURDATE() AND estado != 'cancelada'");
    if ($r && $row = $r->fetch_assoc()) {
        $kpis['ventas_hoy']     = (int)$row['c'];
        $kpis['ventas_hoy_lps'] = (float)$row['t'];
    }
    // Ventas este mes
    $r = $conn->query("SELECT COUNT(*) c, COALESCE(SUM(total),0) t FROM pedidos WHERE MONTH(created_at)=MONTH(CURDATE()) AND YEAR(created_at)=YEAR(CURDATE()) AND estado != 'cancelada'");
    if ($r && $row = $r->fetch_assoc()) {
        $kpis['ventas_mes']     = (int)$row['c'];
        $kpis['total_ingresos'] = (float)$row['t'];
    }
    // Clientes registrados
    $r = $conn->query("SELECT COUNT(*) c FROM usuarios WHERE tipo_cuenta='cliente'");
    if ($r && $row = $r->fetch_assoc()) $kpis['clientes'] = (int)$row['c'];
    // Pedidos pendientes
    $r = $conn->query("SELECT COUNT(*) c FROM pedidos WHERE estado='pendiente'");
    if ($r && $row = $r->fetch_assoc()) $kpis['ordenes_pend'] = (int)$row['c'];
    // Productos activos
    $r = $conn->query("SELECT COUNT(*) c FROM productos WHERE activo=1");
    if ($r && $row = $r->fetch_assoc()) $kpis['productos'] = (int)$row['c'];
} else {
    // ── DATOS DEMO (fallback si la BD aún no tiene datos) ─
    $kpis = [
        'ventas_hoy'      => 14,
        'ventas_hoy_lps'  => 3420.00,
        'ventas_mes'      => 312,
        'total_ingresos'  => 76850.00,
        'clientes'        => 1204,
        'ordenes_pend'    => 23,
        'productos'       => 48,
    ];
}

// ── Últimas órdenes (usa la vista v_ordenes) ──────────────
$ordenes_recientes = [];
if ($tablas_ok) {
    // Intentar usar la vista; si no existe, hacer join manual
    $vista_ok = @$conn->query("SHOW FULL TABLES WHERE Table_type='VIEW' AND Tables_in_urbanstyle='v_ordenes'");
    if ($vista_ok && mysqli_num_rows($vista_ok) > 0) {
        $r = $conn->query("SELECT id, cliente, total, estado, metodo_pago, fecha FROM v_ordenes ORDER BY fecha DESC LIMIT 10");
    } else {
        $r = $conn->query(
            "SELECT p.id,
                    COALESCE(u.nombre,'Invitado') AS cliente,
                    p.total,
                    p.estado,
                    COALESCE(pg.metodo,'visa')    AS metodo_pago,
                    p.created_at                  AS fecha
             FROM pedidos p
             LEFT JOIN usuarios u  ON u.id  = p.usuario_id
             LEFT JOIN pagos    pg ON pg.pedido_id = p.id
             ORDER BY p.created_at DESC
             LIMIT 10"
        );
    }
    if ($r) while ($row = $r->fetch_assoc()) $ordenes_recientes[] = $row;
}
if (empty($ordenes_recientes)) {
    $ordenes_recientes = [
        ['id'=>1041,'cliente'=>'María López','total'=>145.00,'estado'=>'completada','metodo_pago'=>'Visa','fecha'=>'2025-05-17 10:32'],
        ['id'=>1040,'cliente'=>'Carlos Mejía','total'=>89.00,'estado'=>'pendiente','metodo_pago'=>'Transferencia','fecha'=>'2025-05-17 09:15'],
        ['id'=>1039,'cliente'=>'Ana Torres','total'=>220.50,'estado'=>'completada','metodo_pago'=>'PayPal','fecha'=>'2025-05-16 18:44'],
        ['id'=>1038,'cliente'=>'Juan Rodas','total'=>55.00,'estado'=>'cancelada','metodo_pago'=>'Visa','fecha'=>'2025-05-16 14:20'],
        ['id'=>1037,'cliente'=>'Sofía Cruz','total'=>38.00,'estado'=>'completada','metodo_pago'=>'Transferencia','fecha'=>'2025-05-16 11:05'],
        ['id'=>1036,'cliente'=>'Luis Paz','total'=>310.00,'estado'=>'pendiente','metodo_pago'=>'PayPal','fecha'=>'2025-05-15 20:18'],
        ['id'=>1035,'cliente'=>'Valeria Ramos','total'=>72.00,'estado'=>'completada','metodo_pago'=>'Visa','fecha'=>'2025-05-15 16:30'],
        ['id'=>1034,'cliente'=>'Diego Flores','total'=>190.00,'estado'=>'completada','metodo_pago'=>'Transferencia','fecha'=>'2025-05-14 09:50'],
    ];
}

// ── Mensajes de contacto ──────────────────────────────────
$mensajes = [];
$r = @$conn->query("SELECT nombre, correo, mensaje, DATE_FORMAT(created_at,'%d/%m/%Y %H:%i') AS created_at FROM contacto ORDER BY id DESC LIMIT 5");
if ($r) while ($row = $r->fetch_assoc()) $mensajes[] = $row;
if (empty($mensajes)) {
    $mensajes = [
        ['nombre'=>'Patricia Vargas','correo'=>'patricia@gmail.com','mensaje'=>'¿Tienen envíos a San Pedro Sula?','created_at'=>'2025-05-17 08:00'],
        ['nombre'=>'René Alvarado','correo'=>'rene.a@outlook.com','mensaje'=>'Quiero información sobre el reloj Rose Gold.','created_at'=>'2025-05-16 22:30'],
        ['nombre'=>'Claudia Méndez','correo'=>'claudia@gmail.com','mensaje'=>'Mi pedido #1038 fue cancelado sin aviso.','created_at'=>'2025-05-16 15:10'],
    ];
}

// Datos para gráfico de ventas (últimos 7 días)
$chart_labels = [];
$chart_data   = [];
if ($tablas_ok) {
    $r = $conn->query(
        "SELECT DATE(created_at) d, COALESCE(SUM(total),0) t
         FROM pedidos
         WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
           AND estado != 'cancelada'
         GROUP BY DATE(created_at)
         ORDER BY d"
    );
    if ($r) while ($row = $r->fetch_assoc()) {
        $chart_labels[] = date('d/m', strtotime($row['d']));
        $chart_data[]   = (float)$row['t'];
    }
}
if (empty($chart_labels)) {
    $chart_labels = ['11/05','12/05','13/05','14/05','15/05','16/05','17/05'];
    $chart_data   = [4200, 5800, 3900, 7100, 6300, 8900, 3420];
}

// Top productos más vendidos
$top_productos = [];
if ($tablas_ok) {
    // Usar vista si existe, si no, join manual
    $r = $conn->query(
        "SELECT pr.nombre, c.nombre AS categoria,
                SUM(dp.cantidad) AS vendidos,
                SUM(dp.cantidad * dp.precio_unit) AS ingresos
         FROM detalle_pedidos dp
         JOIN productos  pr ON pr.id = dp.producto_id
         JOIN categorias c  ON c.id  = pr.categoria_id
         JOIN pedidos    pe ON pe.id = dp.pedido_id
         WHERE pe.estado != 'cancelada'
         GROUP BY pr.id, pr.nombre, c.nombre
         ORDER BY vendidos DESC
         LIMIT 5"
    );
    if ($r) while ($row = $r->fetch_assoc()) $top_productos[] = [
        'nombre'    => $row['nombre'],
        'categoria' => $row['categoria'],
        'vendidos'  => (int)$row['vendidos'],
        'ingresos'  => (float)$row['ingresos'],
    ];
}
if (empty($top_productos)) {
    $top_productos = [
        ['nombre'=>'Bolso Tote Premium',      'categoria'=>'Bolsos',      'vendidos'=>87, 'ingresos'=>7743],
        ['nombre'=>'Reloj Rose Gold Luxe',    'categoria'=>'Relojes',     'vendidos'=>74, 'ingresos'=>10286],
        ['nombre'=>'Collar Premium',          'categoria'=>'Collares',    'vendidos'=>61, 'ingresos'=>3660],
        ['nombre'=>'Lentes Retro Round',      'categoria'=>'Lentes',      'vendidos'=>58, 'ingresos'=>2030],
        ['nombre'=>'Cinturón Cuero Clásico',  'categoria'=>'Cinturones',  'vendidos'=>45, 'ingresos'=>1440],
    ];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Urban Style</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
/* ══════════════════════════════════
   RESET & VARIABLES
══════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --gold:       #D4AF7A;
    --gold-light: #e8cfa0;
    --gold-dim:   rgba(212,175,122,.15);
    --dark:       #1A1A1A;
    --dark2:      #222;
    --panel:      #252525;
    --border:     rgba(255,255,255,.07);
    --text:       #E8E4DC;
    --muted:      rgba(232,228,220,.5);
    --cream:      #FAF9F6;
    --green:      #4ade80;
    --red:        #f87171;
    --amber:      #fbbf24;
    --sidebar-w:  260px;
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--dark);
    color: var(--text);
    min-height: 100vh;
    display: flex;
    overflow-x: hidden;
}

/* ══════════════════════════════════
   SIDEBAR
══════════════════════════════════ */
.sidebar {
    width: var(--sidebar-w);
    background: var(--dark2);
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0; top: 0; bottom: 0;
    z-index: 100;
    transition: transform .3s;
}

.sidebar-brand {
    padding: 28px 24px 24px;
    border-bottom: 1px solid var(--border);
}
.sidebar-brand .logo {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 28px;
    letter-spacing: .1em;
    color: var(--text);
}
.sidebar-brand .logo span { color: var(--gold); }
.sidebar-brand .badge {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--gold);
    background: var(--gold-dim);
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-block;
    margin-top: 6px;
}

.sidebar-nav {
    flex: 1;
    padding: 20px 12px;
    overflow-y: auto;
}

.nav-label {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--muted);
    padding: 14px 12px 6px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    color: var(--muted);
    transition: .2s;
    text-decoration: none;
    margin-bottom: 2px;
}
.nav-item:hover { background: var(--border); color: var(--text); }
.nav-item.active {
    background: var(--gold-dim);
    color: var(--gold);
}
.nav-item i { width: 18px; text-align: center; font-size: 14px; }

.nav-badge {
    margin-left: auto;
    background: var(--gold);
    color: var(--dark);
    font-size: 10px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 20px;
}

.sidebar-footer {
    padding: 16px 12px;
    border-top: 1px solid var(--border);
}
.admin-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 10px;
    background: var(--border);
}
.admin-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: var(--gold);
    color: var(--dark);
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
    overflow: hidden;
}
.admin-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
.admin-info .name { font-size: 13px; font-weight: 600; color: var(--text); }
.admin-info .role { font-size: 10px; color: var(--gold); font-weight: 600; letter-spacing: .5px; }
.logout-btn {
    margin-left: auto;
    background: none;
    border: none;
    color: var(--muted);
    cursor: pointer;
    font-size: 15px;
    padding: 4px;
    transition: .2s;
}
.logout-btn:hover { color: var(--red); }

/* ══════════════════════════════════
   MAIN CONTENT
══════════════════════════════════ */
.main {
    margin-left: var(--sidebar-w);
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* ── TOPBAR ── */
.topbar {
    height: 70px;
    background: var(--dark2);
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 32px;
    position: sticky;
    top: 0;
    z-index: 50;
}
.topbar-title { font-size: 18px; font-weight: 600; }
.topbar-title span { color: var(--gold); }
.topbar-right { display: flex; align-items: center; gap: 16px; }
.topbar-date {
    font-size: 12px;
    color: var(--muted);
    background: var(--border);
    padding: 6px 14px;
    border-radius: 20px;
}
.topbar-notif {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: var(--border);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    position: relative;
    transition: .2s;
}
.topbar-notif:hover { background: var(--gold-dim); color: var(--gold); }
.notif-dot {
    position: absolute;
    top: 6px; right: 6px;
    width: 8px; height: 8px;
    background: var(--gold);
    border-radius: 50%;
    border: 2px solid var(--dark2);
}

/* ── CONTENT AREA ── */
.content {
    padding: 32px;
    flex: 1;
}

/* ══════════════════════════════════
   SECTIONS (tabs)
══════════════════════════════════ */
.section { display: none; }
.section.active { display: block; }

/* ══════════════════════════════════
   PAGE HEADER
══════════════════════════════════ */
.page-header {
    margin-bottom: 28px;
}
.page-header h1 {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 2.4rem;
    letter-spacing: .06em;
    line-height: 1.1;
}
.page-header h1 span { color: var(--gold); }
.page-header p { font-size: 13px; color: var(--muted); margin-top: 4px; }

/* ══════════════════════════════════
   KPI CARDS
══════════════════════════════════ */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 18px;
    margin-bottom: 28px;
}

.kpi-card {
    background: var(--panel);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 22px 20px;
    position: relative;
    overflow: hidden;
    transition: transform .25s, border-color .25s;
}
.kpi-card:hover {
    transform: translateY(-4px);
    border-color: rgba(212,175,122,.3);
}
.kpi-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--gold);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .3s;
}
.kpi-card:hover::after { transform: scaleX(1); }

.kpi-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    background: var(--gold-dim);
    color: var(--gold);
    display: flex; align-items: center; justify-content: center;
    font-size: 17px;
    margin-bottom: 14px;
}
.kpi-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 6px;
}
.kpi-value {
    font-size: 26px;
    font-weight: 700;
    color: var(--text);
    line-height: 1;
    margin-bottom: 6px;
}
.kpi-sub {
    font-size: 12px;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: 5px;
}
.kpi-up   { color: var(--green); }
.kpi-down { color: var(--red); }

/* ══════════════════════════════════
   GRID 2 COLS
══════════════════════════════════ */
.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
    margin-bottom: 22px;
}
@media (max-width: 1100px) { .grid-2 { grid-template-columns: 1fr; } }

/* ══════════════════════════════════
   PANELS / CARDS
══════════════════════════════════ */
.panel {
    background: var(--panel);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}
.panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1px solid var(--border);
}
.panel-title {
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}
.panel-title i { color: var(--gold); }
.panel-action {
    font-size: 12px;
    color: var(--gold);
    cursor: pointer;
    background: none;
    border: none;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    transition: .2s;
}
.panel-action:hover { opacity: .75; }
.panel-body { padding: 20px 22px; }

/* ══════════════════════════════════
   TABLA ÓRDENES
══════════════════════════════════ */
.table-wrap { overflow-x: auto; }
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
th {
    text-align: left;
    padding: 10px 14px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: var(--muted);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
td {
    padding: 13px 14px;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
    color: var(--text);
}
tr:last-child td { border-bottom: none; }
tr:hover td { background: rgba(255,255,255,.02); }

.badge-estado {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.badge-completada { background: rgba(74,222,128,.12); color: var(--green); }
.badge-pendiente  { background: rgba(251,191,36,.12);  color: var(--amber); }
.badge-cancelada  { background: rgba(248,113,113,.12); color: var(--red); }

.metodo-icon { font-size: 14px; margin-right: 5px; }

/* ══════════════════════════════════
   TOP PRODUCTS TABLE
══════════════════════════════════ */
.top-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 0;
    border-bottom: 1px solid var(--border);
}
.top-row:last-child { border-bottom: none; }
.top-rank {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: var(--gold-dim);
    color: var(--gold);
    font-size: 12px;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.top-rank.gold-1 { background: var(--gold); color: var(--dark); }
.top-info { flex: 1; padding: 0 14px; }
.top-info .name { font-size: 13px; font-weight: 600; }
.top-info .cat  { font-size: 11px; color: var(--muted); }
.top-stats { text-align: right; }
.top-stats .vendidos { font-size: 13px; font-weight: 700; color: var(--text); }
.top-stats .ingresos { font-size: 11px; color: var(--gold); }

/* ══════════════════════════════════
   CHART
══════════════════════════════════ */
.chart-wrap { position: relative; height: 240px; }

/* ══════════════════════════════════
   MENSAJES CONTACTO
══════════════════════════════════ */
.msg-card {
    padding: 14px 0;
    border-bottom: 1px solid var(--border);
}
.msg-card:last-child { border-bottom: none; }
.msg-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 5px; }
.msg-header .name { font-size: 13px; font-weight: 600; }
.msg-header .date { font-size: 11px; color: var(--muted); }
.msg-email { font-size: 11px; color: var(--gold); margin-bottom: 6px; }
.msg-text { font-size: 12px; color: var(--muted); line-height: 1.6; }

/* ══════════════════════════════════
   USUARIOS
══════════════════════════════════ */
.user-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.chip-cliente  { background: rgba(74,222,128,.1);  color: var(--green); }
.chip-vendedor { background: rgba(212,175,122,.15); color: var(--gold); }
.chip-admin    { background: rgba(248,113,113,.1);  color: var(--red); }

/* ══════════════════════════════════
   FILTROS TOP
══════════════════════════════════ */
.filtros-top {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}
.filtro-chip {
    padding: 7px 16px;
    border-radius: 20px;
    background: var(--panel);
    border: 1px solid var(--border);
    font-size: 12px;
    font-weight: 500;
    color: var(--muted);
    cursor: pointer;
    transition: .2s;
    font-family: 'Poppins', sans-serif;
}
.filtro-chip:hover { border-color: var(--gold); color: var(--gold); }
.filtro-chip.activo { background: var(--gold-dim); border-color: var(--gold); color: var(--gold); }

/* ══════════════════════════════════
   SEARCH INPUT
══════════════════════════════════ */
.search-wrap {
    position: relative;
    margin-bottom: 18px;
}
.search-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 13px;
}
.search-input {
    width: 100%;
    padding: 10px 14px 10px 38px;
    background: var(--panel);
    border: 1px solid var(--border);
    border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    color: var(--text);
    outline: none;
    transition: .2s;
}
.search-input:focus { border-color: var(--gold); }
.search-input::placeholder { color: var(--muted); }

/* ══════════════════════════════════
   EMPTY STATE
══════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
}
.empty-state i { font-size: 42px; margin-bottom: 14px; opacity: .3; display: block; }
.empty-state p { font-size: 14px; }

/* ══════════════════════════════════
   ANIMACIONES
══════════════════════════════════ */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.section.active { animation: fadeIn .35s ease; }

/* ══════════════════════════════════
   RESPONSIVE
══════════════════════════════════ */
@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    .main { margin-left: 0; }
    .content { padding: 18px; }
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .topbar { padding: 0 18px; }
}
</style>
</head>
<body>

<!-- ══════════════════════════════════
     SIDEBAR
════════════════════════════════════ -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo">Urban <span>Style</span></div>
        <span class="badge">Panel Admin</span>
    </div>

    <div class="sidebar-nav">
        <div class="nav-label">Principal</div>
        <a class="nav-item active" onclick="showSection('dashboard')" href="#">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>
        <a class="nav-item" onclick="showSection('ventas')" href="#">
            <i class="fa-solid fa-chart-line"></i> Ventas
            <span class="nav-badge"><?= count($ordenes_recientes) ?></span>
        </a>
        <a class="nav-item" onclick="showSection('ordenes')" href="#">
            <i class="fa-solid fa-bag-shopping"></i> Órdenes
            <span class="nav-badge"><?= $kpis['ordenes_pend'] ?></span>
        </a>

        <div class="nav-label">Gestión</div>
        <a class="nav-item" onclick="showSection('productos')" href="#">
            <i class="fa-solid fa-tag"></i> Productos
        </a>
        <a class="nav-item" onclick="showSection('usuarios')" href="#">
            <i class="fa-solid fa-users"></i> Usuarios
        </a>
        <a class="nav-item" onclick="showSection('mensajes')" href="#">
            <i class="fa-solid fa-envelope"></i> Mensajes
            <span class="nav-badge"><?= count($mensajes) ?></span>
        </a>

        <div class="nav-label">Tienda</div>
        <a class="nav-item" href="index.php">
            <i class="fa-solid fa-house"></i> Ver Tienda
        </a>
        <a class="nav-item" href="productos.php">
            <i class="fa-solid fa-store"></i> Ver Catálogo
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="admin-card">
            <div class="admin-avatar">
                <?php if ($avatar_admin): ?>
                    <img src="<?= htmlspecialchars($avatar_admin) ?>" alt="">
                <?php else: ?>
                    <?= $inicial ?>
                <?php endif; ?>
            </div>
            <div class="admin-info">
                <div class="name"><?= $nombre_admin ?></div>
                <div class="role">Administrador</div>
            </div>
            <a href="logout.php" class="logout-btn" title="Cerrar sesión">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </div>
</nav>


<!-- ══════════════════════════════════
     MAIN
════════════════════════════════════ -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-title">
            Panel de <span>Administración</span>
        </div>
        <div class="topbar-right">
            <div class="topbar-date">
                <i class="fa-regular fa-calendar"></i>
                <?= date('d M Y') ?>
            </div>
            <div class="topbar-notif">
                <i class="fa-solid fa-bell"></i>
                <span class="notif-dot"></span>
            </div>
        </div>
    </div>

    <div class="content">

    <!-- ══════════════════════════════════
         SECCIÓN: DASHBOARD
    ════════════════════════════════════ -->
    <div class="section active" id="sec-dashboard">

        <div class="page-header">
            <h1>BIENVENIDO, <span><?= strtoupper(explode(' ', $nombre_admin)[0]) ?></span></h1>
            <p>Resumen general de Urban Style al día de hoy.</p>
        </div>

        <!-- KPIs -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                <div class="kpi-label">Ventas hoy</div>
                <div class="kpi-value"><?= $kpis['ventas_hoy'] ?></div>
                <div class="kpi-sub"><span class="kpi-up"><i class="fa-solid fa-arrow-up"></i> +12%</span> vs ayer</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-dollar-sign"></i></div>
                <div class="kpi-label">Ingresos mes</div>
                <div class="kpi-value">$<?= number_format($kpis['total_ingresos'], 0, '.', ',') ?></div>
                <div class="kpi-sub"><span class="kpi-up"><i class="fa-solid fa-arrow-up"></i> +8%</span> vs mes anterior</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
                <div class="kpi-label">Clientes</div>
                <div class="kpi-value"><?= number_format($kpis['clientes']) ?></div>
                <div class="kpi-sub"><span class="kpi-up"><i class="fa-solid fa-arrow-up"></i> +34</span> este mes</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-clock"></i></div>
                <div class="kpi-label">Órdenes pend.</div>
                <div class="kpi-value"><?= $kpis['ordenes_pend'] ?></div>
                <div class="kpi-sub"><span class="kpi-down"><i class="fa-solid fa-circle-exclamation"></i></span> Requieren atención</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-tag"></i></div>
                <div class="kpi-label">Productos</div>
                <div class="kpi-value"><?= $kpis['productos'] ?></div>
                <div class="kpi-sub"><span style="color:var(--muted)">En catálogo activo</span></div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-chart-bar"></i></div>
                <div class="kpi-label">Ventas mes</div>
                <div class="kpi-value"><?= number_format($kpis['ventas_mes']) ?></div>
                <div class="kpi-sub"><span class="kpi-up"><i class="fa-solid fa-arrow-up"></i> +21%</span> vs mes anterior</div>
            </div>
        </div>

        <!-- Chart + Top Productos -->
        <div class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title"><i class="fa-solid fa-chart-area"></i> Ventas — últimos 7 días</div>
                    <button class="panel-action">Ver reporte →</button>
                </div>
                <div class="panel-body">
                    <div class="chart-wrap">
                        <canvas id="chartVentas"></canvas>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title"><i class="fa-solid fa-trophy"></i> Top Productos</div>
                    <button class="panel-action" onclick="showSection('productos')">Ver todos →</button>
                </div>
                <div class="panel-body">
                    <?php foreach ($top_productos as $i => $p): ?>
                    <div class="top-row">
                        <div class="top-rank <?= $i===0?'gold-1':'' ?>"><?= $i+1 ?></div>
                        <div class="top-info">
                            <div class="name"><?= htmlspecialchars($p['nombre']) ?></div>
                            <div class="cat"><?= htmlspecialchars($p['categoria']) ?></div>
                        </div>
                        <div class="top-stats">
                            <div class="vendidos"><?= $p['vendidos'] ?> uds.</div>
                            <div class="ingresos">$<?= number_format($p['ingresos']) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Últimas órdenes -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title"><i class="fa-solid fa-receipt"></i> Últimas Órdenes</div>
                <button class="panel-action" onclick="showSection('ordenes')">Ver todas →</button>
            </div>
            <div class="table-wrap">
                <?php include_once '_tabla_ordenes.inc.php' ?: ''; ?>
                <table>
                    <thead>
                        <tr>
                            <th># Orden</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($ordenes_recientes, 0, 6) as $o):
                            $metodo_icons = ['Visa'=>'fa-cc-visa','PayPal'=>'fa-paypal','Transferencia'=>'fa-building-columns'];
                            $icon = $metodo_icons[$o['metodo_pago']] ?? 'fa-credit-card';
                        ?>
                        <tr>
                            <td><strong>#<?= $o['id'] ?></strong></td>
                            <td><?= htmlspecialchars($o['cliente']) ?></td>
                            <td><strong style="color:var(--gold)">$<?= number_format($o['total'], 2) ?></strong></td>
                            <td><i class="fa-brands <?= $icon ?> metodo-icon"></i><?= htmlspecialchars($o['metodo_pago']) ?></td>
                            <td><span class="badge-estado badge-<?= $o['estado'] ?>">
                                <?= $o['estado'] === 'completada' ? '<i class="fa-solid fa-check"></i>' : ($o['estado'] === 'pendiente' ? '<i class="fa-solid fa-clock"></i>' : '<i class="fa-solid fa-xmark"></i>') ?>
                                <?= ucfirst($o['estado']) ?>
                            </span></td>
                            <td style="color:var(--muted);font-size:12px;"><?= $o['fecha'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- /dashboard -->


    <!-- ══════════════════════════════════
         SECCIÓN: VENTAS
    ════════════════════════════════════ -->
    <div class="section" id="sec-ventas">
        <div class="page-header">
            <h1>CONTROL DE <span>VENTAS</span></h1>
            <p>Análisis detallado de ingresos y transacciones.</p>
        </div>

        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-money-bill-trend-up"></i></div>
                <div class="kpi-label">Ingresos hoy</div>
                <div class="kpi-value">$<?= number_format($kpis['ventas_hoy_lps'] ?? 3420, 2) ?></div>
                <div class="kpi-sub"><span class="kpi-up"><i class="fa-solid fa-arrow-up"></i> +18%</span> vs ayer</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="kpi-label">Ticket promedio</div>
                <div class="kpi-value">$<?= number_format($kpis['ventas_mes'] > 0 ? $kpis['total_ingresos'] / $kpis['ventas_mes'] : 246, 2) ?></div>
                <div class="kpi-sub"><span style="color:var(--muted)">por orden este mes</span></div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-brands fa-paypal"></i></div>
                <div class="kpi-label">Via PayPal</div>
                <div class="kpi-value">38%</div>
                <div class="kpi-sub"><span style="color:var(--muted)">del total de ventas</span></div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon"><i class="fa-solid fa-building-columns"></i></div>
                <div class="kpi-label">Transferencia</div>
                <div class="kpi-value">34%</div>
                <div class="kpi-sub"><span style="color:var(--muted)">del total de ventas</span></div>
            </div>
        </div>

        <div class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title"><i class="fa-solid fa-chart-line"></i> Ingresos — 7 días</div>
                </div>
                <div class="panel-body">
                    <div class="chart-wrap">
                        <canvas id="chartVentas2"></canvas>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title"><i class="fa-solid fa-chart-pie"></i> Métodos de pago</div>
                </div>
                <div class="panel-body" style="display:flex;justify-content:center;align-items:center;height:240px;">
                    <div style="max-width:220px;width:100%;">
                        <canvas id="chartMetodos"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla completa -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title"><i class="fa-solid fa-table-list"></i> Historial de Ventas</div>
            </div>
            <div class="panel-body" style="padding-bottom:0;">
                <div class="filtros-top">
                    <button class="filtro-chip activo" data-fil="todas">Todas</button>
                    <button class="filtro-chip" data-fil="completada">Completadas</button>
                    <button class="filtro-chip" data-fil="pendiente">Pendientes</button>
                    <button class="filtro-chip" data-fil="cancelada">Canceladas</button>
                </div>
            </div>
            <div class="table-wrap">
                <table id="tablaHistorial">
                    <thead>
                        <tr>
                            <th># Orden</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyHistorial">
                        <?php foreach ($ordenes_recientes as $o):
                            $metodo_icons = ['Visa'=>'fa-cc-visa','PayPal'=>'fa-paypal','Transferencia'=>'fa-building-columns'];
                            $icon = $metodo_icons[$o['metodo_pago']] ?? 'fa-credit-card';
                        ?>
                        <tr data-estado="<?= $o['estado'] ?>">
                            <td><strong>#<?= $o['id'] ?></strong></td>
                            <td><?= htmlspecialchars($o['cliente']) ?></td>
                            <td><strong style="color:var(--gold)">$<?= number_format($o['total'], 2) ?></strong></td>
                            <td><i class="fa-brands <?= $icon ?> metodo-icon"></i><?= htmlspecialchars($o['metodo_pago']) ?></td>
                            <td><span class="badge-estado badge-<?= $o['estado'] ?>">
                                <?= $o['estado']==='completada'?'<i class="fa-solid fa-check"></i>':($o['estado']==='pendiente'?'<i class="fa-solid fa-clock"></i>':'<i class="fa-solid fa-xmark"></i>') ?>
                                <?= ucfirst($o['estado']) ?>
                            </span></td>
                            <td style="color:var(--muted);font-size:12px;"><?= $o['fecha'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- /ventas -->


    <!-- ══════════════════════════════════
         SECCIÓN: ÓRDENES
    ════════════════════════════════════ -->
    <div class="section" id="sec-ordenes">
        <div class="page-header">
            <h1>GESTIÓN DE <span>ÓRDENES</span></h1>
            <p>Administra y actualiza el estado de cada pedido.</p>
        </div>
        <div class="filtros-top">
            <button class="filtro-chip activo" data-ord="todas">Todas (<?= count($ordenes_recientes) ?>)</button>
            <button class="filtro-chip" data-ord="pendiente">Pendientes (<?= count(array_filter($ordenes_recientes, fn($o)=>$o['estado']==='pendiente')) ?>)</button>
            <button class="filtro-chip" data-ord="completada">Completadas</button>
            <button class="filtro-chip" data-ord="cancelada">Canceladas</button>
        </div>
        <div class="panel">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th># Orden</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyOrdenes">
                        <?php foreach ($ordenes_recientes as $o):
                            $metodo_icons = ['Visa'=>'fa-cc-visa','PayPal'=>'fa-paypal','Transferencia'=>'fa-building-columns'];
                            $icon = $metodo_icons[$o['metodo_pago']] ?? 'fa-credit-card';
                        ?>
                        <tr data-ord="<?= $o['estado'] ?>">
                            <td><strong>#<?= $o['id'] ?></strong></td>
                            <td><?= htmlspecialchars($o['cliente']) ?></td>
                            <td><strong style="color:var(--gold)">$<?= number_format($o['total'], 2) ?></strong></td>
                            <td><i class="fa-brands <?= $icon ?> metodo-icon"></i><?= htmlspecialchars($o['metodo_pago']) ?></td>
                            <td><span class="badge-estado badge-<?= $o['estado'] ?>">
                                <?= $o['estado']==='completada'?'<i class="fa-solid fa-check"></i>':($o['estado']==='pendiente'?'<i class="fa-solid fa-clock"></i>':'<i class="fa-solid fa-xmark"></i>') ?>
                                <?= ucfirst($o['estado']) ?>
                            </span></td>
                            <td style="color:var(--muted);font-size:12px;"><?= $o['fecha'] ?></td>
                            <td>
                                <?php if ($o['estado']==='pendiente'): ?>
                                <button onclick="cambiarEstado(this,'completada')"
                                    style="background:rgba(74,222,128,.15);color:var(--green);border:none;padding:5px 12px;border-radius:8px;font-size:11px;cursor:pointer;font-family:'Poppins',sans-serif;font-weight:600;transition:.2s;"
                                    onmouseover="this.style.background='rgba(74,222,128,.3)'" onmouseout="this.style.background='rgba(74,222,128,.15)'">
                                    <i class="fa-solid fa-check"></i> Aprobar
                                </button>
                                <?php else: ?>
                                <span style="color:var(--muted);font-size:11px;">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- /ordenes -->


    <!-- ══════════════════════════════════
         SECCIÓN: PRODUCTOS
    ════════════════════════════════════ -->
    <div class="section" id="sec-productos">
        <div class="page-header">
            <h1>TOP <span>PRODUCTOS</span></h1>
            <p>Rendimiento de ventas por producto.</p>
        </div>
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title"><i class="fa-solid fa-trophy"></i> Ranking de ventas</div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Unidades vendidas</th>
                            <th>Ingresos</th>
                            <th>Rendimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $max_vend = max(array_column($top_productos, 'vendidos'));
                              foreach ($top_productos as $i => $p):
                                $pct = round($p['vendidos'] / $max_vend * 100);
                        ?>
                        <tr>
                            <td>
                                <div class="top-rank <?= $i===0?'gold-1':'' ?>"><?= $i+1 ?></div>
                            </td>
                            <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                            <td><span style="color:var(--gold);font-size:12px;"><?= htmlspecialchars($p['categoria']) ?></span></td>
                            <td><?= $p['vendidos'] ?> uds.</td>
                            <td><strong style="color:var(--gold)">$<?= number_format($p['ingresos']) ?></strong></td>
                            <td style="min-width:120px;">
                                <div style="height:6px;background:var(--border);border-radius:4px;overflow:hidden;">
                                    <div style="height:100%;width:<?= $pct ?>%;background:var(--gold);border-radius:4px;transition:width .5s;"></div>
                                </div>
                                <div style="font-size:10px;color:var(--muted);margin-top:3px;"><?= $pct ?>%</div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- /productos -->


    <!-- ══════════════════════════════════
         SECCIÓN: USUARIOS
    ════════════════════════════════════ -->
    <div class="section" id="sec-usuarios">
        <div class="page-header">
            <h1>GESTIÓN DE <span>USUARIOS</span></h1>
            <p>Todos los usuarios registrados en la plataforma.</p>
        </div>
        <div class="panel">
            <div class="panel-body">
                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="search-input" placeholder="Buscar por nombre o correo..." id="buscarUsuario">
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Tipo</th>
                            <th>Registro</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyUsuarios">
                        <?php
                        $usuarios_lista = [];
                        $ru = @$conn->query("SELECT id, nombre, correo, tipo_cuenta, fecha_registro FROM usuarios ORDER BY id DESC LIMIT 30");
                        if ($ru) while ($u = $ru->fetch_assoc()) $usuarios_lista[] = $u;
                        if (empty($usuarios_lista)) {
                            $usuarios_lista = [
                                ['id'=>1,'nombre'=>'María López','correo'=>'maria@gmail.com','tipo_cuenta'=>'cliente','fecha_registro'=>'2025-03-10'],
                                ['id'=>2,'nombre'=>'Carlos Mejía','correo'=>'carlos.m@gmail.com','tipo_cuenta'=>'vendedor','fecha_registro'=>'2025-03-15'],
                                ['id'=>3,'nombre'=>'Ana Torres','correo'=>'ana.t@outlook.com','tipo_cuenta'=>'cliente','fecha_registro'=>'2025-04-02'],
                                ['id'=>4,'nombre'=>'Admin Urban','correo'=>'admin.urban@gmail.com','tipo_cuenta'=>'admin','fecha_registro'=>'2025-01-01'],
                                ['id'=>5,'nombre'=>'Sofía Cruz','correo'=>'sofia.cruz@gmail.com','tipo_cuenta'=>'cliente','fecha_registro'=>'2025-04-20'],
                                ['id'=>6,'nombre'=>'Luis Paz','correo'=>'luis.paz@hotmail.com','tipo_cuenta'=>'cliente','fecha_registro'=>'2025-05-05'],
                            ];
                        }
                        foreach ($usuarios_lista as $u): ?>
                        <tr>
                            <td style="color:var(--muted)">#<?= $u['id'] ?></td>
                            <td><strong><?= htmlspecialchars($u['nombre']) ?></strong></td>
                            <td style="color:var(--muted);font-size:13px;"><?= htmlspecialchars($u['correo']) ?></td>
                            <td>
                                <span class="user-chip chip-<?= $u['tipo_cuenta'] ?>">
                                    <?= ucfirst($u['tipo_cuenta']) ?>
                                </span>
                            </td>
                            <td style="color:var(--muted);font-size:12px;"><?= $u['fecha_registro'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- /usuarios -->


    <!-- ══════════════════════════════════
         SECCIÓN: MENSAJES
    ════════════════════════════════════ -->
    <div class="section" id="sec-mensajes">
        <div class="page-header">
            <h1>MENSAJES DE <span>CONTACTO</span></h1>
            <p>Formularios enviados desde la sección de contacto.</p>
        </div>
        <div class="panel">
            <div class="panel-body">
                <?php if (empty($mensajes)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-envelope-open"></i>
                    <p>No hay mensajes de contacto aún.</p>
                </div>
                <?php else: ?>
                    <?php foreach ($mensajes as $m): ?>
                    <div class="msg-card">
                        <div class="msg-header">
                            <div class="name"><?= htmlspecialchars($m['nombre']) ?></div>
                            <div class="date"><?= $m['created_at'] ?? '' ?></div>
                        </div>
                        <div class="msg-email"><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($m['correo']) ?></div>
                        <div class="msg-text"><?= htmlspecialchars($m['mensaje']) ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div><!-- /mensajes -->

    </div><!-- /content -->
</div><!-- /main -->


<!-- ══════════════════════════════════
     TOAST
════════════════════════════════════ -->
<div id="toast" style="
    position:fixed;bottom:28px;right:28px;
    background:var(--panel);border:1px solid var(--border);
    color:var(--text);padding:12px 22px;border-radius:12px;
    font-size:13px;font-family:'Poppins',sans-serif;
    opacity:0;pointer-events:none;transition:opacity .3s;z-index:9999;
    display:flex;align-items:center;gap:10px;
"></div>

<script>
/* ══ Navegación ══ */
function showSection(id) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    document.getElementById('sec-' + id).classList.add('active');
    document.querySelectorAll('.nav-item').forEach(n => {
        if (n.getAttribute('onclick') && n.getAttribute('onclick').includes("'" + id + "'"))
            n.classList.add('active');
    });
    // Si es ventas inicializar gráfico extra
    if (id === 'ventas') initChartVentas2();
}

/* ══ Gráfico principal ══ */
const chartLabels = <?= json_encode($chart_labels) ?>;
const chartData   = <?= json_encode($chart_data) ?>;

function initChart(canvasId) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Ingresos ($)',
                data: chartData,
                borderColor: '#D4AF7A',
                backgroundColor: 'rgba(212,175,122,.08)',
                borderWidth: 2.5,
                pointRadius: 4,
                pointBackgroundColor: '#D4AF7A',
                tension: .4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,.04)' }, ticks: { color: '#888', font: { family: 'Poppins', size: 11 } } },
                y: { grid: { color: 'rgba(255,255,255,.04)' }, ticks: { color: '#888', font: { family: 'Poppins', size: 11 }, callback: v => '$'+v.toLocaleString() } }
            }
        }
    });
}

initChart('chartVentas');

let chart2Init = false;
function initChartVentas2() {
    if (chart2Init) return; chart2Init = true;
    initChart('chartVentas2');

    // Gráfico de métodos de pago (donut)
    const ctx2 = document.getElementById('chartMetodos');
    if (!ctx2) return;
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['PayPal', 'Visa', 'Transferencia'],
            datasets: [{
                data: [38, 28, 34],
                backgroundColor: ['#003087','#1A1F71','#D4AF7A'],
                borderColor: 'var(--panel)',
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#888', font: { family: 'Poppins', size: 11 }, padding: 16 }
                }
            }
        }
    });
}

/* ══ Filtro historial ventas ══ */
document.querySelectorAll('[data-fil]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('[data-fil]').forEach(b => b.classList.remove('activo'));
        btn.classList.add('activo');
        const fil = btn.dataset.fil;
        document.querySelectorAll('#tbodyHistorial tr').forEach(tr => {
            tr.style.display = (fil === 'todas' || tr.dataset.estado === fil) ? '' : 'none';
        });
    });
});

/* ══ Filtro órdenes ══ */
document.querySelectorAll('[data-ord]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('[data-ord]').forEach(b => b.classList.remove('activo'));
        btn.classList.add('activo');
        const ord = btn.dataset.ord;
        document.querySelectorAll('#tbodyOrdenes tr').forEach(tr => {
            tr.style.display = (ord === 'todas' || tr.dataset.ord === ord) ? '' : 'none';
        });
    });
});

/* ══ Cambiar estado orden (demo) ══ */
function cambiarEstado(btn, nuevoEstado) {
    const tr = btn.closest('tr');
    tr.dataset.ord = nuevoEstado;
    const badge = tr.querySelector('.badge-estado');
    badge.className = 'badge-estado badge-' + nuevoEstado;
    badge.innerHTML = '<i class="fa-solid fa-check"></i> Completada';
    btn.closest('td').innerHTML = '<span style="color:var(--muted);font-size:11px;">—</span>';
    showToast('✅ Orden marcada como completada');
}

/* ══ Búsqueda usuarios ══ */
document.getElementById('buscarUsuario').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tbodyUsuarios tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.opacity = '1';
    setTimeout(() => t.style.opacity = '0', 3000);
}

const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
const hoy = new Date();
</script>
</body>
</html>