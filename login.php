<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Iniciar Sesión — Urban Style</title>
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
body { font-family: 'Poppins', sans-serif; background: var(--cream); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
.auth-wrapper { display: flex; width: 960px; max-width: 98vw; min-height: 580px; border-radius: 28px; overflow: hidden; box-shadow: 0 24px 80px rgba(0,0,0,0.13); }
.auth-left { flex: 0 0 380px; background: var(--dark); padding: 52px 44px; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden; }
.auth-left::before { content: ''; position: absolute; top: -80px; right: -80px; width: 280px; height: 280px; border-radius: 50%; background: rgba(212,175,122,.12); }
.auth-left::after { content: ''; position: absolute; bottom: -60px; left: -60px; width: 200px; height: 200px; border-radius: 50%; background: rgba(212,175,122,.08); }
.brand-logo { font-size: 26px; font-weight: 700; color: #fff; letter-spacing: -0.5px; position: relative; z-index: 1; }
.brand-logo span { color: var(--gold); }
.left-content { position: relative; z-index: 1; }
.left-content h2 { font-size: 30px; font-weight: 700; color: #fff; line-height: 1.25; margin-bottom: 14px; }
.left-content h2 em { color: var(--gold); font-style: italic; font-weight: 300; }
.left-content p { font-size: 14px; color: rgba(255,255,255,.6); line-height: 1.7; margin-bottom: 32px; }
.stats { display: flex; gap: 28px; }
.stat-num { font-size: 22px; font-weight: 700; color: var(--gold); }
.stat-label { font-size: 11px; color: rgba(255,255,255,.5); letter-spacing: .5px; }
.left-image { width: 100%; height: 180px; object-fit: cover; border-radius: 16px; opacity: .7; position: relative; z-index: 1; }
.auth-right { flex: 1; background: #fff; padding: 52px 48px; display: flex; flex-direction: column; justify-content: center; }
.auth-right h2 { font-size: 24px; font-weight: 700; color: var(--dark); margin-bottom: 6px; }
.auth-right .subtitle { font-size: 13px; color: var(--text); margin-bottom: 28px; }
.auth-right .subtitle a { color: var(--gold); font-weight: 600; text-decoration: none; }
.auth-right .subtitle a:hover { text-decoration: underline; }
.alert { padding: 11px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 18px; display: none; align-items: center; gap: 9px; }
.alert.error   { background: #fdf0f0; border: 1px solid #f5c6c6; color: var(--red);   display: flex; }
.alert.success { background: #edf7f0; border: 1px solid #b2dfdb; color: var(--green); display: flex; }
.btn-google { width: 100%; padding: 13px 18px; border: 1.5px solid var(--border); border-radius: 12px; background: #fff; display: flex; align-items: center; justify-content: center; gap: 10px; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 500; color: var(--dark); cursor: pointer; transition: .25s; margin-bottom: 20px; }
.btn-google:hover { border-color: var(--gold); background: var(--cream); }
.btn-google img { width: 20px; height: 20px; }
.divider { display: flex; align-items: center; gap: 14px; margin-bottom: 22px; }
.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
.divider span { font-size: 12px; color: #bbb; white-space: nowrap; }
.input-group { position: relative; margin-bottom: 16px; }
.input-group i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 14px; transition: .2s; }
.input-group input { width: 100%; padding: 13px 16px 13px 44px; border: 1.5px solid var(--border); border-radius: 12px; font-family: 'Poppins', sans-serif; font-size: 14px; color: var(--dark); background: var(--cream); outline: none; transition: .25s; }
.input-group input:focus { border-color: var(--gold); background: #fff; }
.toggle-pass { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #bbb; font-size: 14px; padding: 0; transition: .2s; }
.toggle-pass:hover { color: var(--gold); }
.forgot { text-align: right; margin-top: -8px; margin-bottom: 20px; }
.forgot a { font-size: 12px; color: var(--text); text-decoration: none; transition: .2s; }
.forgot a:hover { color: var(--gold); }
.btn-submit { width: 100%; padding: 14px; background: var(--dark); color: #fff; border: none; border-radius: 12px; font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 600; cursor: pointer; transition: .3s; display: flex; align-items: center; justify-content: center; gap: 8px; }
.btn-submit:hover { background: var(--gold); }
.gpicker-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 9999; justify-content: center; align-items: center; }
.gpicker-overlay.show { display: flex; }
.gpicker-card { background: #fff; border-radius: 24px; padding: 36px; width: 400px; max-width: 95vw; box-shadow: 0 20px 60px rgba(0,0,0,.2); animation: popUp .3s cubic-bezier(.34,1.56,.64,1); }
@keyframes popUp { from { transform: scale(.85); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.gpicker-card .g-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.gpicker-card .g-logo img { width: 24px; }
.gpicker-card .g-logo span { font-size: 16px; font-weight: 600; color: var(--dark); }
.gpicker-card h3 { font-size: 18px; font-weight: 700; color: var(--dark); margin-bottom: 6px; }
.gpicker-card p { font-size: 13px; color: var(--text); margin-bottom: 22px; }
.g-account-btn { width: 100%; padding: 13px 16px; border: 1.5px solid var(--border); border-radius: 12px; background: #fff; display: flex; align-items: center; gap: 14px; cursor: pointer; transition: .2s; margin-bottom: 10px; font-family: 'Poppins', sans-serif; text-align: left; }
.g-account-btn:hover { border-color: var(--gold); background: var(--cream); }
.g-account-btn .av { width: 40px; height: 40px; border-radius: 50%; background: var(--dark); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; flex-shrink: 0; }
.g-account-btn .av img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
.g-account-btn .info .name { font-size: 14px; font-weight: 600; color: var(--dark); }
.g-account-btn .info .email { font-size: 12px; color: var(--text); }
.g-add-account { width: 100%; padding: 12px 16px; border: 1.5px dashed var(--border); border-radius: 12px; background: transparent; display: flex; align-items: center; gap: 14px; cursor: pointer; transition: .2s; font-family: 'Poppins', sans-serif; font-size: 13px; color: var(--text); margin-top: 6px; }
.g-add-account:hover { border-color: var(--gold); color: var(--dark); }
.g-add-account i { font-size: 16px; color: var(--gold); }
.gpicker-cancel { width: 100%; margin-top: 14px; padding: 11px; background: transparent; border: none; font-family: 'Poppins', sans-serif; font-size: 13px; color: var(--text); cursor: pointer; transition: .2s; }
.gpicker-cancel:hover { color: var(--dark); }
.btn-volver { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text); text-decoration: none; padding: 7px 14px; border: 1.5px solid var(--border); border-radius: 20px; transition: .2s; margin-bottom: 20px; background: #fff; }
.btn-volver:hover { border-color: var(--gold); color: var(--gold); }
@media (max-width: 720px) { .auth-left { display: none; } .auth-right { padding: 40px 28px; } .auth-wrapper { border-radius: 0; min-height: 100vh; } }
</style>
</head>
<body>

<div class="gpicker-overlay" id="gpickerModal">
    <div class="gpicker-card">
        <div class="g-logo">
            <img src="https://www.google.com/favicon.ico" alt="Google">
            <span>Iniciar sesión con Google</span>
        </div>
        <h3>Elige una cuenta</h3>
        <p>para continuar en <strong>Urban Style</strong></p>
        <button class="g-account-btn" onclick="googleLoginWith('cuenta1')">
            <div class="av" style="background:#4285F4;">U</div>
            <div class="info">
                <div class="name">Usuario Ejemplo</div>
                <div class="email">usuario@gmail.com</div>
            </div>
        </button>
        <button class="g-account-btn" onclick="googleLoginWith('cuenta2')">
            <div class="av" style="background:#EA4335;">A</div>
            <div class="info">
                <div class="name">Admin Urban</div>
                <div class="email">admin.urban@gmail.com</div>
            </div>
        </button>
        <button class="g-add-account" onclick="usarOtraGoogle()">
            <i class="fa-solid fa-plus"></i>
            Usar otra cuenta
        </button>
        <button class="gpicker-cancel" onclick="cerrarGpicker()">Cancelar</button>
    </div>
</div>

<div class="auth-wrapper">
    <div class="auth-left">
        <div class="brand-logo">Urban <span>Style</span></div>
        <div class="left-content">
            <h2>Tu estilo,<br>tu <em>identidad</em></h2>
            <p>Accede a tu cuenta y descubre colecciones exclusivas diseñadas para el estilo urbano moderno.</p>
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-num">10K+</div>
                    <div class="stat-label">Clientes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">100%</div>
                    <div class="stat-label">Artesanal</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">4.9★</div>
                    <div class="stat-label">Valoración</div>
                </div>
            </div>
        </div>
        <img class="left-image"
             src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&q=75"
             alt="Urban Style">
    </div>

    <div class="auth-right">
        <a href="index.php" class="btn-volver">
            <i class="fa-solid fa-arrow-left"></i> Volver al inicio
        </a>
        <h2>Bienvenido de nuevo</h2>

        <!-- ✅ CAMBIADO: registro.php → crear_cuenta.php -->
        <p class="subtitle">¿No tienes cuenta? <a href="crear_cuenta.php">Créala aquí</a></p>

        <div class="alert" id="alertMsg">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span id="alertText"></span>
        </div>

        <button class="btn-google" onclick="abrirGpicker()">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
            Continuar con Google
        </button>

        <div class="divider"><span>o continúa con tu correo</span></div>

        <form id="loginForm" action="validar.php" method="POST" onsubmit="return validarLogin(event)">
            <div class="input-group">
                <i class="fa-solid fa-envelope icon-left"></i>
                <input type="email" name="correo" id="correo" placeholder="Correo electrónico" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-lock icon-left"></i>
                <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" required>
                <button type="button" class="toggle-pass" onclick="togglePass('contrasena', this)">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
            <div class="forgot"><a href="#">¿Olvidaste tu contraseña?</a></div>
            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                Iniciar Sesión
            </button>
        </form>
    </div>
</div>

<script>
function abrirGpicker() { document.getElementById('gpickerModal').classList.add('show'); }
function cerrarGpicker() { document.getElementById('gpickerModal').classList.remove('show'); }
document.getElementById('gpickerModal').addEventListener('click', function(e) { if (e.target === this) cerrarGpicker(); });

function googleLoginWith(cuenta) {
    cerrarGpicker();
    mostrarAlerta('Redirigiendo a Google...', 'success');
    setTimeout(() => { window.location.href = 'google_callback.php?cuenta=' + encodeURIComponent(cuenta); }, 800);
}

function usarOtraGoogle() {
    cerrarGpicker();
    google.accounts.id.initialize({ client_id: 'TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com' });
    google.accounts.id.prompt();
}

function handleGoogleCredential(response) {
    const form  = document.createElement('form');
    form.method = 'POST';
    form.action = 'google_callback.php';
    const input = document.createElement('input');
    input.name  = 'credential';
    input.value = response.credential;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

function validarLogin(e) {
    const correo = document.getElementById('correo').value.trim();
    const pass   = document.getElementById('contrasena').value.trim();
    if (!correo || !pass) { e.preventDefault(); mostrarAlerta('Por favor completa todos los campos.', 'error'); return false; }
    if (pass.length < 6)  { e.preventDefault(); mostrarAlerta('La contraseña debe tener al menos 6 caracteres.', 'error'); return false; }
    return true;
}

function mostrarAlerta(msg, tipo) {
    const el  = document.getElementById('alertMsg');
    const txt = document.getElementById('alertText');
    el.className     = 'alert ' + tipo;
    txt.textContent  = msg;
}

function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') { input.type = 'text';     icon.className = 'fa-solid fa-eye-slash'; }
    else                           { input.type = 'password'; icon.className = 'fa-solid fa-eye'; }
}

const params = new URLSearchParams(window.location.search);
if (params.get('error') === '1') mostrarAlerta('Correo o contraseña incorrectos.', 'error');
if (params.get('error') === '2') mostrarAlerta('No existe una cuenta con ese correo.', 'error');
if (params.get('ok')    === '1') mostrarAlerta('Sesión iniciada correctamente. Redirigiendo...', 'success');
</script>

</body>
</html>