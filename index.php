<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Urban Style</title>
<link rel="stylesheet" type="text/css" href="./css/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
#modalHistoria {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}
#modalHistoria.show { display: flex; }

.historia-card {
    background: #fff;
    border-radius: 28px;
    width: 600px;
    max-width: 95vw;
    max-height: 88vh;
    overflow-y: auto;
    box-shadow: 0 24px 70px rgba(0,0,0,0.22);
    animation: popIn .35s cubic-bezier(.34,1.56,.64,1);
}
@keyframes popIn {
    from { transform: scale(.88); opacity: 0; }
    to   { transform: scale(1);   opacity: 1; }
}

.historia-header {
    background: #2C2C2C;
    padding: 38px 40px 30px;
    border-radius: 28px 28px 0 0;
    position: relative;
}
.historia-header .badge {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #D4AF7A;
    margin-bottom: 10px;
    font-family: 'Poppins', sans-serif;
}
.historia-header h2 {
    font-family: 'Poppins', sans-serif;
    font-size: 28px;
    font-weight: 700;
    color: #fff;
    margin: 0;
    line-height: 1.2;
}
.historia-header h2 span { color: #D4AF7A; font-style: italic; font-weight: 300; }
.historia-close {
    position: absolute;
    top: 20px;
    right: 22px;
    background: rgba(255,255,255,0.1);
    border: none;
    color: #fff;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 16px;
    transition: .2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.historia-close:hover { background: rgba(255,255,255,0.2); }

.historia-body { padding: 36px 40px 40px; }

.historia-timeline {
    display: flex;
    flex-direction: column;
    gap: 0;
}
.historia-step {
    display: flex;
    gap: 20px;
    padding-bottom: 28px;
    position: relative;
}
.historia-step:last-child { padding-bottom: 0; }
.historia-step:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 19px;
    top: 40px;
    width: 2px;
    bottom: 0;
    background: #E8E4DC;
}
.step-icon {
    width: 40px;
    height: 40px;
    min-width: 40px;
    background: #D4AF7A;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 15px;
    z-index: 1;
}
.step-content {}
.step-year {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: #D4AF7A;
    text-transform: uppercase;
    font-family: 'Poppins', sans-serif;
    margin-bottom: 3px;
}
.step-title {
    font-size: 15px;
    font-weight: 600;
    color: #2C2C2C;
    font-family: 'Poppins', sans-serif;
    margin-bottom: 5px;
}
.step-text {
    font-size: 13px;
    color: #777;
    line-height: 1.65;
    font-family: 'Poppins', sans-serif;
}

.historia-quote {
    background: #FAF9F6;
    border-left: 3px solid #D4AF7A;
    border-radius: 0 12px 12px 0;
    padding: 18px 20px;
    margin: 28px 0 0;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-style: italic;
    color: #555;
    line-height: 1.7;
}
.historia-quote strong { color: #D4AF7A; font-style: normal; }

.historia-cta {
    margin-top: 28px;
    display: flex;
    gap: 12px;
}
.historia-cta a {
    padding: 12px 28px;
    border-radius: 50px;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: .3s;
}
.historia-cta .cta-dark  { background: #2C2C2C; color: #fff; }
.historia-cta .cta-dark:hover  { background: #D4AF7A; }
.historia-cta .cta-light { background: transparent; color: #2C2C2C; border: 1px solid #E8E4DC; }
.historia-cta .cta-light:hover { background: #f0f0f0; }

.hero-images {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    max-width: 560px;
}
.hero-images img {
    width: 100%;
    border-radius: 18px;
    object-fit: cover;
    height: 220px;
}
.hero-images img:first-child,
.hero-images img:nth-child(3) {
    height: 260px;
}

.hero-launch-card {
    background: rgba(255,255,255,0.95);
    border-radius: 16px;
    padding: 18px 22px;
    grid-column: 1 / -1;
    margin-top: -6px;
}
.hero-launch-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #D4AF7A;
    margin-bottom: 5px;
    font-family: 'Poppins', sans-serif;
}
.hero-launch-title {
    font-size: 17px;
    font-weight: 600;
    color: #2C2C2C;
    font-family: 'Poppins', sans-serif;
}
</style>
</head>
<body>

<div id="modalHistoria">
    <div class="historia-card">
        <div class="historia-header">
            <button class="historia-close" onclick="cerrarHistoria()"><i class="fa-solid fa-xmark"></i></button>
            <p class="badge">Nuestra Historia</p>
            <h2>Cómo nació <span>Urban Style</span></h2>
        </div>
        <div class="historia-body">
            <div class="historia-timeline">

                <div class="historia-step">
                    <div class="step-icon"><i class="fa-solid fa-lightbulb"></i></div>
                    <div class="step-content">
                        <p class="step-year">2020 · El origen</p>
                        <p class="step-title">La idea que lo cambió todo</p>
                        <p class="step-text">Todo empezó en un pequeño taller de Tegucigalpa. Dos amigos apasionados por la moda urbana notaron que los accesorios disponibles en el mercado local carecían de identidad y calidad. Las piezas importadas eran costosas y las locales no reflejaban el verdadero carácter de la calle.</p>
                    </div>
                </div>

                <div class="historia-step">
                    <div class="step-icon"><i class="fa-solid fa-hammer"></i></div>
                    <div class="step-content">
                        <p class="step-year">2021 · El primer taller</p>
                        <p class="step-title">Manos a la obra</p>
                        <p class="step-text">Con una inversión inicial mínima y mucha creatividad, comenzaron a diseñar sus primeras piezas: cinturones de cuero genuino, collares con materiales nobles y bolsos de diseño propio. Cada accesorio se creaba a mano, con atención al detalle y carácter urbano auténtico.</p>
                    </div>
                </div>

                <div class="historia-step">
                    <div class="step-icon"><i class="fa-solid fa-store"></i></div>
                    <div class="step-content">
                        <p class="step-year">2022 · Primer lanzamiento</p>
                        <p class="step-title">Urban Style abre sus puertas</p>
                        <p class="step-text">La marca hizo su debut oficial con una colección de 20 piezas exclusivas. La respuesta fue inmediata: agotaron existencias en menos de una semana. Eso confirmó que había un mercado ávido de accesorios con personalidad y calidad artesanal.</p>
                    </div>
                </div>

                <div class="historia-step">
                    <div class="step-icon"><i class="fa-solid fa-globe"></i></div>
                    <div class="step-content">
                        <p class="step-year">2023–2024 · Crecimiento</p>
                        <p class="step-title">De taller a marca regional</p>
                        <p class="step-text">Urban Style creció hasta contar con más de 10,000 clientes únicos, una tienda online, y colecciones que abarcan relojes, lentes, gorras, joyería y carteras. Hoy cada pieza sigue haciéndose con la misma pasión del primer día.</p>
                    </div>
                </div>

            </div>

            <div class="historia-quote">
                "Queríamos crear accesorios que hablen sin palabras, que cuando los lleves, cuenten quién eres. Ese sigue siendo nuestro norte."
                <br><strong>— Fundadores de Urban Style</strong>
            </div>

            <div class="historia-cta">
                <a href="productos.php" class="cta-dark"><i class="fa-solid fa-bag-shopping"></i> Ver Colecciones</a>
                <a href="#contacto" class="cta-light" onclick="cerrarHistoria()">Contáctanos</a>
            </div>
        </div>
    </div>
</div>


<?php require_once "header.php"; ?>

<section class="hero">
    <div class="hero-text">
        <p class="small-title">ESTILO URBANO</p>
        <h1>
            Detalles <br>
            que <br>
            <span>definen</span>
        </h1>
        <p class="description">
            Accesorios artesanales que cuentan tu historia.
            Cada pieza diseñada para elevar tu estilo urbano
            con autenticidad y carácter.
        </p>
        <div class="buttons">
            <!-- Explorar Colecciones → va a #destacados -->
            <button class="btn-dark" onclick="window.location.href='#destacados'">
                Explorar Colecciones &nbsp;→
            </button>
            <!-- Nuestra Historia → abre modal -->
            <button class="btn-light" onclick="abrirHistoria()">
                Nuestra Historia
            </button>
        </div>
    </div>

    <div class="hero-images">
        <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&q=80" alt="Collar urbano">
        <img src="https://images.unsplash.com/photo-1630019852942-f89202989a59?w=600&q=80" alt="Aretes luxury">
        <img src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80" alt="Bolsos colección">
        <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&q=80" alt="Colección heritage">
    </div>
</section>

<section class="nosotros" id="nosotros">
    <h2>Nosotros</h2>
    <p>
        Urban Style nace para redefinir el estilo urbano moderno,
        ofreciendo accesorios exclusivos con elegancia y autenticidad.
        Más de 10K clientes únicos confían en cada pieza que creamos.
    </p>
</section>

<section class="destacados" id="destacados">
    <h2>Productos Destacados</h2>
    <div class="destacados-grid">

        <div class="destacado-card">
            <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&q=80" alt="Collar Premium">
            <h3>Collar Premium</h3>
        </div>

        <div class="destacado-card">
            <img src="https://images.unsplash.com/photo-1630019852942-f89202989a59?w=600&q=80" alt="Aretes Luxury Drop">
            <h3>Aretes Luxury Drop</h3>
        </div>

        <div class="destacado-card">
            <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&q=80" alt="Mini Bag Gold Edition">
            <h3>Mini Bag Gold Edition</h3>
        </div>

        <div class="destacado-card">
            <img src="https://images.unsplash.com/photo-1509048191080-d2984bad6ae5?w=600&q=80" alt="Reloj Rose Gold Luxe">
            <h3>Reloj Rose Gold Luxe</h3>
        </div>

        <div class="destacado-card">
            <img src="https://i.pinimg.com/1200x/12/b6/36/12b636d3de9a876c2f967ffcdbd79194.jpg" alt="Cinturón Cuero Clásico">
            <h3>Cinturón Cuero Clásico</h3>
        </div>

        <div class="destacado-card">
            <img src="https://images.unsplash.com/photo-1577803645773-f96470509666?w=600&q=80" alt="Lentes Retro Round">
            <h3>Lentes Retro Round</h3>
        </div>

    </div>
</section>

<section class="contacto" id="contacto">
    <h2>Contacto</h2>
    <form class="contact-form" action="guardar_contacto.php" method="POST">

        <?php
        if (isset($_GET['contacto'])) {
            $tipo = $_GET['contacto'];
            $msgs = [
                'ok'      => ['success', '✅ ¡Mensaje enviado! Nos pondremos en contacto contigo pronto.'],
                'campos'  => ['error',   '⚠️ Por favor completa todos los campos.'],
                'correo'  => ['error',   '⚠️ El formato del correo no es válido.'],
                'error'   => ['error',   '❌ Ocurrió un error al enviar. Intenta de nuevo.'],
            ];
            if (isset($msgs[$tipo])) {
                [$clase, $texto] = $msgs[$tipo];
                echo "<div style=\"
                    padding: 14px 20px;
                    border-radius: 12px;
                    margin-bottom: 22px;
                    font-size: 15px;
                    font-family: 'Poppins', sans-serif;
                    background: " . ($clase === 'success' ? '#edf7f0; border: 1px solid #b2dfdb; color: #27ae60;' : '#fdf0f0; border: 1px solid #f5c6c6; color: #e74c3c;') . "
                \">$texto</div>";
            }
        }
        ?>

        <div class="form-row">
            <input type="text"  name="nombre"  placeholder="Nombre"  required
                   value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>">
            <input type="email" name="correo"  placeholder="Correo"  required
                   value="<?= htmlspecialchars($_GET['correo'] ?? '') ?>">
        </div>
        <textarea name="mensaje" placeholder="Mensaje" required></textarea>
        <button type="submit">Enviar</button>
    </form>
</section>

<script>
function abrirHistoria() {
    document.getElementById('modalHistoria').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function cerrarHistoria() {
    document.getElementById('modalHistoria').classList.remove('show');
    document.body.style.overflow = '';
}
document.getElementById('modalHistoria').addEventListener('click', function(e) {
    if (e.target === this) cerrarHistoria();
});
// ESC
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarHistoria(); });
</script>

</body>
</html>