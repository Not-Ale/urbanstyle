<?php
/**
 * guardar_usuario.php — Urban Style
 * Registra un nuevo usuario en la BD con contraseña hasheada.
 */

session_start();
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: registro.php");
    exit;
}

$nombre      = isset($_POST['nombre'])      ? trim($_POST['nombre'])      : '';
$correo      = isset($_POST['correo'])      ? trim($_POST['correo'])      : '';
$contrasena  = isset($_POST['contrasena'])  ? trim($_POST['contrasena'])  : '';
$confirmar   = isset($_POST['confirmar'])   ? trim($_POST['confirmar'])   : '';
$tipo_cuenta = isset($_POST['tipo_cuenta']) ? trim($_POST['tipo_cuenta']) : 'cliente';

// Validaciones
if (empty($nombre) || empty($correo) || empty($contrasena) || empty($confirmar)) {
    header("Location: registro.php?error=campos");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: registro.php?error=correo");
    exit;
}

if (strlen($contrasena) < 6) {
    header("Location: registro.php?error=pass_corta");
    exit;
}

if ($contrasena !== $confirmar) {
    header("Location: registro.php?error=no_coinciden");
    exit;
}

// Tipos permitidos
$tipos_validos = ['cliente', 'vendedor', 'admin'];
if (!in_array($tipo_cuenta, $tipos_validos)) {
    $tipo_cuenta = 'cliente';
}

$correo_safe = mysqli_real_escape_string($conexion, $correo);

// Verificar si ya existe
$check = mysqli_query($conexion, "SELECT id FROM usuarios WHERE correo = '$correo_safe' LIMIT 1");
if (mysqli_num_rows($check) > 0) {
    header("Location: registro.php?error=existe");
    exit;
}

// Hash de contraseña
$hash = password_hash($contrasena, PASSWORD_BCRYPT);

$nombre_safe = mysqli_real_escape_string($conexion, $nombre);
$tipo_safe   = mysqli_real_escape_string($conexion, $tipo_cuenta);

$sql = "INSERT INTO usuarios (nombre, correo, contrasena, tipo_cuenta, fecha_registro)
        VALUES ('$nombre_safe', '$correo_safe', '$hash', '$tipo_safe', NOW())";

$ok = mysqli_query($conexion, $sql);

if ($ok) {
    // Auto-login tras registro exitoso
    $uid = mysqli_insert_id($conexion);
    $_SESSION['usuario_id']     = $uid;
    $_SESSION['usuario_nombre'] = $nombre;
    $_SESSION['usuario_correo'] = $correo;
    $_SESSION['usuario_tipo']   = $tipo_cuenta;
    $_SESSION['usuario_avatar'] = null;

    // Redirigir
    switch ($tipo_cuenta) {
        case 'admin':
            header("Location: admin.php");
            break;
        case 'vendedor':
            header("Location: vendedor.php");
            break;
        default:
            header("Location: index.php?bienvenido=1");
            break;
    }
    exit;
} else {
    // Error BD
    header("Location: registro.php?error=bd");
    exit;
}
?>