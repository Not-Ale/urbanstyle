<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Crear Cuenta — Urban Style</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://accounts.google.com/gsi/client" async defer></script>

<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --gold:   #D4AF7A;
    --dark:   #2C2C2C;
    --cream:  #FAF9F6;
    --border: #E8E4DC;
    --text:   #555;
    --red:    #e74c3c;
    --green:  #27ae60;
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--cream);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 16px;
}

.auth-wrapper {
    display: flex;
    width: 960px;
    max-width: 100%;
    min-height: 640px;
    border-radius: 28px;
    overflow: hidden;
    box-shadow: 0 24px 80px rgba(0,0,0,0.13);
}

/* ═══ PANEL IZQUIERDO ═══ */
.auth-left {
    flex: 0 0 360px;
    background: var(--dark);
    padding: 52px 40px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}
.auth-left::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 280px; height: 280px;
    border-radius: 50%;
    background: rgba(212,175,122,.12);
}
.brand-logo { font-size: 26px; font-weight: 700; color: #fff; position: relative; z-index: 1; }
.brand-logo span { color: var(--gold); }

.left-content { position: relative; z-index: 1; }
.left-content h2 { font-size: 26px; font-weight: 700; color: #fff; line-height: 1.3; margin-bottom: 14px; }
.left-content h2 em { color: var(--gold); font-style: italic; font-weight: 300; }
.left-content p { font-size: 13px; color: rgba(255,255,255,.55); line-height: 1.7; margin-bottom: 28px; }

.perks { display: flex; flex-direction: column; gap: 14px; }
.perk  { display: flex; align-items: flex-start; gap: 12px; }
.perk-icon {
    width: 36px; height: 36px; min-width: 36px;
    border-radius: 10px;
    background: rgba(212,175,122,.18);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold); font-size: 15px;
}
.perk-text .ptitle { font-size: 13px; font-weight: 600; color: #fff; }
.perk-text .pdesc  { font-size: 11px; color: rgba(255,255,255,.45); }

.left-image {
    width: 100%; height: 160px;
    object-fit: cover; border-radius: 14px;
    opacity: .65; position: relative; z-index: 1;
}

/* ═══ PANEL DERECHO ═══ */
.auth-right {
    flex: 1;
    background: #fff;
    padding: 48px 48px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    overflow-y: auto;
}
.auth-right h2 { font-size: 24px; font-weight: 700; color: var(--dark); margin-bottom: 4px; }
.auth-right .subtitle { font-size: 13px; color: var(--text); margin-bottom: 24px; }
.auth-right .subtitle a { color: var(--gold); font-weight: 600; text-decoration: none; }
.auth-right .subtitle a:hover { text-decoration: underline; }

/* ═══ TIPO DE CUENTA ═══ */
.account-type-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .7px;
    text-transform: uppercase;
    color: var(--text);
    margin-bottom: 10px;
}
.account-types {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;
    margin-bottom: 22px;
}
.type-card {
    border: 1.5px solid var(--border);
    border-radius: 14px;
    padding: 14px 12px;
    text-align: center;
    cursor: pointer;
    transition: .25s;
    position: relative;
}
.type-card:hover { border-color: var(--gold); background: #fffdf8; }
.type-card.selected {
    border-color: var(--gold);
    background: #fffdf8;
    box-shadow: 0 0 0 3px rgba(212,175,122,.18);
}
.type-card input[type="radio"] { display: none; }
.type-card .type-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    background: var(--cream);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    color: var(--dark);
    margin: 0 auto 10px;
    transition: .25s;
}
.type-card.selected .type-icon { background: var(--gold); color: #fff; }
.type-card .type-name { font-size: 13px; font-weight: 600; color: var(--dark); margin-bottom: 2px; }
.type-card .type-desc { font-size: 10px; color: var(--text); line-height: 1.4; }
.type-card .check-mark {
    position: absolute; top: 8px; right: 10px;
    color: var(--gold); font-size: 14px;
    display: none;
}
.type-card.selected .check-mark { display: block; }

/* ═══ ALERT ═══ */
.alert { padding: 11px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 16px; display: none; align-items: center; gap: 9px; }
.alert.error   { background: #fdf0f0; border: 1px solid #f5c6c6; color: var(--red);   display: flex; }
.alert.success { background: #edf7f0; border: 1px solid #b2dfdb; color: var(--green); display: flex; }

/* ═══ GOOGLE BTN ═══ */
.btn-google {
    width: 100%; padding: 12px 18px;
    border: 1.5px solid var(--border); border-radius: 12px;
    background: #fff;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 500;
    color: var(--dark); cursor: pointer; transition: .25s; margin-bottom: 18px;
}
.btn-google:hover { border-color: var(--gold); background: var(--cream); }
.btn-google img { width: 20px; height: 20px; }

.divider { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; }
.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
.divider span { font-size: 12px; color: #bbb; white-space: nowrap; }

/* ═══ INPUTS ═══ */
.form-row { display: flex; gap: 14px; }
.form-row .input-group { flex: 1; }

.input-group { position: relative; margin-bottom: 14px; }
.input-group i.icon-left {
    position: absolute; left: 14px; top: 50%;
    transform: translateY(-50%); color: #bbb; font-size: 14px;
    pointer-events: none; transition: .2s;
}
.input-group input {
    width: 100%; padding: 12px 14px 12px 42px;
    border: 1.5px solid var(--border); border-radius: 12px;
    font-family: 'Poppins', sans-serif; font-size: 14px;
    color: var(--dark); background: var(--cream); outline: none; transition: .25s;
}
.input-group input:focus { border-color: var(--gold); background: #fff; }
.toggle-pass {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: #bbb; font-size: 14px; transition: .2s;
}
.toggle-pass:hover { color: var(--gold); }

/* Password strength */
.pass-strength { display: flex; gap: 5px; margin-top: -8px; margin-bottom: 10px; }
.strength-bar { flex: 1; height: 3px; border-radius: 10px; background: var(--border); transition: .3s; }
.strength-bar.weak   { background: var(--red); }
.strength-bar.fair   { background: #f39c12; }
.strength-bar.strong { background: var(--green); }

/* ═══ TERMS ═══ */
.terms-check { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 18px; font-size: 12px; color: var(--text); }
.terms-check input[type="checkbox"] { margin-top: 2px; accent-color: var(--gold); width: 15px; height: 15px; flex-shrink: 0; }
.terms-check a { color: var(--gold); text-decoration: none; }

/* ═══ SUBMIT ═══ */
.btn-submit {
    width: 100%; padding: 14px;
    background: var(--dark); color: #fff; border: none; border-radius: 12px;
    font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 600;
    cursor: pointer; transition: .3s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-submit:hover { background: var(--gold); }

@media (max-width: 720px) {
    .auth-left { display: none; }
    .auth-right { padding: 36px 22px; }
    .auth-wrapper { border-radius: 0; min-height: 100vh; }
    .account-types { grid-template-columns: 1fr 1fr; }
    .form-row { flex-direction: column; gap: 0; }
}
</style>
</head>
<body>

<div class="auth-wrapper">

    <!-- Panel izquierdo -->
    <div class="auth-left">
        <div class="brand-logo">Urban <span>Style</span></div>

        <div class="left-content">
            <h2>Únete a la<br><em>comunidad</em></h2>
            <p>Crea tu cuenta y accede a beneficios exclusivos de la comunidad Urban Style.</p>

            <div class="perks">
                <div class="perk">
                    <div class="perk-icon"><i class="fa-solid fa-tag"></i></div>
                    <div class="perk-text">
                        <div class="ptitle">Ofertas exclusivas</div>
                        <div class="pdesc">Descuentos solo para miembros</div>
                    </div>
                </div>
                <div class="perk">
                    <div class="perk-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <div class="perk-text">
                        <div class="ptitle">Seguimiento de pedidos</div>
                        <div class="pdesc">Sabe dónde está tu compra</div>
                    </div>
                </div>
                <div class="perk">
                    <div class="perk-icon"><i class="fa-solid fa-heart"></i></div>
                    <div class="perk-text">
                        <div class="ptitle">Lista de favoritos</div>
                        <div class="pdesc">Guarda los productos que amas</div>
                    </div>
                </div>
            </div>
        </div>

        <img class="left-image"
             src="https://images.unsplash.com/photo-1630019852942-f89202989a59?w=600&q=75"
             alt="Urban Style">
    </div>

    <!-- Panel derecho -->
    <div class="auth-right">
        <h2>Crea tu cuenta</h2>
        <p class="subtitle">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>

        <div class="alert" id="alertMsg">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span id="alertText"></span>
        </div>

        <!-- Google -->
        <button class="btn-google" onclick="registroGoogle()">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
            Registrarse con Google
        </button>
        <div class="divider"><span>o crea una cuenta con tu correo</span></div>

        <!-- Tipo de cuenta -->
        <p class="account-type-label">Selecciona el tipo de cuenta</p>
        <div class="account-types" id="accountTypes">

            <label class="type-card selected" onclick="seleccionarTipo(this, 'cliente')">
                <input type="radio" name="tipo_cuenta" value="cliente" checked>
                <i class="fa-solid fa-check check-mark"></i>
                <div class="type-icon"><i class="fa-solid fa-user"></i></div>
                <div class="type-name">Cliente</div>
                <div class="type-desc">Compra y sigue tus pedidos</div>
            </label>

            <label class="type-card" onclick="seleccionarTipo(this, 'vendedor')">
                <input type="radio" name="tipo_cuenta" value="vendedor">
                <i class="fa-solid fa-check check-mark"></i>
                <div class="type-icon"><i class="fa-solid fa-store"></i></div>
                <div class="type-name">Vendedor</div>
                <div class="type-desc">Publica y gestiona productos</div>
            </label>

            <label class="type-card" onclick="seleccionarTipo(this, 'admin')">
                <input type="radio" name="tipo_cuenta" value="admin">
                <i class="fa-solid fa-check check-mark"></i>
                <div class="type-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="type-name">Admin</div>
                <div class="type-desc">Gestión completa del sistema</div>
            </label>

        </div>

        <!-- Formulario registro -->
        <form id="registroForm" action="guardar_usuario.php" method="POST" onsubmit="return validarRegistro(event)">
            <input type="hidden" name="tipo_cuenta" id="tipoCuentaInput" value="cliente">

            <div class="form-row">
                <div class="input-group">
                    <i class="fa-solid fa-user icon-left"></i>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre completo" required>
                </div>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-envelope icon-left"></i>
                <input type="email" name="correo" id="correo" placeholder="Correo electrónico" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock icon-left"></i>
                <input type="password" name="contrasena" id="contrasena"
                       placeholder="Contraseña (mín. 6 caracteres)"
                       oninput="evalStrength(this.value)" required>
                <button type="button" class="toggle-pass" onclick="togglePass('contrasena', this)">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>

            <div class="pass-strength">
                <div class="strength-bar" id="sb1"></div>
                <div class="strength-bar" id="sb2"></div>
                <div class="strength-bar" id="sb3"></div>
                <div class="strength-bar" id="sb4"></div>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock icon-left"></i>
                <input type="password" name="confirmar" id="confirmar"
                       placeholder="Confirmar contraseña" required>
                <button type="button" class="toggle-pass" onclick="togglePass('confirmar', this)">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>

            <div class="terms-check">
                <input type="checkbox" id="terminos" required>
                <label for="terminos">
                    Acepto los <a href="#">Términos y Condiciones</a> y la
                    <a href="#">Política de Privacidad</a> de Urban Style.
                </label>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-user-plus"></i>
                Crear Cuenta
            </button>
        </form>
    </div>

</div>

<script>
/* ════ TIPO DE CUENTA ════ */
function seleccionarTipo(card, tipo) {
    document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    document.getElementById('tipoCuentaInput').value = tipo;
}

/* ════ GOOGLE REGISTRO ════ */
function registroGoogle() {
    google.accounts.id.initialize({
        client_id: 'TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com', // ← Reemplaza con tu Client ID
        callback: handleGoogleCredential,
        ux_mode: 'popup'
    });
    google.accounts.id.prompt();
}

function handleGoogleCredential(response) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'google_callback.php';
    const c = document.createElement('input'); c.name = 'credential'; c.value = response.credential;
    const t = document.createElement('input'); t.name = 'tipo_cuenta'; t.value = document.getElementById('tipoCuentaInput').value;
    form.appendChild(c); form.appendChild(t);
    document.body.appendChild(form);
    form.submit();
}

/* ════ VALIDACIÓN ════ */
function validarRegistro(e) {
    const nombre    = document.getElementById('nombre').value.trim();
    const correo    = document.getElementById('correo').value.trim();
    const pass      = document.getElementById('contrasena').value;
    const confirmar = document.getElementById('confirmar').value;
    const terminos  = document.getElementById('terminos').checked;

    if (!nombre || !correo || !pass || !confirmar) {
        e.preventDefault();
        mostrarAlerta('Por favor completa todos los campos.', 'error');
        return false;
    }
    if (pass.length < 6) {
        e.preventDefault();
        mostrarAlerta('La contraseña debe tener al menos 6 caracteres.', 'error');
        return false;
    }
    if (pass !== confirmar) {
        e.preventDefault();
        mostrarAlerta('Las contraseñas no coinciden.', 'error');
        return false;
    }
    if (!terminos) {
        e.preventDefault();
        mostrarAlerta('Debes aceptar los términos y condiciones.', 'error');
        return false;
    }
    return true;
}

/* ════ PASSWORD STRENGTH ════ */
function evalStrength(v) {
    const bars = [sb1, sb2, sb3, sb4];
    bars.forEach(b => { b.className = 'strength-bar'; });
    if (v.length === 0) return;
    let score = 0;
    if (v.length >= 6)  score++;
    if (v.length >= 10) score++;
    if (/[A-Z]/.test(v) && /[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    const cls = score <= 1 ? 'weak' : score === 2 ? 'weak' : score === 3 ? 'fair' : 'strong';
    for (let i = 0; i < score; i++) bars[i].classList.add(cls);
}

function mostrarAlerta(msg, tipo) {
    const el = document.getElementById('alertMsg');
    document.getElementById('alertText').textContent = msg;
    el.className = 'alert ' + tipo;
}

function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash';
}

/* Mensajes desde URL */
const params = new URLSearchParams(window.location.search);
if (params.get('error') === 'existe') mostrarAlerta('Ese correo ya tiene una cuenta registrada.', 'error');
if (params.get('ok')    === '1')      mostrarAlerta('¡Cuenta creada! Redirigiendo...', 'success');
</script>

</body>
</html>