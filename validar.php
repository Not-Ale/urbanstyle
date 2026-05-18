<?php
session_start();
require_once "conexion.php";

// $conn viene de conexion.php
$conexion = $conn;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$correo     = isset($_POST['correo'])     ? trim($_POST['correo'])     : '';
$contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';

if (empty($correo) || empty($contrasena)) {
    header("Location: login.php?error=1");
    exit;
}

$correo_safe = mysqli_real_escape_string($conexion, $correo);

$sql    = "SELECT id, nombre, correo, contrasena, tipo_cuenta, avatar
           FROM usuarios
           WHERE correo = '$correo_safe'
           LIMIT 1";
$result = mysqli_query($conexion, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: login.php?error=2");
    exit;
}

$usuario = mysqli_fetch_assoc($result);

if (!password_verify($contrasena, $usuario['contrasena'])) {
    header("Location: login.php?error=1");
    exit;
}

$_SESSION['usuario_id']     = $usuario['id'];
$_SESSION['usuario_nombre'] = $usuario['nombre'];
$_SESSION['usuario_correo'] = $usuario['correo'];
$_SESSION['usuario_tipo']   = $usuario['tipo_cuenta'];
$_SESSION['usuario_avatar'] = $usuario['avatar'];

switch ($usuario['tipo_cuenta']) {
    case 'admin':    header("Location: admind.php");   break;
    case 'vendedor': header("Location: productos.php"); break;
    default:         header("Location: index.php");    break;
}
exit;