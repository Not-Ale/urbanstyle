<?php

ob_start();
session_start();
require_once "conexion.php";

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);

if (!$body) {
    echo json_encode(['ok' => false, 'error' => 'Cuerpo vacío o JSON inválido']);
    exit;
}

$metodo   = $body['metodo']   ?? '';
$items    = $body['items']    ?? [];
$total    = (float)($body['total']   ?? 0);
$nombre   = trim($body['nombre']     ?? '');
$correo   = trim($body['correo']     ?? '');
$refpago  = trim($body['referencia'] ?? '');   

$metodos_ok = ['visa', 'paypal', 'transferencia'];
if (!in_array($metodo, $metodos_ok)) {
    echo json_encode(['ok' => false, 'error' => 'Método de pago inválido']);
    exit;
}
if (empty($items) || $total <= 0) {
    echo json_encode(['ok' => false, 'error' => 'Carrito vacío o total incorrecto']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'] ?? null;
if (empty($nombre) && !empty($_SESSION['usuario_nombre'])) $nombre = $_SESSION['usuario_nombre'];
if (empty($correo) && !empty($_SESSION['usuario_correo'])) $correo = $_SESSION['usuario_correo'];

if (empty($nombre)) $nombre = 'Invitado';
if (empty($correo)) $correo = 'sin-correo@urbanstyle.hn';

$conn->begin_transaction();

try {
    $stmt = $conn->prepare(
        "INSERT INTO pedidos (usuario_id, nombre, correo, total, estado, created_at)
         VALUES (?, ?, ?, ?, 'pendiente', NOW())"
    );
    $stmt->bind_param('issd', $usuario_id, $nombre, $correo, $total);
    $stmt->execute();
    $pedido_id = $stmt->insert_id;
    $stmt->close();

    $stmt2 = $conn->prepare(
        "INSERT INTO detalle_pedidos (pedido_id, nombre_prod, img_url, precio_unit, cantidad)
         VALUES (?, ?, ?, ?, ?)"
    );
    foreach ($items as $item) {
        $np  = substr(trim($item['nombre'] ?? 'Producto'), 0, 200);
        $img = substr(trim($item['img']    ?? ''), 0, 500);
        $pu  = (float)($item['precio']     ?? 0);
        $qty = max(1, (int)($item['qty']   ?? 1));
        $stmt2->bind_param('issdi', $pedido_id, $np, $img, $pu, $qty);
        $stmt2->execute();
    }
    $stmt2->close();

    $estado_pago = ($metodo === 'transferencia') ? 'pendiente' : 'aprobado';

    $stmt3 = $conn->prepare(
        "INSERT INTO pagos (pedido_id, metodo, referencia, estado, created_at)
         VALUES (?, ?, ?, ?, NOW())"
    );
    $stmt3->bind_param('isss', $pedido_id, $metodo, $refpago, $estado_pago);
    $stmt3->execute();
    $stmt3->close();

    if ($estado_pago === 'aprobado') {
        $conn->query("UPDATE pedidos SET estado='completada' WHERE id=$pedido_id");
    }

    $conn->commit();

    ob_end_clean();
    echo json_encode(['ok' => true, 'pedido_id' => $pedido_id]);

} catch (Throwable $e) {
    $conn->rollback();
    error_log('procesar_pedido error: ' . $e->getMessage());
    ob_end_clean();
    echo json_encode(['ok' => false, 'error' => 'Error interno al guardar el pedido']);
}