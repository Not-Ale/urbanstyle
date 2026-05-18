<?php
session_start();
require_once "conexion.php";

// $conn viene de conexion.php — lo asignamos al nombre que usa este archivo
$conexion = $conn;

define('GOOGLE_CLIENT_ID', 'TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com');

$credential  = isset($_POST['credential'])  ? $_POST['credential']  : null;
$tipo_cuenta = isset($_POST['tipo_cuenta']) ? $_POST['tipo_cuenta'] : 'cliente';
$cuenta_demo = isset($_GET['cuenta'])       ? $_GET['cuenta']       : null;

// ── Modo demo (pruebas locales sin Google real) ───────────
if ($cuenta_demo && !$credential) {

    $demos = [
        'cuenta1' => [
            'google_id' => 'demo_google_001',
            'nombre'    => 'Usuario Ejemplo',
            'correo'    => 'usuario@gmail.com',
            'avatar'    => 'https://ui-avatars.com/api/?name=Usuario+Ejemplo&background=4285F4&color=fff&size=200',
        ],
        'cuenta2' => [
            'google_id' => 'demo_google_002',
            'nombre'    => 'Admin Urban',
            'correo'    => 'admin.urban@gmail.com',
            'avatar'    => 'https://ui-avatars.com/api/?name=Admin+Urban&background=EA4335&color=fff&size=200',
        ],
    ];

    if (!isset($demos[$cuenta_demo])) {
        header("Location: login.php?error=google");
        exit;
    }

    $info = $demos[$cuenta_demo];
    procesarUsuarioGoogle($info['google_id'], $info['nombre'], $info['correo'], $info['avatar'], $tipo_cuenta, $conexion);
    exit;
}

// ── Login real con Google ─────────────────────────────────
if (!$credential) {
    header("Location: login.php?error=google");
    exit;
}

$payload = decodeJwtPayload($credential);

if (!$payload || empty($payload['sub'])) {
    header("Location: login.php?error=google_invalid");
    exit;
}

$google_id = $payload['sub'];
$nombre    = isset($payload['name'])    ? $payload['name']    : 'Usuario Google';
$correo    = isset($payload['email'])   ? $payload['email']   : '';
$avatar    = isset($payload['picture']) ? $payload['picture'] : null;

procesarUsuarioGoogle($google_id, $nombre, $correo, $avatar, $tipo_cuenta, $conexion);

// ── Funciones ─────────────────────────────────────────────
function procesarUsuarioGoogle($google_id, $nombre, $correo, $avatar, $tipo_cuenta, $conexion) {

    $gid_safe    = mysqli_real_escape_string($conexion, $google_id);
    $correo_safe = mysqli_real_escape_string($conexion, $correo);
    $nombre_safe = mysqli_real_escape_string($conexion, $nombre);
    $avatar_safe = mysqli_real_escape_string($conexion, $avatar ?? '');

    $tipos_validos = ['cliente', 'vendedor', 'admin'];
    if (!in_array($tipo_cuenta, $tipos_validos)) $tipo_cuenta = 'cliente';
    $tipo_safe = mysqli_real_escape_string($conexion, $tipo_cuenta);

    // ¿Ya existe por google_id?
    $res = mysqli_query($conexion,
        "SELECT id, nombre, correo, tipo_cuenta, avatar
         FROM usuarios WHERE google_id = '$gid_safe' LIMIT 1");

    if (mysqli_num_rows($res) > 0) {
        $u = mysqli_fetch_assoc($res);
        mysqli_query($conexion,
            "UPDATE usuarios SET avatar='$avatar_safe' WHERE id={$u['id']}");
        iniciarSesion($u['id'], $u['nombre'], $u['correo'], $u['tipo_cuenta'], $avatar);

    } else {
        // ¿Ya existe por correo?
        $res2 = mysqli_query($conexion,
            "SELECT id, nombre, correo, tipo_cuenta FROM usuarios
             WHERE correo = '$correo_safe' LIMIT 1");

        if (mysqli_num_rows($res2) > 0) {
            // Vincular cuenta existente con Google
            $u = mysqli_fetch_assoc($res2);
            mysqli_query($conexion,
                "UPDATE usuarios
                 SET google_id='$gid_safe', avatar='$avatar_safe'
                 WHERE id={$u['id']}");
            iniciarSesion($u['id'], $u['nombre'], $u['correo'], $u['tipo_cuenta'], $avatar);

        } else {
            // Crear usuario nuevo
            mysqli_query($conexion,
                "INSERT INTO usuarios (nombre, correo, tipo_cuenta, google_id, avatar, fecha_registro)
                 VALUES ('$nombre_safe','$correo_safe','$tipo_safe','$gid_safe','$avatar_safe', NOW())");
            $nuevo_id = mysqli_insert_id($conexion);
            iniciarSesion($nuevo_id, $nombre, $correo, $tipo_cuenta, $avatar);
        }
    }
}

function iniciarSesion($id, $nombre, $correo, $tipo, $avatar) {
    $_SESSION['usuario_id']     = $id;
    $_SESSION['usuario_nombre'] = $nombre;
    $_SESSION['usuario_correo'] = $correo;
    $_SESSION['usuario_tipo']   = $tipo;
    $_SESSION['usuario_avatar'] = $avatar;

    switch ($tipo) {
        case 'admin':    header("Location: admind.php");            break;
        case 'vendedor': header("Location: productos.php");         break;
        default:         header("Location: index.php?bienvenido=1"); break;
    }
    exit;
}

function decodeJwtPayload($jwt) {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return null;
    $payload = base64_decode(str_pad(
        strtr($parts[1], '-_', '+/'),
        strlen($parts[1]) % 4,
        '=', STR_PAD_RIGHT
    ));
    return json_decode($payload, true);
}