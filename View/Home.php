<?php
session_start();

require_once "../Controller/EventController.php";
$eventController = new EventController();
$eventos = $eventController->readAllPublic();

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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stand-App</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="Home.css">
    <script src="./JS/carrusel.js" defer></script>
    <script src="./JS/cookies.js" defer></script>
    <!-- <link rel="stylesheet" href="cookies.css"> -->
</head>

<body>

    <!-- BARRA DE MENÚ -->
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

    <!-- BARRA DE BÚSQUEDA -->
    <div class="search-container">
        <form action="eventos_todos.php" method="GET" class="search-form">

            <div class="date-input-wrapper" id="date-wrapper">
                <label for="fecha-visible" class="calendar-trigger">
                    <i class="far fa-calendar-alt fa-2x"></i>
                    <span id="fecha-texto">Seleccionar fecha</span>
                </label>
                <input type="date"
                    id="fecha-visible"
                    name="fecha"
                    onchange="mostrarFecha(this)"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;opacity:0;cursor:pointer;z-index:10;">
            </div>

            <div class="search-input-wrapper">
                <input type="text" name="busqueda" placeholder="Buscar comediante o evento...">
                <button type="submit" style="background:none;border:none;">
                    <i class="fas fa-search search-icon"></i>
                </button>
            </div>

        </form>
    </div>

    <!-- HERO CARD -->
    <main class="hero-card">
        <div class="card-content">
            <video autoplay muted loop playsinline>
                <source src="../View/img/uhd_25fps.mp4" type="video/mp4">
                Tu navegador no soporta la reproducción de videos.
            </video>
            <div class="texto-encima">
                <h1>Risas en Crudo</h1>
                <p>
                    Show de stand up sin filtros con comediantes probando material nuevo.
                    Humor fresco, improvisación y muchas risas en un ambiente cercano.
                </p>
                <div>
                    <a href="<?php echo $destino ?>" class="btn-book">Book Now</a>
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
                <a style="color:#ffffff;" href="<?php echo $destino ?>" target="_blank">Descubre más</a>
            </div>
            <div class="collection-list">
                <div class="events-outstand">
                    <div class="card-content">
                        <video autoplay muted loop playsinline>
                            <source src="../View/img/outstand2.mp4" type="video/mp4">
                            Tu navegador no soporta la reproducción de videos.
                        </video>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo $destino ?>" class="card-info-right">
                            <h3>Pablo Herrera</h3>
                            <div class="arrow-icon"><i class="fa-solid fa-arrow-right"></i></div>
                        </a>
                    </div>
                </div>
                <div class="events-outstand">
                    <div class="card-content">
                        <video autoplay muted loop playsinline>
                            <source src="../View/img/outstanda.mp4" type="video/mp4">
                            Tu navegador no soporta la reproducción de videos.
                        </video>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo $destino ?>" class="card-info-right">
                            <h3>Raúl Mendoza</h3>
                            <div class="arrow-icon"><i class="fa-solid fa-arrow-right"></i></div>
                        </a>
                    </div>
                </div>
                <div class="events-outstand-b">
                    <div class="card-content">
                        <video autoplay muted loop playsinline>
                            <source src="../View/img/outstandb.mp4" type="video/mp4">
                            Tu navegador no soporta la reproducción de videos.
                        </video>
                    </div>
                    <div class="card-footer">
                        <a href="<?php echo $destino ?>" class="card-info-right">
                            <h3>Víctor Prieto</h3>
                            <div class="arrow-icon"><i class="fa-solid fa-arrow-right"></i></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRÓXIMOS EVENTOS -->
        <section class="next-card">
            <div class="events-content">
                <h1>Próximos Eventos</h1>
                <?php if (isset($_SESSION['IDPersona'])): ?>
                    <a href="event_all.php">Descubre más</a>
                <?php else: ?>
                    <a href="LogIn.php">Descubre más</a>
                <?php endif; ?>
            </div>

            <?php
            $eventosCarrusel = isset($_SESSION['IDPersona'])
                ? $eventos
                : array_slice($eventos, 0, 3);
            ?>

            <?php if (empty($eventosCarrusel)): ?>
                <p style="color:#b5b5b5;text-align:center;padding:2rem 0;">
                    No hay eventos próximos disponibles.
                </p>
            <?php else: ?>

                <div class="carrusel-wrapper">
                    <button class="carrusel-btn carrusel-prev" onclick="moverCarrusel(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <div class="carrusel-overflow">
                        <div class="carrusel-contenedor" id="carrusel">
                            <?php foreach ($eventosCarrusel as $ev): ?>
                                <div class="outline next-event carrusel-item">
                                    <div>
                                        <?php if (!empty($ev['imagen_evento'])): ?>
                                            <img class="next-event"
                                                src="<?= htmlspecialchars($ev['imagen_evento']) ?>"
                                                alt="<?= htmlspecialchars($ev['descripcion']) ?>">
                                        <?php else: ?>
                                            <div style="width:100%;height:180px;background:#363636;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#b5b5b5;font-size:2rem;">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h3 class="next-event"><?= htmlspecialchars($ev['descripcion']) ?></h3>
                                        <div>
                                            <div class="card-info-next">
                                                <div class="location-icon"><i class="fas fa-user"></i></div>
                                                <span><?= htmlspecialchars($ev['comediante']) ?></span>
                                            </div>
                                            <div class="card-info-next">
                                                <div class="location-icon"><i class="fas fa-map-marker-alt"></i></div>
                                                <span><?= htmlspecialchars($ev['ubicacion'] ?? 'Por confirmar') ?></span>
                                            </div>
                                            <div class="card-info-next">
                                                <div class="location-icon"><i class="far fa-calendar-alt"></i></div>
                                                <span><?= date('d/m/Y H:i', strtotime($ev['fechahora'])) ?></span>
                                            </div>
                                        </div>
                                        <div class="button-next">
                                            <?php if (isset($_SESSION['IDPersona'])): ?>
                                                <a href="Event.php?id=<?= $ev['IDEvento'] ?>" class="btn-book">Ver evento</a>
                                            <?php else: ?>
                                                <a href="LogIn.php" class="btn-book">Ver evento</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button class="carrusel-btn carrusel-next" onclick="moverCarrusel(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <div class="carrusel-dots" id="carrusel-dots">

                </div>

            <?php endif; ?>
        </section>

        <!-- COMENTARIOS -->
        <section class="next-card" id="seccion-comentarios">
            <div class="events-content">
                <h1>Comentarios</h1>
                <a href="<?php echo $destino ?>" target="_blank">Descubre más</a>
            </div>
            <div class="collection-list">
                <a href="<?php echo $destino ?>" target="_blank">
                    <div class="card-footer outline comentary-card">
                        <div class="card-info-right">
                            <img class="logo-circle" src="../View/img/profile1.jpg" alt="Imagen de reseña 1">
                        </div>
                        <div class="comentary-content">
                            <h3>Risas & Birras</h3>
                            <p>El plan ideal para una noche con amigos. Buen humor, buen ambiente y además puedes
                                tomarte algo mientras ves el show. Se pasa volando y te quedas con ganas de más.</p>
                        </div>
                        <div class="arrow-icon button-next"><i class="fa-solid fa-arrow-right"></i></div>
                    </div>
                </a>
                <a href="<?php echo $destino ?>" target="_blank">
                    <div class="card-footer outline comentary-card">
                        <div class="card-info-right">
                            <img class="logo-circle" src="../View/img/profile2.jpg" alt="Imagen de reseña 2">
                        </div>
                        <div class="comentary-content">
                            <h3>Micro Abierto Sin Filtro</h3>
                            <p>Me gustó mucho el formato, se siente muy auténtico porque los cómicos prueban
                                material nuevo. Algunos chistes funcionan mejor que otros, pero eso es parte de la
                                gracia. Muy recomendable si te gusta algo diferente.</p>
                        </div>
                        <div class="arrow-icon button-next"><i class="fa-solid fa-arrow-right"></i></div>
                    </div>
                </a>
                <a href="<?php echo $destino ?>" target="_blank">
                    <div class="card-footer outline comentary-card">
                        <div class="card-info-right">
                            <img class="logo-circle" src="../View/img/profile3.jpg" alt="Imagen de reseña 3">
                        </div>
                        <div class="comentary-content">
                            <h3>Noche de Risas BCN</h3>
                            <p>Fui con unos amigos sin saber qué esperar y salimos encantados. Los comediantes
                                fueron muy cercanos y hubo momentos de improvisación que hicieron el show aún más
                                divertido. Sin duda repetiré.</p>
                        </div>
                        <div class="arrow-icon button-next"><i class="fa-solid fa-arrow-right"></i></div>
                    </div>
                </a>
                <a href="<?php echo $destino ?>" target="_blank">
                    <div class="card-footer outline comentary-card">
                        <div class="card-info-right">
                            <img class="logo-circle" src="../View/img/profile4.jpg" alt="Imagen de reseña 4">
                        </div>
                        <div class="comentary-content">
                            <h3>Comedy Night Underground</h3>
                            <p>Un ambiente súper cercano, casi íntimo. Descubrí a varios comediantes que no conocía
                                y me sorprendieron muchísimo. Es perfecto si buscas algo alternativo fuera de lo
                                típico.</p>
                        </div>
                        <div class="arrow-icon button-next"><i class="fa-solid fa-arrow-right"></i></div>
                    </div>
                </a>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="footer-section">
        <div class="footer-wrap">
            <div class="footer-left">
                <div>
                    <div class="separator-line"></div>
                    <div class="text-size-regular footer-text">Isabel Sousa / Mauricio Patiño</div>
                    <div class="separator-line"></div>
                    <div class="text-size-regular footer-text">Stucom Proyecto M0615 UX/UI</div>
                    <div class="separator-line"></div>
                    <div class="text-size-regular footer-text">© 2026 Proyecto Stand App</div>
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