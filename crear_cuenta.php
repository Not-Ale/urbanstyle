<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crear Cuenta — Urban Style</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --black:   #0a0a0a;
      --white:   #f5f2ed;
      --accent:  #c8f542;
      --gray:    #1e1e1e;
      --gray2:   #2e2e2e;
      --muted:   #888;
      --danger:  #ff4d4d;
      --success: #c8f542;
      --gold:    #D4AF7A;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--black);
      color: var(--white);
      min-height: 100vh;
      display: flex;
      align-items: stretch;
      overflow-x: hidden;
    }

    /* ── LEFT PANEL ── */
    .panel-left {
      flex: 1;
      background: var(--gray);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 3rem;
      position: relative;
      overflow: hidden;
    }

    .panel-left::before {
      content: 'US';
      font-family: 'Bebas Neue', sans-serif;
      font-size: clamp(160px, 20vw, 300px);
      color: rgba(200,245,66,.04);
      position: absolute;
      bottom: -2rem;
      left: -1rem;
      line-height: 1;
      user-select: none;
    }

    .brand {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 2rem;
      letter-spacing: .12em;
      color: var(--accent);
    }

    .panel-left-tagline { position: relative; z-index: 1; }

    .panel-left-tagline h2 {
      font-family: 'Bebas Neue', sans-serif;
      font-size: clamp(2.5rem, 5vw, 4.5rem);
      line-height: 1.05;
      letter-spacing: .04em;
      margin-bottom: 1rem;
    }
    .panel-left-tagline h2 span { color: var(--accent); }

    .panel-left-tagline p {
      font-size: .95rem;
      color: var(--muted);
      max-width: 320px;
      line-height: 1.7;
    }

    .dots { display: flex; gap: .5rem; }
    .dots span {
      width: 8px; height: 8px;
      border-radius: 50%;
      background: var(--gray2);
    }
    .dots span.active { background: var(--accent); }

    /* ── RIGHT PANEL ── */
    .panel-right {
      width: 480px;
      background: var(--black);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem 2.5rem;
    }

    .form-box { width: 100%; max-width: 380px; }

    .form-box h1 {
      font-family: 'Bebas Neue', sans-serif;
      font-size: 2.6rem;
      letter-spacing: .06em;
      margin-bottom: .35rem;
    }

    .form-box .subtitle {
      font-size: .85rem;
      color: var(--muted);
      margin-bottom: 2.2rem;
    }
    .form-box .subtitle a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 500;
    }

    /* alert */
    .alert {
      padding: .85rem 1rem;
      border-radius: 6px;
      font-size: .875rem;
      margin-bottom: 1.4rem;
      display: none;
    }
    .alert.error   { background: rgba(255,77,77,.15); border: 1px solid var(--danger); color: var(--danger); display: block; }
    .alert.success { background: rgba(200,245,66,.12); border: 1px solid var(--accent); color: var(--accent); display: block; }

    /* field */
    .field { margin-bottom: 1.2rem; }

    .field label {
      display: block;
      font-size: .78rem;
      font-weight: 600;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--muted);
      margin-bottom: .45rem;
    }

    .field-wrap { position: relative; }

    .field-wrap .fi-left {
      position: absolute;
      left: .9rem;
      top: 50%;
      transform: translateY(-50%);
      opacity: .35;
      pointer-events: none;
      color: var(--white);
      font-size: 14px;
    }

    .field input {
      width: 100%;
      background: var(--gray);
      border: 1px solid var(--gray2);
      color: var(--white);
      padding: .85rem 2.8rem .85rem 2.6rem;
      border-radius: 8px;
      font-family: 'DM Sans', sans-serif;
      font-size: .95rem;
      outline: none;
      transition: border-color .2s, box-shadow .2s;
    }

    .field input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(200,245,66,.1);
    }
    .field input::placeholder { color: #555; }

    /* ── OJITO — fix correcto ── */
    .toggle-pass {
      position: absolute;
      right: .75rem;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: #666;
      padding: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color .2s;
      z-index: 2;
    }
    .toggle-pass:hover { color: var(--accent); }
    .toggle-pass i { font-size: 15px; pointer-events: none; }

    /* password strength */
    .strength-bar {
      display: flex;
      gap: 4px;
      margin-top: .5rem;
    }
    .strength-bar div {
      flex: 1;
      height: 3px;
      border-radius: 2px;
      background: var(--gray2);
      transition: background .3s;
    }
    .strength-label {
      font-size: .72rem;
      color: var(--muted);
      margin-top: .3rem;
    }

    /* ── TIPO CUENTA — tarjetas visuales ── */
    .tipo-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .75rem;
      margin-top: .1rem;
    }

    .tipo-card {
      position: relative;
    }
    .tipo-card input[type="radio"] {
      position: absolute;
      opacity: 0;
      width: 0; height: 0;
    }
    .tipo-card label {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: .45rem;
      padding: 1rem .75rem;
      background: var(--gray);
      border: 2px solid var(--gray2);
      border-radius: 10px;
      cursor: pointer;
      transition: border-color .2s, background .2s;
      text-transform: none;
      letter-spacing: 0;
      font-size: .85rem;
      font-weight: 500;
      color: var(--muted);
      text-align: center;
    }
    .tipo-card label i {
      font-size: 1.4rem;
    }
    /* Cliente → verde accent */
    .tipo-card.cliente input:checked ~ label {
      border-color: var(--accent);
      background: rgba(200,245,66,.08);
      color: var(--accent);
    }
    /* Vendedor → dorado */
    .tipo-card.vendedor input:checked ~ label {
      border-color: var(--gold);
      background: rgba(212,175,122,.1);
      color: var(--gold);
    }
    .tipo-card label:hover {
      border-color: #444;
      color: var(--white);
    }

    /* Panel vendedor (oculto por defecto) */
    .vendedor-info {
      display: none;
      background: rgba(212,175,122,.07);
      border: 1px solid rgba(212,175,122,.25);
      border-radius: 10px;
      padding: 1rem;
      margin-top: .5rem;
      font-size: .82rem;
      color: rgba(212,175,122,.9);
      line-height: 1.65;
    }
    .vendedor-info i { margin-right: 5px; }
    .vendedor-info.show { display: block; }

    /* submit */
    .btn-submit {
      width: 100%;
      background: var(--accent);
      color: var(--black);
      border: none;
      padding: 1rem;
      border-radius: 8px;
      font-family: 'Bebas Neue', sans-serif;
      font-size: 1.15rem;
      letter-spacing: .1em;
      cursor: pointer;
      margin-top: .5rem;
      transition: opacity .2s, transform .15s;
    }
    .btn-submit:hover  { opacity: .9; }
    .btn-submit:active { transform: scale(.98); }
    .btn-submit.loading { pointer-events: none; opacity: .6; }

    .terms {
      font-size: .75rem;
      color: var(--muted);
      text-align: center;
      margin-top: 1.2rem;
      line-height: 1.6;
    }
    .terms a { color: var(--accent); text-decoration: none; }

    /* ── RESPONSIVE ── */
    @media (max-width: 820px) {
      .panel-left { display: none; }
      .panel-right { width: 100%; }
    }

    /* entrance animation */
    .form-box > * {
      opacity: 0;
      transform: translateY(14px);
      animation: fadeUp .5s forwards;
    }
    .form-box > *:nth-child(1) { animation-delay: .05s; }
    .form-box > *:nth-child(2) { animation-delay: .10s; }
    .form-box > *:nth-child(3) { animation-delay: .15s; }
    .form-box > *:nth-child(4) { animation-delay: .18s; }
    .form-box > *:nth-child(5) { animation-delay: .21s; }
    .form-box > *:nth-child(6) { animation-delay: .24s; }
    .form-box > *:nth-child(7) { animation-delay: .27s; }
    .form-box > *:nth-child(8) { animation-delay: .30s; }
    .form-box > *:nth-child(9) { animation-delay: .33s; }

    @keyframes fadeUp {
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<!-- LEFT -->
<div class="panel-left">
  <div class="brand">Urban Style</div>
  <div class="panel-left-tagline">
    <h2>ÚNETE A<br>LA <span>CULTURA</span><br>URBANA.</h2>
    <p>Crea tu cuenta gratis y accede a drops exclusivos, ofertas y más.</p>
  </div>
  <div class="dots">
    <span class="active"></span><span></span><span></span>
  </div>
</div>

<!-- RIGHT -->
<div class="panel-right">
  <div class="form-box">

    <h1>CREAR CUENTA</h1>
    <p class="subtitle">¿Ya tienes una? <a href="login.php">Inicia sesión</a></p>

    <?php
      $msg = $msg_type = '';
      if (isset($_GET['error'])) {
        $msg_type = 'error';
        $msgs = [
          'campos'  => 'Todos los campos son obligatorios.',
          'correo'  => 'El formato del correo no es válido.',
          'pass'    => 'La contraseña debe tener al menos 8 caracteres.',
          'existe'  => 'Este correo ya está registrado.',
          'db'      => 'Error al guardar. Intenta de nuevo.',
        ];
        $msg = $msgs[$_GET['error']] ?? 'Ocurrió un error inesperado.';
      }
      if (isset($_GET['ok'])) {
        $msg_type = 'success';
        $msg = '¡Cuenta creada exitosamente! Ahora puedes iniciar sesión.';
      }
    ?>
    <?php if ($msg): ?>
      <div class="alert <?= $msg_type ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form action="guardar_usuario.php" method="POST" id="regForm">

      <!-- Nombre -->
      <div class="field">
        <label>Nombre completo</label>
        <div class="field-wrap">
          <i class="fa-solid fa-user fi-left"></i>
          <input type="text" name="nombre" id="nombre"
                 placeholder="Ej: Carlos Mendoza"
                 maxlength="100" required
                 value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
        </div>
      </div>

      <!-- Correo -->
      <div class="field">
        <label>Correo electrónico</label>
        <div class="field-wrap">
          <i class="fa-solid fa-envelope fi-left"></i>
          <input type="email" name="correo" id="correo"
                 placeholder="tucorreo@gmail.com"
                 maxlength="150" required
                 value="<?= htmlspecialchars($_GET['correo'] ?? '') ?>">
        </div>
      </div>

      <!-- Teléfono -->
      <div class="field">
        <label>Teléfono <span style="color:#555;font-weight:400">(opcional)</span></label>
        <div class="field-wrap">
          <i class="fa-solid fa-phone fi-left"></i>
          <input type="tel" name="telefono" placeholder="9999-0000" maxlength="20">
        </div>
      </div>

      <!-- Contraseña — OJITO CORREGIDO -->
      <div class="field">
        <label>Contraseña</label>
        <div class="field-wrap">
          <i class="fa-solid fa-lock fi-left"></i>
          <input type="password" name="contrasena" id="pass"
                 placeholder="Mínimo 8 caracteres"
                 minlength="8" maxlength="72" required
                 oninput="checkStrength(this.value)">
          <!-- El botón usa Font Awesome y cambia el ícono correctamente -->
          <button type="button" class="toggle-pass" id="btnOjito"
                  onclick="togglePass()" title="Mostrar/ocultar contraseña">
            <i class="fa-solid fa-eye" id="ojito-icon"></i>
          </button>
        </div>
        <div class="strength-bar">
          <div id="s1"></div><div id="s2"></div><div id="s3"></div><div id="s4"></div>
        </div>
        <div class="strength-label" id="sLabel"></div>
      </div>

      <!-- Tipo de cuenta — tarjetas radio -->
      <div class="field">
        <label>Tipo de cuenta</label>

        <div class="tipo-grid">
          <!-- Cliente -->
          <div class="tipo-card cliente">
            <input type="radio" name="tipo_cuenta" id="tipo_cliente"
                   value="cliente" checked onchange="onTipoChange(this)">
            <label for="tipo_cliente">
              <i class="fa-solid fa-bag-shopping"></i>
              Cliente
            </label>
          </div>
          <!-- Vendedor -->
          <div class="tipo-card vendedor">
            <input type="radio" name="tipo_cuenta" id="tipo_vendedor"
                   value="vendedor" onchange="onTipoChange(this)">
            <label for="tipo_vendedor">
              <i class="fa-solid fa-store"></i>
              Vendedor
            </label>
          </div>
        </div>

        <!-- Info extra si elige vendedor -->
        <div class="vendedor-info" id="vendedorInfo">
          <i class="fa-solid fa-circle-info"></i>
          Como <strong>vendedor</strong> podrás publicar tus productos en Urban Style
          y gestionar tus ventas desde tu panel personal.
          Nuestro equipo revisará tu solicitud en 24–48 h.
        </div>
      </div>

      <button type="submit" class="btn-submit" id="btnSubmit">
        CREAR MI CUENTA
      </button>

    </form>

    <div class="terms">
      Al registrarte aceptas nuestros
      <a href="#">Términos de servicio</a> y
      <a href="#">Política de privacidad</a>.
    </div>

  </div>
</div>

<script>
  /* ── OJITO CORREGIDO ─────────────────────────────── */
  function togglePass() {
    const input = document.getElementById('pass');
    const icon  = document.getElementById('ojito-icon');
    const visible = input.type === 'password';
    input.type  = visible ? 'text' : 'password';
    // Cambiar ícono: ojo abierto ↔ ojo tachado
    icon.className = visible ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
  }

  /* ── TIPO DE CUENTA ──────────────────────────────── */
  function onTipoChange(radio) {
    const info = document.getElementById('vendedorInfo');
    info.classList.toggle('show', radio.value === 'vendedor');
  }

  /* ── MEDIDOR DE FORTALEZA ────────────────────────── */
  function checkStrength(val) {
    const bars   = [
      document.getElementById('s1'),
      document.getElementById('s2'),
      document.getElementById('s3'),
      document.getElementById('s4')
    ];
    const label  = document.getElementById('sLabel');
    const colors = ['#ff4d4d', '#ff944d', '#f5d020', '#c8f542'];
    const labels = ['Muy débil', 'Débil', 'Regular', 'Fuerte'];

    let score = 0;
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;

    bars.forEach((b, i) => {
      b.style.background = i < score ? colors[score - 1] : 'var(--gray2)';
    });
    label.textContent = val.length ? (labels[score - 1] || '') : '';
  }

  document.getElementById('regForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnSubmit');
    btn.textContent = 'GUARDANDO...';
    btn.classList.add('loading');
  });
</script>

</body>
</html>