<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrito - Urban Style</title>
<link rel="stylesheet" href="css/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
/* ══ MODAL BASE ══ */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}
.modal-overlay.show { display: flex; }

.modal-card {
    background: #fff;
    border-radius: 24px;
    padding: 40px;
    width: 460px;
    max-width: 95vw;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.22);
    animation: slideUp .3s ease;
}
@keyframes slideUp {
    from { transform: translateY(40px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}

.modal-card h3 {
    font-size: 22px;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
}
.modal-sub {
    color: #888;
    font-size: 13px;
    margin-bottom: 22px;
    font-family: 'Poppins', sans-serif;
}
.modal-card label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: #555;
    margin-bottom: 5px;
    margin-top: 15px;
    letter-spacing: .6px;
    text-transform: uppercase;
    font-family: 'Poppins', sans-serif;
}
.modal-card input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #E8E4DC;
    border-radius: 10px;
    font-size: 14px;
    outline: none;
    background: #FAFAFA;
    transition: .2s;
    font-family: 'Poppins', sans-serif;
    box-sizing: border-box;
}
.modal-card input:focus { border-color: #D4AF7A; background: #fff; }

.card-row { display: flex; gap: 14px; }
.card-row > div { flex: 1; }

.modal-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0;
    border-top: 1px solid #E8E4DC;
    border-bottom: 1px solid #E8E4DC;
    margin: 20px 0;
    font-size: 16px;
    font-weight: 600;
    font-family: 'Poppins', sans-serif;
}
.modal-total span { color: #D4AF7A; font-size: 22px; font-weight: 700; }

.modal-pay-btn {
    width: 100%;
    padding: 14px;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    cursor: pointer;
    transition: .3s;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    margin-top: 4px;
}
.modal-pay-btn:hover { opacity: .85; }
.modal-close {
    width: 100%;
    padding: 11px;
    background: transparent;
    border: 1px solid #E8E4DC;
    border-radius: 12px;
    font-size: 13px;
    cursor: pointer;
    margin-top: 10px;
    transition: .3s;
    font-family: 'Poppins', sans-serif;
    color: #555;
}
.modal-close:hover { background: #f0f0f0; }

/* ══ SUCCESS ══ */
.success-msg {
    display: none;
    text-align: center;
    padding: 10px 0;
    font-family: 'Poppins', sans-serif;
}
.success-msg i { font-size: 56px; color: #4CAF50; margin-bottom: 14px; display: block; }
.success-msg h4 { font-size: 20px; margin-bottom: 8px; }
.success-msg p  { color: #888; font-size: 13px; margin-bottom: 22px; }

/* ══ TRANSFERENCIA INFO BOX ══ */
.transfer-box {
    background: #FAF9F6;
    border: 1px solid #E8E4DC;
    border-radius: 14px;
    padding: 20px;
    margin-top: 4px;
}
.transfer-box p { font-family:'Poppins',sans-serif; font-size:13px; color:#555; margin:0 0 6px; }
.transfer-box strong { color:#2C2C2C; font-size:15px; }
.transfer-box .bank-label { font-size:11px; font-weight:600; letter-spacing:.6px; text-transform:uppercase; color:#D4AF7A; margin-top:14px; margin-bottom:3px; display:block; }
.transfer-field {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border: 1px solid #E8E4DC;
    border-radius: 9px;
    padding: 10px 14px;
    margin-bottom: 8px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: #2C2C2C;
}
.transfer-field button {
    background: none;
    border: none;
    cursor: pointer;
    color: #D4AF7A;
    font-size: 15px;
    padding: 0;
    transition: .2s;
}
.transfer-field button:hover { color: #2C2C2C; }
.copy-toast {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    background: #2C2C2C;
    color: #fff;
    padding: 10px 22px;
    border-radius: 50px;
    font-size: 13px;
    font-family: 'Poppins', sans-serif;
    opacity: 0;
    pointer-events: none;
    transition: opacity .3s;
    z-index: 99999;
}
.copy-toast.show { opacity: 1; }

/* ══ CARRITO VACÍO ══ */
.cart-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 100px 20px;
    text-align: center;
    font-family: 'Poppins', sans-serif;
    color: #aaa;
}
.cart-empty i { font-size: 72px; color: #E8E4DC; margin-bottom: 20px; }
.cart-empty h2 { font-size: 22px; color: #555; margin-bottom: 10px; }
.cart-empty p { font-size: 14px; margin-bottom: 28px; }
.cart-empty a {
    padding: 13px 32px;
    background: #2C2C2C;
    color: #fff;
    border-radius: 50px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: .3s;
}
.cart-empty a:hover { background: #D4AF7A; }

/* ══ PAYPAL BRAND ══ */
.paypal-logo { color: #003087; }
.paypal-logo .pp-sky { color: #009cde; }

/* ══ CUENTA INPUT para transferencia ══ */
.cuenta-input-wrap { margin-top: 18px; }
.cuenta-input-wrap label { color: #D4AF7A; }
</style>
</head>
<body>

<?php require_once "header.php"; ?>


<!-- ══════════════════════════════════
     MODAL PAYPAL
════════════════════════════════════ -->
<div id="modalPaypal" class="modal-overlay">
    <div class="modal-card">
        <div id="paypalForm">
            <h3>
                <span style="display:inline-flex;gap:2px;">
                    <i class="fa-brands fa-paypal" style="color:#003087;"></i>
                </span>
                Pago con PayPal
            </h3>
            <p class="modal-sub">Ingresa tu correo de PayPal para continuar</p>

            <label>Correo de PayPal</label>
            <input type="email" id="ppEmail" placeholder="tu@correo.com">

            <label>Contraseña de PayPal</label>
            <input type="password" id="ppPass" placeholder="••••••••">

            <div class="modal-total">
                <p>Total a pagar</p>
                <span id="ppTotal">$0.00</span>
            </div>

            <button class="modal-pay-btn" style="background:#003087;" onclick="procesarPaypal()">
                <i class="fa-brands fa-paypal"></i>&nbsp; Pagar con PayPal
            </button>
            <button class="modal-close" onclick="cerrarModal('modalPaypal')">Cancelar</button>
        </div>
        <div class="success-msg" id="ppSuccess">
            <i class="fa-solid fa-circle-check"></i>
            <h4>¡Pago con PayPal exitoso!</h4>
            <p>Tu orden ha sido registrada.<br>Recibirás un correo de confirmación.</p>
            <button class="modal-pay-btn" style="background:#2C2C2C;" onclick="cerrarModal('modalPaypal')">Cerrar</button>
        </div>
    </div>
</div>


<!-- ══════════════════════════════════
     MODAL VISA
════════════════════════════════════ -->
<div id="modalVisa" class="modal-overlay">
    <div class="modal-card">
        <div id="visaForm">
            <h3><i class="fa-brands fa-cc-visa" style="color:#1a1f71;font-size:32px;"></i> Pago con Visa</h3>
            <p class="modal-sub">Ingresa los datos de tu tarjeta Visa</p>

            <label>Nombre en la tarjeta</label>
            <input type="text" id="visaName" placeholder="Como aparece en la tarjeta">

            <label>Número de tarjeta Visa</label>
            <input type="text" id="visaNumber" placeholder="4XXX XXXX XXXX XXXX" maxlength="19">

            <div class="card-row">
                <div>
                    <label>Vencimiento</label>
                    <input type="text" id="visaExp" placeholder="MM/AA" maxlength="5">
                </div>
                <div>
                    <label>CVV</label>
                    <input type="text" id="visaCvv" placeholder="123" maxlength="4">
                </div>
            </div>

            <div class="modal-total">
                <p>Total a pagar</p>
                <span id="visaTotal">$0.00</span>
            </div>

            <button class="modal-pay-btn" style="background:#1a1f71;" onclick="procesarVisa()">
                <i class="fa-solid fa-lock"></i>&nbsp; Pagar con Visa
            </button>
            <button class="modal-close" onclick="cerrarModal('modalVisa')">Cancelar</button>
        </div>
        <div class="success-msg" id="visaSuccess">
            <i class="fa-solid fa-circle-check"></i>
            <h4>¡Pago con Visa exitoso!</h4>
            <p>Tu orden ha sido registrada.<br>Recibirás un correo de confirmación.</p>
            <button class="modal-pay-btn" style="background:#2C2C2C;" onclick="cerrarModal('modalVisa')">Cerrar</button>
        </div>
    </div>
</div>


<!-- ══════════════════════════════════
     MODAL TRANSFERENCIA
════════════════════════════════════ -->
<div id="modalTransfer" class="modal-overlay">
    <div class="modal-card">
        <div id="transferForm">
            <h3><i class="fa-solid fa-building-columns" style="color:#D4AF7A;"></i> Transferencia Bancaria</h3>
            <p class="modal-sub">Realiza la transferencia al número de cuenta y envía el comprobante</p>

            <div class="transfer-box">
                <span class="bank-label">Banco</span>
                <div class="transfer-field">Banco Atlántida <button onclick="copiar('Banco Atlántida')" title="Copiar"><i class="fa-regular fa-copy"></i></button></div>

                <span class="bank-label">Número de Cuenta</span>
                <div class="transfer-field" id="numCuentaDisplay">— <button onclick="copiar(document.getElementById('numCuentaDisplay').dataset.val || '')" title="Copiar"><i class="fa-regular fa-copy"></i></button></div>

                <span class="bank-label">Titular</span>
                <div class="transfer-field">Urban Style S.A. <button onclick="copiar('Urban Style S.A.')" title="Copiar"><i class="fa-regular fa-copy"></i></button></div>

                <span class="bank-label">Monto a Transferir</span>
                <div class="transfer-field"><span id="transferTotal">$0.00</span> <button onclick="copiar(document.getElementById('transferTotal').textContent)" title="Copiar"><i class="fa-regular fa-copy"></i></button></div>
            </div>

            <!-- Campo para ingresar número de cuenta -->
            <div class="cuenta-input-wrap">
                <label>Tu número de cuenta (para confirmar tu pago)</label>
                <input type="text" id="clienteCuenta" placeholder="Ej: 1234-5678-9012-3456">
            </div>

            <label style="margin-top:14px;">Nombre del titular de tu cuenta</label>
            <input type="text" id="clienteTitular" placeholder="Nombre completo">

            <div class="modal-total" style="margin-top:18px;">
                <p>Total a pagar</p>
                <span id="transferTotalModal">$0.00</span>
            </div>

            <button class="modal-pay-btn" style="background:#D4AF7A;" onclick="procesarTransferencia()">
                <i class="fa-solid fa-paper-plane"></i>&nbsp; Confirmar Transferencia
            </button>
            <button class="modal-close" onclick="cerrarModal('modalTransfer')">Cancelar</button>
        </div>
        <div class="success-msg" id="transferSuccess">
            <i class="fa-solid fa-circle-check"></i>
            <h4>¡Transferencia registrada!</h4>
            <p>Verificaremos tu pago en un máximo de 24 horas.<br>Gracias por tu compra.</p>
            <button class="modal-pay-btn" style="background:#2C2C2C;" onclick="cerrarModal('modalTransfer')">Cerrar</button>
        </div>
    </div>
</div>

<!-- Toast de copia -->
<div class="copy-toast" id="copyToast">¡Copiado al portapapeles!</div>


<!-- ══════════════════════════════════
     CONTENIDO PRINCIPAL
════════════════════════════════════ -->
<section class="cart-container" id="cartContainer" style="display:none;">

    <div class="cart-left">
        <h1>Tu Carrito</h1>
        <div id="cartItems"></div>
    </div>

    <div class="cart-right">
        <h2>Resumen</h2>

        <div class="total-box">
            <p>Subtotal</p><span id="resSubtotal">$0</span>
        </div>
        <div class="total-box">
            <p>Envío</p><span>$5</span>
        </div>
        <div class="total-box total">
            <p>Total</p><span id="resTotal">$5</span>
        </div>

        <h3 style="margin-top:28px; margin-bottom:0;">Método de Pago</h3>

        <div class="payment-methods">
            <button class="pay-btn" data-method="visa">
                <i class="fa-brands fa-cc-visa"></i> Visa
            </button>
            <button class="pay-btn" data-method="paypal">
                <i class="fa-brands fa-paypal"></i> PayPal
            </button>
            <button class="pay-btn" data-method="transferencia">
                <i class="fa-solid fa-building-columns"></i> Transferencia
            </button>
        </div>

        <button class="checkout-btn" id="btnCheckout">Finalizar Compra</button>
    </div>

</section>

<!-- Carrito vacío -->
<div class="cart-empty" id="cartEmpty">
    <i class="fa-solid fa-cart-shopping"></i>
    <h2>Tu carrito está vacío</h2>
    <p>Agrega productos desde nuestro catálogo para comenzar.</p>
    <a href="productos.php">Ver Catálogo</a>
</div>


<script>
/* ══════════════════════════════════
   DATOS DEL CARRITO (demo estático)
   En producción esto vendría de
   localStorage o sesión PHP
════════════════════════════════════ */
const ENVIO = 5;

// Carrito vacío por defecto — productos se agregan desde productos.php
let carrito = JSON.parse(localStorage.getItem('urbanstyle_cart') || '[]');

function renderCart() {
    const container = document.getElementById('cartItems');
    const empty     = document.getElementById('cartEmpty');
    const section   = document.getElementById('cartContainer');

    if (carrito.length === 0) {
        empty.style.display   = 'flex';
        section.style.display = 'none';
        return;
    }

    empty.style.display   = 'none';
    section.style.display = 'flex';

    container.innerHTML = '';
    let subtotal = 0;

    carrito.forEach((item, i) => {
        subtotal += item.precio;
        container.innerHTML += `
            <div class="cart-item">
                <img src="${item.img}" alt="${item.nombre}" onerror="this.src='https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=200&q=60'">
                <div class="cart-info">
                    <h2>${item.nombre}</h2>
                    <p>${item.detalle || ''}</p>
                    <span>$${item.precio}</span>
                </div>
                <button onclick="eliminar(${i})" style="margin-left:auto;background:none;border:none;cursor:pointer;color:#ccc;font-size:18px;transition:.2s;" onmouseover="this.style.color='#c0392b'" onmouseout="this.style.color='#ccc'">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
    });

    const total = subtotal + ENVIO;
    document.getElementById('resSubtotal').textContent = '$' + subtotal;
    document.getElementById('resTotal').textContent    = '$' + total;

    // Actualizar totales en modales
    const fmt = '$' + total.toFixed(2);
    document.getElementById('ppTotal').textContent          = fmt;
    document.getElementById('visaTotal').textContent        = fmt;
    document.getElementById('transferTotal').textContent    = fmt;
    document.getElementById('transferTotalModal').textContent = fmt;
    document.getElementById('numCuentaDisplay').dataset.val = '';
}

function eliminar(i) {
    carrito.splice(i, 1);
    localStorage.setItem('urbanstyle_cart', JSON.stringify(carrito));
    renderCart();
}

renderCart();


/* ══ Método de pago ══ */
let metodoActivo = null;

document.querySelectorAll('.pay-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.pay-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        metodoActivo = btn.dataset.method;
    });
});

document.getElementById('btnCheckout').addEventListener('click', () => {
    if (carrito.length === 0) { alert('Agrega productos primero.'); return; }
    if (!metodoActivo) { alert('Por favor selecciona un método de pago.'); return; }

    const modales = { visa: 'modalVisa', paypal: 'modalPaypal', transferencia: 'modalTransfer' };
    abrirModal(modales[metodoActivo]);
});


/* ══ Modal helpers ══ */
function abrirModal(id) {
    // Reset forms
    ['paypalForm','ppSuccess','visaForm','visaSuccess','transferForm','transferSuccess'].forEach(f => {
        const el = document.getElementById(f);
        if (el) el.style.display = f.includes('Form') ? 'block' : 'none';
    });
    document.getElementById(id).classList.add('show');
}

function cerrarModal(id) {
    document.getElementById(id).classList.remove('show');
}

// Cerrar al hacer clic fuera
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
});


/* ══ Procesar PayPal ══ */
function procesarPaypal() {
    const email = document.getElementById('ppEmail').value.trim();
    const pass  = document.getElementById('ppPass').value.trim();
    if (!email || !pass) { alert('Por favor completa los datos de PayPal.'); return; }
    mostrarExito('paypalForm', 'ppSuccess');
}

/* ══ Procesar Visa ══ */
function procesarVisa() {
    const nombre = document.getElementById('visaName').value.trim();
    const numero = document.getElementById('visaNumber').value.trim();
    const exp    = document.getElementById('visaExp').value.trim();
    const cvv    = document.getElementById('visaCvv').value.trim();
    if (!nombre || !numero || !exp || !cvv) { alert('Por favor completa todos los datos de la tarjeta.'); return; }
    mostrarExito('visaForm', 'visaSuccess');
}

/* ══ Procesar Transferencia ══ */
function procesarTransferencia() {
    const cuenta   = document.getElementById('clienteCuenta').value.trim();
    const titular  = document.getElementById('clienteTitular').value.trim();
    if (!cuenta || !titular) { alert('Por favor ingresa tu número de cuenta y nombre del titular.'); return; }
    mostrarExito('transferForm', 'transferSuccess');
}

function mostrarExito(formId, successId) {
    document.getElementById(formId).style.display   = 'none';
    document.getElementById(successId).style.display = 'block';
    // Vaciar carrito tras éxito
    carrito = [];
    localStorage.removeItem('urbanstyle_cart');
}


/* ══ Copiar al portapapeles ══ */
function copiar(texto) {
    if (!texto) return;
    navigator.clipboard.writeText(texto).then(() => {
        const toast = document.getElementById('copyToast');
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 2000);
    });
}


/* ══ Formato automático tarjeta Visa ══ */
document.getElementById('visaNumber').addEventListener('input', function() {
    let v = this.value.replace(/\D/g, '').substring(0, 16);
    this.value = v.replace(/(.{4})/g, '$1 ').trim();
});
document.getElementById('visaExp').addEventListener('input', function() {
    let v = this.value.replace(/\D/g, '').substring(0, 4);
    if (v.length >= 3) v = v.substring(0, 2) + '/' + v.substring(2);
    this.value = v;
});
</script>

</body>
</html>