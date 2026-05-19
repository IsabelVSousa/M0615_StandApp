<?php
session_start();

require_once "../Controller/EventController.php";
$eventController = new EventController();
$eventos = $eventController->readAllPublic();
// Decidimos el destino y el texto del botón
if (isset($_SESSION['IDPersona'])) {
    if ($_SESSION['tipo'] == 'admin') {
        $destino     = "Event.html";
        $destinoorg  = "Event.html";
        $destinoperf = "perfil_admin.php";
    } else {
        $destino     = "Event.html";
        $destinoorg  = "LogIn.php";
        $destinoperf = "perfil.php";
    }
} else {
    $destino     = "LogIn.php";
    $destinoorg  = "LogIn.php";
    $destinoperf = "LogIn.php";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Home.css">
</head>

<script>
    let posicion = 0;
    const carrusel = document.getElementById('carrusel');
    const dots = document.querySelectorAll('.dot');
    const totalItems = carrusel ? carrusel.children.length : 0;

    // Cuántos items caben según el ancho de pantalla
    function itemsVisibles() {
        if (window.innerWidth <= 742) return 1;
        if (window.innerWidth <= 992) return 2;
        return 3;
    }

    function actualizarCarrusel() {
        const visibles = itemsVisibles();
        const maxPos = Math.max(0, totalItems - visibles);
        posicion = Math.min(posicion, maxPos);
        const porcentaje = (100 / visibles) * posicion;
        carrusel.style.transform = `translateX(-${porcentaje}%)`;

        // Actualizar dots
        dots.forEach((d, i) => {
            d.classList.toggle('activo', i === posicion);
        });
    }

    function moverCarrusel(direccion) {
        const visibles = itemsVisibles();
        const maxPos = Math.max(0, totalItems - visibles);
        posicion = Math.max(0, Math.min(posicion + direccion, maxPos));
        actualizarCarrusel();
    }

    function irASlide(index) {
        posicion = index;
        actualizarCarrusel();
    }

    window.addEventListener('resize', actualizarCarrusel);
</script>

<body>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Diseño Eventos</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    </head>

    <!-- BARRA DE MENU -->

    <body>

        <div class="top-bar">
            <nav class="main-nav">
                <div class="nav-left">
                    <div class="logo-circle">
                        <img src="img/logotipo2_StandApp_Dunia.png" alt="imagen del logo">
                    </div>
                    <div class="logo-text-placeholder">
                        <span>Stand-App</span>
                    </div>
                </div>
                <input type="checkbox" id="menu-toggle" class="menu-checkbox">
                <label for="menu-toggle" class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </label>
                <div class="nav-links">
                    <a href="Event.html">Descuentos</a>
                    <a href="foro.html">Foro</a>
                    <a href="<?php echo $destinoorg ?>">Organizadores</a>
                    <a href="<?php echo $destinoperf ?>">Perfil</a>
                </div>
                <div class="nav-buttons">
                    <a href="LogIn.php" class="btn btn-outline">Iniciar Sesión</a>
                    <a href="register.php" class="btn btn-white">Registrarse</a>
                </div>
            </nav>
        </div>

        <!-- BARRA DE BUSQUEDA -->

        <div class="search-container">
            <form action="buscar.php" method="GET" class="search-form">
                <div class="date-input-wrapper" id="date-wrapper">
                    <input type="checkbox" id="toggle-calendar" hidden>

                    <label for="toggle-calendar" class="calendar-trigger">
                        <i class="far fa-calendar-alt fa-2x"></i>
                        <span class="arrow-down">▼</span>
                    </label>

                    <!-- <input type="checkbox" id="toggle-calendar" hidden>

<label for="toggle-calendar" class="calendar-trigger">
    <i class="far fa-calendar-alt"></i>
    <span class="arrow-down">▼</span>
</label> -->

                    <div class="datepicker">
                        <div class="datepicker-top">
                            <div class="btn-group">
                                <button type="button" class="tag">Hoy</button>
                                <button type="button" class="tag">Mañana</button>
                                <button type="button" class="tag">En dos dias</button>
                            </div>
                            <div class="month-selector">
                                <button type="button" class="arrow"><i class="material-icons">Izq</i></button>
                                <span class="month-name">Enero 2026</span>
                                <button type="button" class="arrow"><i class="material-icons">Der</i></button>
                            </div>
                        </div>
                        <div class="datepicker-calendar">
                            <span class="day">Lu</span>
                            <span class="day">Ma</span>
                            <span class="day">Mi</span>
                            <span class="day">Ju</span>
                            <span class="day">Vi</span>
                            <span class="day">Sa</span>
                            <span class="day">Do</span>
                            <button type="button" class="date faded">30</button>
                            <button type="button" class="date">1</button>
                            <button type="button" class="date">2</button>
                            <button type="button" class="date">3</button>
                            <button type="button" class="date">4</button>
                            <button type="button" class="date">5</button>
                            <button type="button" class="date">6</button>
                            <button type="button" class="date">7</button>
                            <button type="button" class="date">8</button>
                            <button type="button" class="date current-day">9</button>
                            <button type="button" class="date">10</button>
                            <button type="button" class="date">11</button>
                            <button type="button" class="date">12</button>
                            <button type="button" class="date">13</button>
                            <button type="button" class="date">14</button>
                            <button type="button" class="date">15</button>
                            <button type="button" class="date">16</button>
                            <button type="button" class="date">17</button>
                            <button type="button" class="date">18</button>
                            <button type="button" class="date">19</button>
                            <button type="button" class="date">20</button>
                            <button type="button" class="date">21</button>
                            <button type="button" class="date">22</button>
                            <button type="button" class="date">23</button>
                            <button type="button" class="date">24</button>
                            <button type="button" class="date">25</button>
                            <button type="button" class="date">26</button>
                            <button type="button" class="date">27</button>
                            <button type="button" class="date">28</button>
                            <button type="button" class="date">29</button>
                            <button type="button" class="date">30</button>
                            <button type="button" class="date">31</button>
                            <button type="button" class="date faded">1</button>
                            <button type="button" class="date faded">2</button>
                            <button type="button" class="date faded">3</button>
                        </div>
                    </div>
                    <!-- fin calendario -->

                    <input type="date" name="fecha" placeholder="00/00/00"
                        style="position:absolute; opacity:0; width:100%; height:100%; cursor:pointer;">
                </div>
                <div class="search-input-wrapper">
                    <input type="text" name="busqueda" placeholder="Buscar...">
                    <button type="submit" style="background:none; border:none;">
                        <i class="fas fa-search search-icon"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- HERO CARD -->

        <main class="hero-card">
            <div class="card-content">
                <video autoplay muted loop playsinline>
                    <source src="../View/img/uhd_25fps.mp4" type="video/mp4" alt="Video evento hero card">
                    Tu navegador no soporta la reproducción de videos.
                </video>
                <div class="texto-encima">
                    <h1>Risas en Crudo</h1>
                    <p>
                        Show de stand up sin filtros con comediantes probando material nuevo.
                        Humor fresco, improvisación y muchas risas en un ambiente cercano.
                    </p>
                    <div>
                        <a href=<?php echo $destino ?> class="btn-book">Book Now</a>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="card-info-right">
                    <span>26/05/26</span>
                    <span>Carrer de Provença 88, Barcelona</span>
                    <div class="location-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>
            </div>

            <!-- EVENTOS DESTACADOS -->

            <div class="outstand-card">
                <div class="events-content">
                    <h1>Comediantes destacados</h1>
                    <a style="color: #ffffff;" href=<?php echo $destino ?> target="_blank">Descubre más</a>
                </div>
                <div class="collection-list">
                    <div class="events-outstand">
                        <div class="card-content">
                            <!-- poner el video de contenido destacado -->
                            <video autoplay muted loop playsinline>
                                <source src="../View/img/outstand2.mp4" type="video/mp4" alt="Video primer evento">
                                Tu navegador no soporta la reproducción de videos.
                            </video>
                        </div>
                        <div class="card-footer">
                            <a href=<?php echo $destino ?> class="card-info-right">
                                <h3>Pablo Herrera</h3>
                                <div class="arrow-icon">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="events-outstand">
                        <div class="card-content">
                            <!-- poner el video de contenido destacado -->
                            <video autoplay muted loop playsinline>
                                <source src="../View/img/outstanda.mp4" type="video/mp4" alt="Video segundo evento">
                                Tu navegador no soporta la reproducción de videos.
                            </video>
                        </div>
                        <div class="card-footer">
                            <a href=<?php echo $destino ?> class="card-info-right">
                                <h3>Raúl Mendoza</h3>
                                <div class="arrow-icon">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="events-outstand-b">
                        <div class="card-content">
                            <!-- poner el video de contenido destacado -->
                            <video autoplay muted loop playsinline>
                                <source src="../View/img/outstandb.mp4" type="video/mp4" alt="Video tercer evento">
                                Tu navegador no soporta la reproducción de videos.
                            </video>
                        </div>
                        <div class="card-footer">
                            <a href=<?php echo $destino ?> class="card-info-right">
                                <h3>Víctor Prieto</h3>
                                <div class="arrow-icon">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <section class="next-card">
                <div class="events-content">
                    <h1>Próximos Eventos</h1>
                    <?php if (isset($_SESSION['IDPersona'])): ?>
                        <a href="eventos_todos.php">Descubre más</a>
                    <?php else: ?>
                        <a href="LogIn.php">Descubre más</a>
                    <?php endif; ?>
                </div>

                <?php
                // Si no está logueado o es standard sin sesión, mostrar solo 3
                $eventosCarrusel = isset($_SESSION['IDPersona'])
                    ? $eventos  // todos
                    : array_slice($eventos, 0, 3); // solo los 3 más próximos
                ?>

                <?php if (empty($eventosCarrusel)): ?>
                    <p style="color:#b5b5b5; text-align:center; padding:2rem 0;">
                        No hay eventos próximos disponibles.
                    </p>
                <?php else: ?>

                    <!-- CARRUSEL -->
                    <div class="carrusel-wrapper">

                        <!-- Flecha izquierda -->
                        <button class="carrusel-btn carrusel-prev" onclick="moverCarrusel(-1)">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        
                        <div class="carrusel-wrapper">
                            <button class="carrusel-btn carrusel-prev" onclick="moverCarrusel(-1)">
                                <i class="fas fa-chevron-left"></i>
                            </button>

                            <!-- Contenedor de tarjetas -->
                            <div class="carrusel-contenedor" id="carrusel">
                                <?php foreach ($eventosCarrusel as $ev): ?>
                                    <div class="outline next-event carrusel-item">

                                        <!-- Imagen del evento -->
                                        <div>
                                            <?php if (!empty($ev['imagen_evento'])): ?>
                                                <img class="next-event"
                                                    src="<?= htmlspecialchars($ev['imagen_evento']) ?>"
                                                    alt="<?= htmlspecialchars($ev['descripcion']) ?>">
                                            <?php else: ?>
                                                <div style="
                                    width:100%; height:180px;
                                    background:#363636;
                                    border-radius:8px;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    color:#b5b5b5;
                                    font-size:2rem;">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Info del evento -->
                                        <div>
                                            <h3 class="next-event">
                                                <?= htmlspecialchars($ev['descripcion']) ?>
                                            </h3>
                                            <div>
                                                <div class="card-info-next">
                                                    <div class="location-icon">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <span><?= htmlspecialchars($ev['comediante']) ?></span>
                                                </div>
                                                <div class="card-info-next">
                                                    <div class="location-icon">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                    <span><?= htmlspecialchars($ev['ubicacion'] ?? 'Por confirmar') ?></span>
                                                </div>
                                                <div class="card-info-next">
                                                    <div class="location-icon">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </div>
                                                    <span><?= date('d/m/Y H:i', strtotime($ev['fechahora'])) ?></span>
                                                </div>
                                            </div>
                                            <div class="button-next">
                                                <?php if (isset($_SESSION['IDPersona'])): ?>
                                                    <a href="Event.php?id=<?= $ev['IDEvento'] ?>" class="btn-book">
                                                        Ver evento
                                                    </a>
                                                <?php else: ?>
                                                    <a href="LogIn.php" class="btn-book">
                                                        Ver evento
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Flecha derecha -->
                        <button class="carrusel-btn carrusel-next" onclick="moverCarrusel(1)">
                            <i class="fas fa-chevron-right"></i>
                        </button>

                    </div>

                    <!-- Indicadores de posición -->
                    <div class="carrusel-dots" id="carrusel-dots">
                        <?php foreach ($eventosCarrusel as $i => $ev): ?>
                            <span class="dot <?= $i === 0 ? 'activo' : '' ?>"
                                onclick="irASlide(<?= $i ?>)"></span>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>

            </section>

            <!-- COMENTARIOS -->

            <section class="next-card">
                <div class="events-content">
                    <h1>Comentarios</h1>
                    <a href=<?php echo $destino ?> target="_blank">Descubre más</a>
                </div>
                <div class="collection-list">
                    <a href=<?php echo $destino ?> target="_blank">
                        <div class="card-footer outline comentary-card">
                            <div class="card-info-right">
                                <img class="logo-circle" src="../View/img/profile1.jpg" alt="Imagen de reseña 1">
                            </div>
                            <div class="comentary-content">
                                <h3>Risas & Birras</h3>
                                <p>
                                    El plan ideal para una noche con amigos. Buen humor, buen ambiente y además puedes
                                    tomarte algo mientras ves el show. Se pasa volando y te quedas con ganas de más.
                                </p>
                            </div>
                            <div class="arrow-icon button-next">
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                    <a href=<?php echo $destino ?> target="_blank">
                        <div class="card-footer outline comentary-card">
                            <div class="card-info-right">
                                <img class="logo-circle" src="../View/img/profile2.jpg" alt="Imagen de reseña 2">
                            </div>
                            <div class="comentary-content">
                                <h3>Micro Abierto Sin Filtro</h3>
                                <p>
                                    Me gustó mucho el formato, se siente muy auténtico porque los cómicos prueban
                                    material nuevo. Algunos chistes funcionan mejor que otros, pero eso es parte de la
                                    gracia. Muy recomendable si te gusta algo diferente.
                                </p>
                            </div>
                            <div class="arrow-icon button-next">
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                    <a href=<?php echo $destino ?> target="_blank">
                        <div class="card-footer outline comentary-card">
                            <div class="card-info-right">
                                <img class="logo-circle" src="../View/img/profile3.jpg" alt="Imagen de reseña 3">
                            </div>
                            <div class="comentary-content">
                                <h3>Noche de Risas BCN</h3>
                                <p>
                                    Fui con unos amigos sin saber qué esperar y salimos encantados. Los comediantes
                                    fueron muy cercanos y hubo momentos de improvisación que hicieron el show aún más
                                    divertido. Sin duda repetiré.
                                </p>
                            </div>
                            <div class="arrow-icon button-next">
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                    <a href=<?php echo $destino ?> target="_blank">
                        <div class="card-footer outline comentary-card">
                            <div class="card-info-right">
                                <img class="logo-circle" src="../View/img/profile4.jpg" alt="Imagen de reseña 4">
                            </div>
                            <div class="comentary-content">
                                <h3>Comedy Night Underground</h3>
                                <p>
                                    Un ambiente súper cercano, casi íntimo. Descubrí a varios comediantes que no conocía
                                    y me sorprendieron muchísimo. Es perfecto si buscas algo alternativo fuera de lo
                                    típico.
                                </p>
                            </div>
                            <div class="arrow-icon button-next">
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
                </div>
            </section>
        </main>

        <!-- FOOTER -->
        <footer class="footer-section">
            <div class="footer-wrap">
                <div class="footer-left">

                    <div>
                        <div class="separator-line"></div>
                        <div class="text-size-regular footer-text"> Isabel Sousa / Mauricio Patiño
                        </div>
                        <div class="separator-line"></div>
                        <div class="text-size-regular footer-text">Stucom Proyecto M0615 UX/UI</div>
                        <div class="separator-line"></div>
                        <div class="text-size-regular footer-text">© 2026 Proyecto Stand App
                        </div>
                    </div>
                </div>
                <div class="footer-right">
                    <ul role="list" class="footer-menu-list">
                        <li class="list-item">
                            <a href=" " class="footer-list-link w-inline-block">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <div class="nav-links">Newsletter</div>
                            </a>
                        </li>
                        <li class="list-item">
                            <a href=" " class="footer-list-link w-inline-block">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <div class="nav-links">About Us</div>
                            </a>
                        </li>
                        <li class="list-item">
                            <a href=" " class="footer-list-link w-inline-block">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <div class="nav-links">Términos de venta</div>
                            </a>
                        </li>
                        <li class="list-item">
                            <a href=" " class="footer-list-link w-inline-block">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <div class="nav-links">Términos de uso</div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </body>

    </html>