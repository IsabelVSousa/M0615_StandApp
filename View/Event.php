<?php
session_start();

// Si no está logueado, al login
if (!isset($_SESSION['IDPersona'])) {
    header("Location: LogIn.php");
    exit;
}

// Cargar datos del evento
require_once "../Controller/EventController.php";
$eventController = new EventController();
$idEvento = $_GET['id'] ?? '';
$evento   = $eventController->readOne($idEvento);

if (!$evento) {
    header("Location: Home.php?error=evento_no_encontrado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($evento['descripcion']) ?> — StandApp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Event.css">
</head>
<body>

<!-- NAVBAR -->
<div class="barra-superior">
    <nav class="navegacion-principal">
        <div class="navegacion-izquierda">
            <div class="logo-circulo">
                <img src="img/logotipo2_StandApp_Dunia.png" alt="Logo StandApp">
            </div>
            <div class="logo-texto-placeholder">
                <span>Stand-App</span>
            </div>
        </div>

        <input type="checkbox" id="menu-toggle" class="menu-checkbox">
        <label for="menu-toggle" class="menu-toggle">
            <i class="fas fa-bars"></i>
        </label>

        <div class="enlaces-navegacion">
            <a href="Home.php">Home</a>
            <?php if ($_SESSION['tipo'] === 'admin'): ?>
                <a href="perfil_admin.php">Mi perfil</a>
            <?php else: ?>
                <a href="perfil.php">Mi perfil</a>
            <?php endif; ?>
        </div>

        <div class="botones-navegacion">
            <form action="../Controller/UserController.php" method="post">
                <button type="submit" name="logout" class="boton boton-contorno">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </nav>
</div>

<!-- DETALLE DEL EVENTO -->
<section class="contenedor-evento">
    <div class="tarjeta-evento">

        <!-- Imagen del evento -->
        <div class="cuadro-imagen-evento">
            <?php if (!empty($evento['imagen_evento'])): ?>
                <img src="<?= htmlspecialchars($evento['imagen_evento']) ?>"
                     alt="<?= htmlspecialchars($evento['descripcion']) ?>"
                     style="width:100%; height:100%; object-fit:cover; border-radius:10px;">
            <?php else: ?>
                <label class="placeholder-imagen">
                    <i class="fas fa-image"></i>
                    <span>Sin imagen</span>
                </label>
            <?php endif; ?>
        </div>

        <!-- Información del evento -->
        <div class="informacion-evento">

            <h1 class="titulo-evento">
                <?= htmlspecialchars($evento['descripcion']) ?>
            </h1>

            <p class="descripcion-evento">
                <?= htmlspecialchars($evento['descripcion_larga'] ?? 'Sin descripción disponible.') ?>
            </p>

            <div class="detalles-evento">
                <div style="display:flex; align-items:center; gap:1rem; margin-bottom:0.75rem;">
                    <i class="fas fa-user" style="color:#FC3A05;"></i>
                    <span><?= htmlspecialchars($evento['comediante']) ?></span>
                </div>
                <div style="display:flex; align-items:center; gap:1rem; margin-bottom:0.75rem;">
                    <i class="fas fa-calendar-alt" style="color:#FC3A05;"></i>
                    <span><?= date('d/m/Y', strtotime($evento['fechahora'])) ?></span>
                </div>
                <div style="display:flex; align-items:center; gap:1rem; margin-bottom:0.75rem;">
                    <i class="fas fa-clock" style="color:#FC3A05;"></i>
                    <span><?= date('H:i', strtotime($evento['fechahora'])) ?> hrs</span>
                </div>
                <div style="display:flex; align-items:center; gap:1rem;">
                    <i class="fas fa-map-marker-alt" style="color:#FC3A05;"></i>
                    <span><?= htmlspecialchars($evento['ubicacion'] ?? 'Por confirmar') ?></span>
                </div>
            </div>

            <?php if (isset($_GET['exito']) && $_GET['exito'] === 'reservado'): ?>
                <p style="color:#4caf50; font-weight:700; margin-top:1rem;">
                    ✅ Reserva realizada correctamente.
                </p>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'ya_reservado'): ?>
                <p style="color:#FC3A05; font-weight:700; margin-top:1rem;">
                    ⚠️ Ya tienes una reserva para este evento.
                </p>
            <?php endif; ?>

            <!-- Botón reservar -->
            <?php if ($_SESSION['tipo'] === 'standard'): ?>
                <form action="../Controller/EntradaController.php" method="post">
                    <input type="hidden" name="idEvento" value="<?= $evento['IDEvento'] ?>">
                    <button type="submit" name="reservar" class="boton-reservar">
                        Reservar
                    </button>
                </form>

            <?php else: ?>
                <!-- Admin ve el botón bloqueado -->
                <button class="boton-reservar"
                        disabled
                        title="Los organizadores no pueden reservar entradas"
                        style="opacity:0.4; cursor:not-allowed;">
                    Reservar
                </button>
                <p style="color:#b5b5b5; font-size:0.85rem; margin-top:0.5rem;">
                    Los organizadores no pueden reservar entradas.
                </p>
            <?php endif; ?>

        </div>
    </div>
</section>

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
            </ul>
        </div>
    </div>
</footer>

</body>
</html>