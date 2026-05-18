<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Catálogo - Urban Style</title>
<link rel="stylesheet" href="css/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
.catalogo-hero{width:100%;padding:80px 10% 40px;background:#fff;border-bottom:1px solid #E8E4DC;}
.catalogo-hero h1{font-size:64px;font-weight:700;margin-bottom:10px;}
.catalogo-hero h1 span{color:#D4AF7A;font-style:italic;font-weight:400;}
.catalogo-hero p{font-size:18px;color:#777;}

.filtros-bar{width:100%;padding:28px 10%;background:#FAF9F6;border-bottom:1px solid #E8E4DC;display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
.filtro-btn{padding:10px 22px;border:1px solid #E8E4DC;border-radius:50px;background:#fff;font-family:'Poppins',sans-serif;font-size:13px;font-weight:500;color:#2C2C2C;cursor:pointer;transition:.25s;display:flex;align-items:center;gap:7px;}
.filtro-btn:hover{background:#2C2C2C;color:#fff;border-color:#2C2C2C;}
.filtro-btn.activo{background:#D4AF7A;color:#fff;border-color:#D4AF7A;}

.catalogo-section{padding:40px 10% 100px;background:#FAF9F6;}
.categoria-titulo{font-size:26px;font-weight:600;margin:50px 0 26px;padding-bottom:12px;border-bottom:2px solid #E8E4DC;display:flex;align-items:center;gap:12px;font-family:'Poppins',sans-serif;}
.categoria-titulo i{color:#D4AF7A;font-size:22px;}

.catalogo-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(255px,1fr));gap:26px;}

.producto-card{background:#fff;border-radius:20px;border:1px solid #E8E4DC;overflow:hidden;transition:.3s;position:relative;}
.producto-card:hover{transform:translateY(-8px);box-shadow:0 16px 40px rgba(0,0,0,0.1);}

.producto-badge{position:absolute;top:14px;left:14px;color:#fff;font-size:11px;font-weight:600;padding:4px 12px;border-radius:50px;letter-spacing:.5px;font-family:'Poppins',sans-serif;text-transform:uppercase;z-index:2;}
.producto-badge.nuevo{background:#2C2C2C;}
.producto-badge.oferta{background:#c0392b;}

.producto-img{width:100%;height:260px;object-fit:cover;display:block;transition:.4s;}
.producto-card:hover .producto-img{transform:scale(1.04);}
.img-wrap{overflow:hidden;height:260px;}

.producto-body{padding:18px 20px 20px;}
.producto-categoria{font-size:11px;font-weight:600;color:#D4AF7A;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:5px;font-family:'Poppins',sans-serif;}
.producto-body h3{font-size:17px;font-weight:600;margin-bottom:5px;color:#2C2C2C;font-family:'Poppins',sans-serif;}
.producto-desc{font-size:13px;color:#888;margin-bottom:14px;line-height:1.5;font-family:'Poppins',sans-serif;}
.producto-footer{display:flex;justify-content:space-between;align-items:center;}
.producto-precio{font-size:21px;font-weight:700;color:#D4AF7A;font-family:'Poppins',sans-serif;}
.producto-precio small{font-size:12px;color:#bbb;text-decoration:line-through;font-weight:400;margin-left:5px;}
.btn-agregar{width:38px;height:38px;border-radius:50%;background:#2C2C2C;border:none;color:#fff;font-size:16px;cursor:pointer;transition:.25s;display:flex;align-items:center;justify-content:center;text-decoration:none;}
.btn-agregar:hover{background:#D4AF7A;transform:scale(1.12);}

.sin-resultados{display:none;text-align:center;padding:80px 0;color:#888;font-family:'Poppins',sans-serif;font-size:18px;}
.sin-resultados i{font-size:50px;color:#E8E4DC;display:block;margin-bottom:16px;}
</style>
</head>
<body>

<?php require_once "header.php"; ?>

<div class="catalogo-hero">
    <h1>Catálogo <span>Urban Style</span></h1>
    <p>Accesorios y moda urbana premium — encuentra tu estilo</p>
</div>

<div class="filtros-bar">
    <button class="filtro-btn activo" data-cat="todos"><i class="fa-solid fa-border-all"></i> Todos</button>
    <button class="filtro-btn" data-cat="bolsos"><i class="fa-solid fa-bag-shopping"></i> Bolsos</button>
    <button class="filtro-btn" data-cat="carteras"><i class="fa-solid fa-wallet"></i> Carteras</button>
    <button class="filtro-btn" data-cat="relojes"><i class="fa-solid fa-clock"></i> Relojes</button>
    <button class="filtro-btn" data-cat="gorras"><i class="fa-solid fa-hat-cowboy"></i> Gorras</button>
    <button class="filtro-btn" data-cat="lentes"><i class="fa-solid fa-glasses"></i> Lentes</button>
    <button class="filtro-btn" data-cat="collares"><i class="fa-solid fa-gem"></i> Collares</button>
    <button class="filtro-btn" data-cat="cinturones"><i class="fa-solid fa-circle-dot"></i> Cinturones</button>
</div>

<section class="catalogo-section">

    <div class="sin-resultados" id="sinResultados">
        <i class="fa-solid fa-magnifying-glass"></i>
        No hay productos en esta categoría.
    </div>

    <!-- BOLSOS -->
    <div class="grupo-categoria" data-grupo="bolsos">
        <h2 class="categoria-titulo"><i class="fa-solid fa-bag-shopping"></i> Bolsos</h2>
        <div class="catalogo-grid">

            <div class="producto-card" data-cat="bolsos" data-nombre="Bolso Tote Premium" data-precio="89" data-img="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80">
                <span class="producto-badge nuevo">Nuevo</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&q=80" alt="Bolso Tote Premium">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Bolsos</p>
                    <h3>Bolso Tote Premium</h3>
                    <p class="producto-desc">Cuero genuino, interior forrado, asa doble y correa ajustable.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$89 <small>$120</small></span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="bolsos" data-nombre="Bolso Crossbody Urban" data-precio="55" data-img="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&q=80" alt="Bolso Crossbody Urban">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Bolsos</p>
                    <h3>Bolso Crossbody Urban</h3>
                    <p class="producto-desc">Diseño compacto con múltiples compartimentos y cierre magnético.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$55</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="bolsos" data-nombre="Mini Bag Gold Edition" data-precio="38" data-img="https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&q=80">
                <span class="producto-badge oferta">Oferta</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&q=80" alt="Mini Bag Gold Edition">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Bolsos</p>
                    <h3>Mini Bag Gold Edition</h3>
                    <p class="producto-desc">Bolso pequeño de noche con cadena dorada y cierre zip.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$38 <small>$60</small></span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="bolsos" data-nombre="Mochila Street Style" data-precio="65" data-img="https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=600&q=80" alt="Mochila Street Style">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Bolsos</p>
                    <h3>Mochila Street Style</h3>
                    <p class="producto-desc">Canvas resistente, bolsillo frontal y espalda acolchada.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$65</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- CARTERAS -->
    <div class="grupo-categoria" data-grupo="carteras">
        <h2 class="categoria-titulo"><i class="fa-solid fa-wallet"></i> Carteras</h2>
        <div class="catalogo-grid">

            <div class="producto-card" data-cat="carteras" data-nombre="Cartera Slim Leather" data-precio="45" data-img="https://images.unsplash.com/photo-1627123424574-724758594e93?w=600&q=80">
                <span class="producto-badge nuevo">Nuevo</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1627123424574-724758594e93?w=600&q=80" alt="Cartera Slim Leather">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Carteras</p>
                    <h3>Cartera Slim Leather</h3>
                    <p class="producto-desc">Cuero italiano, 8 tarjeteros, billetera y ventana para ID.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$45</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="carteras" data-nombre="Monedero Urban Chic" data-precio="28" data-img="https://images.unsplash.com/photo-1473186578172-c141e6798cf4?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://i.pinimg.com/736x/25/5e/88/255e88ed3421345405729142c72e04b4.jpg" alt="Monedero Urban Chic">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Carteras</p>
                    <h3>Monedero Urban Chic</h3>
                    <p class="producto-desc">Piel vegana con cierre zip, bolsillo exterior y grabado dorado.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$28</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="carteras" data-nombre="Cartera RFID Shield" data-precio="35" data-img="https://images.unsplash.com/photo-1612817288484-6f916006741a?w=600&q=80">
                <span class="producto-badge oferta">Oferta</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://i.pinimg.com/736x/cf/a4/b0/cfa4b0355506921bcb366c7cc7cafd5c.jpg" alt="Cartera RFID Shield">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Carteras</p>
                    <h3>Cartera RFID Shield</h3>
                    <p class="producto-desc">Protección RFID, minimalista, 6 tarjeteros y compartimento oculto.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$35 <small>$50</small></span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="carteras" data-nombre="Portadocumentos Elite" data-precio="52" data-img="https://images.unsplash.com/photo-1553531384-397c80973a0b?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://i.pinimg.com/1200x/35/e9/da/35e9da7858bdb1f9eefffc1b232cc9bb.jpg" alt="Portadocumentos Elite">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Carteras</p>
                    <h3>Portadocumentos Elite</h3>
                    <p class="producto-desc">Cuero genuino para pasaporte, tarjetas y billetes, cierre magnético.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$52</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- RELOJES -->
    <div class="grupo-categoria" data-grupo="relojes">
        <h2 class="categoria-titulo"><i class="fa-solid fa-clock"></i> Relojes</h2>
        <div class="catalogo-grid">

            <div class="producto-card" data-cat="relojes" data-nombre="Reloj Clásico Gold" data-precio="135" data-img="https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=600&q=80">
                <span class="producto-badge nuevo">Nuevo</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=600&q=80" alt="Reloj Clásico Gold">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Relojes</p>
                    <h3>Reloj Clásico Gold</h3>
                    <p class="producto-desc">Mecanismo japonés, caja de acero inoxidable, cristal mineral.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$135</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="relojes" data-nombre="Reloj Urban Black" data-precio="98" data-img="https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?w=600&q=80" alt="Reloj Urban Black">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Relojes</p>
                    <h3>Reloj Urban Black</h3>
                    <p class="producto-desc">Correa de cuero negro, esfera minimalista, resistente al agua.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$98</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="relojes" data-nombre="Cronógrafo Sport" data-precio="110" data-img="https://images.unsplash.com/photo-1547996160-81dfa63595aa?w=600&q=80">
                <span class="producto-badge oferta">Oferta</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1547996160-81dfa63595aa?w=600&q=80" alt="Cronógrafo Sport">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Relojes</p>
                    <h3>Cronógrafo Sport</h3>
                    <p class="producto-desc">Funciones cronómetro, fecha y bisel giratorio de acero.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$110 <small>$160</small></span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="relojes" data-nombre="Reloj Rose Gold Luxe" data-precio="175" data-img="https://images.unsplash.com/photo-1509048191080-d2984bad6ae5?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1509048191080-d2984bad6ae5?w=600&q=80" alt="Reloj Rose Gold Luxe">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Relojes</p>
                    <h3>Reloj Rose Gold Luxe</h3>
                    <p class="producto-desc">Caja y brazalete de acero rose gold, bisel con cristales.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$175</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- GORRAS -->
    <div class="grupo-categoria" data-grupo="gorras">
        <h2 class="categoria-titulo"><i class="fa-solid fa-hat-cowboy"></i> Gorras</h2>
        <div class="catalogo-grid">

            <div class="producto-card" data-cat="gorras" data-nombre="Gorra Snapback Urban" data-precio="22" data-img="https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=600&q=80">
                <span class="producto-badge nuevo">Nuevo</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=600&q=80" alt="Gorra Snapback Urban">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Gorras</p>
                    <h3>Gorra Snapback Urban</h3>
                    <p class="producto-desc">100% algodón, visera plana, cierre ajustable y logo bordado.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$22</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="gorras" data-nombre="Bucket Hat Retro" data-precio="18" data-img="https://images.unsplash.com/photo-1556306535-0f09a537f0a3?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1556306535-0f09a537f0a3?w=600&q=80" alt="Bucket Hat Retro">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Gorras</p>
                    <h3>Bucket Hat Retro</h3>
                    <p class="producto-desc">Estilo pescador, tela reversible en dos colores neutros.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$18</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="gorras" data-nombre="Dad Cap Clásica" data-precio="20" data-img="https://images.unsplash.com/photo-1514327605112-b887c0e61c0a?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1514327605112-b887c0e61c0a?w=600&q=80" alt="Dad Cap Clásica">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Gorras</p>
                    <h3>Dad Cap Clásica</h3>
                    <p class="producto-desc">Corte relajado, visera curva, disponible en negro, beige y verde.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$20</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="gorras" data-nombre="Gorra Trucker Mesh" data-precio="16" data-img="https://images.unsplash.com/photo-1534215754734-18e55d13e346?w=600&q=80">
                <span class="producto-badge oferta">Oferta</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1534215754734-18e55d13e346?w=600&q=80" alt="Gorra Trucker Mesh">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Gorras</p>
                    <h3>Gorra Trucker Mesh</h3>
                    <p class="producto-desc">Panel frontal rígido, malla trasera transpirable, logo metálico.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$16 <small>$25</small></span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- LENTES -->
    <div class="grupo-categoria" data-grupo="lentes">
        <h2 class="categoria-titulo"><i class="fa-solid fa-glasses"></i> Lentes</h2>
        <div class="catalogo-grid">

            <div class="producto-card" data-cat="lentes" data-nombre="Lentes Aviator Gold" data-precio="42" data-img="https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=600&q=80">
                <span class="producto-badge nuevo">Nuevo</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=600&q=80" alt="Lentes Aviator Gold">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Lentes</p>
                    <h3>Lentes Aviator Gold</h3>
                    <p class="producto-desc">Marco metálico dorado, lentes UV400, estilo clásico atemporal.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$42</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="lentes" data-nombre="Lentes Cat Eye" data-precio="36" data-img="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&q=80" alt="Lentes Cat Eye">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Lentes</p>
                    <h3>Lentes Cat Eye</h3>
                    <p class="producto-desc">Montura acetato negra, forma cat-eye, lentes polarizados.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$36</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="lentes" data-nombre="Lentes Retro Round" data-precio="28" data-img="https://images.unsplash.com/photo-1577803645773-f96470509666?w=600&q=80">
                <span class="producto-badge oferta">Oferta</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1577803645773-f96470509666?w=600&q=80" alt="Lentes Retro Round">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Lentes</p>
                    <h3>Lentes Retro Round</h3>
                    <p class="producto-desc">Forma redonda, marco delgado, lentes espejados de colores.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$28 <small>$45</small></span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="lentes" data-nombre="Lentes Wayfarer Premium" data-precio="48" data-img="https://images.unsplash.com/photo-1508296695146-257a814070b4?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1508296695146-257a814070b4?w=600&q=80" alt="Lentes Wayfarer Premium">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Lentes</p>
                    <h3>Lentes Wayfarer Premium</h3>
                    <p class="producto-desc">Montura gruesa, lentes degradados, protección UV 400.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$48</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- COLLARES -->
    <div class="grupo-categoria" data-grupo="collares">
        <h2 class="categoria-titulo"><i class="fa-solid fa-gem"></i> Collares &amp; Joyería</h2>
        <div class="catalogo-grid">

            <div class="producto-card" data-cat="collares" data-nombre="Collar Urbano Gold" data-precio="25" data-img="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&q=80" alt="Collar Urbano Gold">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Collares</p>
                    <h3>Collar Urbano Gold</h3>
                    <p class="producto-desc">Cadena chapada en oro 18k, colgante geométrico, cierre de langosta.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$25</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="collares" data-nombre="Gargantilla Luxe" data-precio="48" data-img="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=600&q=80">
                <span class="producto-badge nuevo">Nuevo</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://i.pinimg.com/736x/58/91/42/589142e73329626b5ddc5862122fdcea.jpg" alt="Gargantilla Luxe">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Collares</p>
                    <h3>Gargantilla Luxe</h3>
                    <p class="producto-desc">Plata 925, piedras de circón, ajustable 38–45 cm.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$48</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="collares" data-nombre="Aretes Luxury Drop" data-precio="18" data-img="https://images.unsplash.com/photo-1630019852942-f89202989a59?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://images.unsplash.com/photo-1630019852942-f89202989a59?w=600&q=80" alt="Aretes Luxury Drop">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Collares</p>
                    <h3>Aretes Luxury Drop</h3>
                    <p class="producto-desc">Aretes colgantes con perla cultivada y base de plata.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$18</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="collares" data-nombre="Pulsera Urban Stack" data-precio="30" data-img="https://images.unsplash.com/photo-1611652022419-a9419f74343d?w=600&q=80">
                <span class="producto-badge oferta">Oferta</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://i.pinimg.com/736x/7e/3d/b7/7e3db73f46788ef33a81892d73be9cd2.jpg" alt="Pulsera Urban Stack">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Collares</p>
                    <h3>Pulsera Urban Stack</h3>
                    <p class="producto-desc">Set de 3 pulseras: cuero, cadena y cuentas de ónix negro.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$30 <small>$45</small></span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- CINTURONES -->
    <div class="grupo-categoria" data-grupo="cinturones">
        <h2 class="categoria-titulo"><i class="fa-solid fa-circle-dot"></i> Cinturones</h2>
        <div class="catalogo-grid">

            <div class="producto-card" data-cat="cinturones" data-nombre="Cinturón Cuero Clásico" data-precio="32" data-img="https://images.unsplash.com/photo-1624223649976-b3b7f50a8a21?w=600&q=80">
                <span class="producto-badge nuevo">Nuevo</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://i.pinimg.com/1200x/12/b6/36/12b636d3de9a876c2f967ffcdbd79194.jpg" alt="Cinturón Cuero Clásico">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Cinturones</p>
                    <h3>Cinturón Cuero Clásico</h3>
                    <p class="producto-desc">Cuero genuino, hebilla plateada, 3 cm de ancho, tallas S–XL.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$32</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="cinturones" data-nombre="Cinturón Reversible Urban" data-precio="40" data-img="https://images.unsplash.com/photo-1544816155-12df9643f363?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://i.pinimg.com/736x/8d/c0/b7/8dc0b712f7aa4e8a283eb9f371d82cb2.jpg" alt="Cinturón Reversible Urban">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Cinturones</p>
                    <h3>Cinturón Reversible Urban</h3>
                    <p class="producto-desc">Dos colores en uno, hebilla giratoria de acero, piel italiana.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$40</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="cinturones" data-nombre="Cinturón Elástico Sport" data-precio="22" data-img="https://images.unsplash.com/photo-1603400521630-9f2de124b33b?w=600&q=80">
                <span class="producto-badge oferta">Oferta</span>
                <div class="img-wrap">
                    <img class="producto-img" src="https://i.pinimg.com/1200x/5c/81/e7/5c81e72ef3d50468dc37939ef7b5ecb0.jpg" alt="Cinturón Elástico Sport">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Cinturones</p>
                    <h3>Cinturón Elástico Sport</h3>
                    <p class="producto-desc">Elástico trenzado, hebilla automática de zinc, estilo casual.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$22 <small>$35</small></span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <div class="producto-card" data-cat="cinturones" data-nombre="Cinturón Gold Buckle" data-precio="38" data-img="https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=600&q=80">
                <div class="img-wrap">
                    <img class="producto-img" src="https://i.pinimg.com/736x/61/9d/eb/619deb075376390efe7c9a2086f34499.jpg" alt="Cinturón Gold Buckle">
                </div>
                <div class="producto-body">
                    <p class="producto-categoria">Cinturones</p>
                    <h3>Cinturón Gold Buckle</h3>
                    <p class="producto-desc">Piel vegana, hebilla dorada de diseño, ancho 4 cm, unisex.</p>
                    <div class="producto-footer">
                        <span class="producto-precio">$38</span>
                        <button class="btn-agregar" onclick="agregarCarrito(this)"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>

<script>
const filtros = document.querySelectorAll(".filtro-btn");
const grupos  = document.querySelectorAll(".grupo-categoria");
const sinRes  = document.getElementById("sinResultados");

filtros.forEach(btn => {
    btn.addEventListener("click", () => {
        filtros.forEach(b => b.classList.remove("activo"));
        btn.classList.add("activo");

        const cat = btn.dataset.cat;
        let visible = 0;

        grupos.forEach(g => {
            if (cat === "todos" || g.dataset.grupo === cat) {
                g.style.display = "block";
                visible++;
            } else {
                g.style.display = "none";
            }
        });

        sinRes.style.display = visible === 0 ? "block" : "none";
    });
});
</script>


<div id="cartToast" style="
    position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(20px);
    background:#2C2C2C;color:#fff;padding:12px 24px;border-radius:50px;
    font-family:'Poppins',sans-serif;font-size:14px;font-weight:500;
    opacity:0;pointer-events:none;transition:all .3s;z-index:99999;
    display:flex;align-items:center;gap:10px;white-space:nowrap;">
    <i class="fa-solid fa-circle-check" style="color:#D4AF7A;"></i>
    <span id="cartToastMsg">Producto agregado</span>
    <a id="cartToastLink" href="carrito.php" style="color:#D4AF7A;text-decoration:none;font-weight:600;margin-left:6px;">Ver carrito →</a>
</div>

<script>
function agregarCarrito(btn) {
    const card   = btn.closest('.producto-card');
    const nombre = card.dataset.nombre;
    const precio = parseFloat(card.dataset.precio);
    const img    = card.dataset.img;
    let carrito  = JSON.parse(localStorage.getItem('urbanstyle_cart') || '[]');
    carrito.push({ nombre, precio, img, detalle: '' });
    localStorage.setItem('urbanstyle_cart', JSON.stringify(carrito));
    btn.style.background = '#D4AF7A';
    btn.innerHTML = '<i class="fa-solid fa-check"></i>';
    setTimeout(() => {
        btn.style.background = '';
        btn.innerHTML = '<i class="fa-solid fa-plus"></i>';
    }, 1200);
    const toast = document.getElementById('cartToast');
    document.getElementById('cartToastMsg').textContent = '"'+ nombre +'" agregado al carrito';
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    toast.style.pointerEvents = 'auto';
    clearTimeout(toast._t);
    toast._t = setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(20px)';
        toast.style.pointerEvents = 'none';
    }, 3000);
}
</script>
</body>
</html>