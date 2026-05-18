<?php
/**
 * guardar_contacto.php — Urban Style
 * Recibe el formulario de contacto y lo guarda en la BD.
 */

ob_start();
require_once "conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php#contacto");
    exit;
}

$nombre  = trim($_POST['nombre']  ?? '');
$correo  = trim($_POST['correo']  ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

// Validaciones básicas
if (empty($nombre) || empty($correo) || empty($mensaje)) {
    ob_end_clean();
    header("Location: index.php?contacto=campos#contacto");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    ob_end_clean();
    header("Location: index.php?contacto=correo#contacto");
    exit;
}

// Guardar en BD
$stmt = $conn->prepare(
    "INSERT INTO contacto (nombre, correo, mensaje) VALUES (?, ?, ?)"
);

if (!$stmt) {
    ob_end_clean();
    header("Location: index.php?contacto=error#contacto");
    exit;
}

$stmt->bind_param("sss", $nombre, $correo, $mensaje);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    ob_end_clean();
    header("Location: index.php?contacto=ok#contacto");
} else {
    $stmt->close();
    $conn->close();
    ob_end_clean();
    header("Location: index.php?contacto=error#contacto");
}
exit;