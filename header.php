<?php
/**
 * header.php — Urban Style
 * Header reutilizable. Inclúyelo en cualquier página con:
 *   require_once "header.php";
 * La sesión debe estar iniciada ANTES de incluir este archivo.
 */

$es_staff  = isset($_SESSION['usuario_tipo']) && in_array($_SESSION['usuario_tipo'], ['admin', 'vendedor']);
$panel_url = (($_SESSION['usuario_tipo'] ?? '') === 'admin') ? 'admind.php' : 'admind.php';
?>
<header>
    <div class="logo">Urban <span>Style</span></div>
    <nav>
        <a href="index.php">Inicio</a>
        <a href="index.php#nosotros">Nosotros</a>
        <a href="productos.php">Catálogo</a>
        <a href="index.php#destacados">Destacados</a>
        <a href="index.php#contacto">Contacto</a>
    </nav>
    <div class="icons">
        <?php if ($es_staff): ?>
        <a href="<?= htmlspecialchars($panel_url) ?>" class="icon-btn icon-btn-panel" title="Panel de control">
            <i class="fa-solid fa-gear"></i>
        </a>
        <?php endif; ?>
        <a href="carrito.php" class="icon-btn"><i class="fa-solid fa-cart-shopping"></i></a>
        <a href="login.php"   class="icon-btn"><i class="fa-solid fa-user"></i></a>
    </div>
</header>

<style>
.icon-btn-panel {
    border-color: #D4AF7A !important;
    color: #D4AF7A !important;
    position: relative;
    overflow: visible !important;
}
.icon-btn-panel:hover {
    background: #D4AF7A !important;
    color: #fff !important;
}
.icon-btn-panel i {
    transition: transform 0.5s ease;
}
.icon-btn-panel:hover i {
    transform: rotate(90deg);
}
.icon-btn-panel::after {
    content: 'Panel de control';
    position: absolute;
    bottom: -34px;
    left: 50%;
    transform: translateX(-50%);
    background: #2C2C2C;
    color: #fff;
    font-size: 11px;
    font-family: 'Poppins', sans-serif;
    white-space: nowrap;
    padding: 4px 10px;
    border-radius: 6px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
    z-index: 999;
}
.icon-btn-panel:hover::after {
    opacity: 1;
}
</style>