<?php

ob_start(); // Captura cualquier output accidental antes del header()

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "conexion.php";

// Solo aceptar POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ob_end_clean();
    header("Location: crear_cuenta.php");
    exit;
}

// ── 1. Recoger datos ──────────────────────────────────────
$nombre     = trim($_POST["nombre"]   ?? '');
$correo     = trim($_POST["correo"]   ?? '');
$telefono   = trim($_POST["telefono"] ?? '');
$contrasena = $_POST["contrasena"]    ?? '';
$tipo       = $_POST["tipo_cuenta"]   ?? 'cliente';

if (!in_array($tipo, ['cliente', 'vendedor'])) $tipo = 'cliente';

// ── 2. Validaciones ───────────────────────────────────────
if (empty($nombre) || empty($correo) || empty($contrasena)) {
    ob_end_clean();
    header("Location: crear_cuenta.php?error=campos&nombre=".urlencode($nombre)."&correo=".urlencode($correo));
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    ob_end_clean();
    header("Location: crear_cuenta.php?error=correo&nombre=".urlencode($nombre));
    exit;
}

if (strlen($contrasena) < 8) {
    ob_end_clean();
    header("Location: crear_cuenta.php?error=pass&nombre=".urlencode($nombre)."&correo=".urlencode($correo));
    exit;
}

// ── 3. ¿Correo ya existe? ─────────────────────────────────
$check = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
if (!$check) {
    ob_end_clean();
    header("Location: crear_cuenta.php?error=db");
    exit;
}
$check->bind_param("s", $correo);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    ob_end_clean();
    header("Location: crear_cuenta.php?error=existe&correo=".urlencode($correo));
    exit;
}
$check->close();

// ── 4. Hash y guardar ─────────────────────────────────────
$hash    = password_hash($contrasena, PASSWORD_BCRYPT);
$tel_val = !empty($telefono) ? $telefono : null;

$stmt = $conn->prepare(
    "INSERT INTO usuarios (nombre, correo, contrasena, telefono, tipo_cuenta) VALUES (?, ?, ?, ?, ?)"
);

if (!$stmt) {
    error_log("Prepare error: " . $conn->error);
    ob_end_clean();
    header("Location: crear_cuenta.php?error=db");
    exit;
}

$stmt->bind_param("sssss", $nombre, $correo, $hash, $tel_val, $tipo);

if ($stmt->execute()) {
    $nuevo_id = $stmt->insert_id;
    $stmt->close();
    $conn->close();

    // Auto-login tras registro exitoso
    session_start();
    $_SESSION['usuario_id']     = $nuevo_id;
    $_SESSION['usuario_nombre'] = $nombre;
    $_SESSION['usuario_correo'] = $correo;
    $_SESSION['usuario_tipo']   = $tipo;
    $_SESSION['usuario_avatar'] = null;

    ob_end_clean();
    switch ($tipo) {
        case 'admin':    header("Location: admind.php");            break;
        case 'vendedor': header("Location: productos.php");         break;
        default:         header("Location: index.php?bienvenido=1"); break;
    }
    exit;
} else {
    error_log("Execute error: " . $stmt->error);
    $stmt->close();
    $conn->close();
    ob_end_clean();
    header("Location: crear_cuenta.php?error=db");
    exit;
}