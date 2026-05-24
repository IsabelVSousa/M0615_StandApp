<?php
session_start();

// Si no está logueado, al login
if (!isset($_SESSION['IDPersona'])) {
    header("Location: LogIn.php");
    exit;
}

require_once "../Controller/EventController.php";
$eventController = new EventController();

// Recoger filtros de la URL
$fecha    = $_GET['fecha']    ?? '';
$busqueda = $_GET['busqueda'] ?? '';

// Cargar eventos con filtros
$eventos = $eventController->readAllPublic($fecha, $busqueda);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos los Eventos — StandApp</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-orange: #FC3A05;
            --bg-dark: #1F2124;
            --card-bg: #363636;
            --input-bg: #2a2a2a;
            --text-white: #ffffff;
            --text-muted: #b5b5b5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-white);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* NAVBAR */
        .barra-superior {
            background-color: var(--primary-orange);
        }

        .navegacion-principal {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            gap: 5%;
        }

        .navegacion-izquierda {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo-circulo {
            width: 3rem;
            height: 3rem;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .logo-circulo img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        .logo-texto {
            font-weight: 800;
            text-transform: uppercase;
            font-size: 1rem;
            color: white;
        }

        .enlaces-navegacion {
            display: flex;
            gap: 20px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .enlaces-navegacion a {
            border-right: 2px solid rgba(255, 255, 255, 0.4);
            padding-right: 20px;
            color: white;
        }

        .enlaces-navegacion a:last-child {
            border-right: none;
        }

        .botones-navegacion {
            display: flex;
            gap: 10px;
        }

        .boton {
            padding: 0.6rem 1.5rem;
            border-radius: 0.4rem;
            font-family: 'Outfit', sans-serif;
            font-weight: bold;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .boton-contorno {
            background: transparent;
            border: 1px solid white;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        /* CONTENIDO */
        main {
            flex: 1;
            width: 90%;
            max-width: 1200px;
            margin: 3rem auto;
        }

        .page-titulo {
            font-size: 2.5rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .page-subtitulo {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 2.5rem;
        }

        /* GRID DE EVENTOS */
        .eventos-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .evento-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }

        .evento-card:hover {
            transform: translateY(-4px);
        }

        .evento-imagen {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .evento-imagen-placeholder {
            width: 100%;
            height: 200px;
            background-color: var(--input-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 2.5rem;
        }

        .evento-body {
            padding: 1.25rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .evento-titulo {
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .evento-comediante {
            color: var(--primary-orange);
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .evento-info {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .evento-dato {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .evento-dato i {
            color: var(--primary-orange);
            width: 1rem;
            text-align: center;
        }

        .evento-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .btn-ver {
            display: block;
            text-align: center;
            background-color: var(--primary-orange);
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 0.4rem;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
            transition: background 0.2s;
        }

        .btn-ver:hover {
            background-color: #e03000;
        }

        /* Sin eventos */
        .sin-eventos {
            text-align: center;
            padding: 4rem 0;
            color: var(--text-muted);
        }

        .sin-eventos i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }

        /* FOOTER */
        .footer-section {
            background-color: var(--primary-orange);
            padding: 2.5rem 5%;
            margin-top: auto;
        }

        .footer-wrap {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-copy {
            font-size: 0.85rem;
            opacity: 0.85;
        }

        /* RESPONSIVE */
        @media screen and (max-width: 992px) {
            .eventos-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 600px) {
            .eventos-grid {
                grid-template-columns: 1fr;
            }

            .page-titulo {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <header class="barra-superior">
        <nav class="navegacion-principal">
            <div class="navegacion-izquierda">
                <div class="logo-circulo">
                    <img src="img/logotipo2_StandApp_Dunia.png" alt="Logo StandApp">
                </div>
                <span class="logo-texto">Stand-App</span>
            </div>

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
    </header>

    <main>
        <h1 class="page-titulo">Todos los eventos</h1>
        <p class="page-subtitulo">
            <?= count($eventos) ?> evento<?= count($eventos) !== 1 ? 's' : '' ?> disponible<?= count($eventos) !== 1 ? 's' : '' ?>
        </p>
        <?php if (!empty($fecha) || !empty($busqueda)): ?>
            <div style="
        display:flex; 
        align-items:center; 
        gap:1rem; 
        margin-bottom:1.5rem;
        color: var(--text-muted);
        font-size: 0.9rem;
    ">
                <span>Filtrando por:</span>

                <?php if (!empty($fecha)): ?>
                    <span style="
                background: rgba(252,58,5,0.15);
                border: 1px solid #FC3A05;
                color: #FC3A05;
                padding: 0.3rem 0.75rem;
                border-radius: 20px;
                font-weight: 700;
            ">
                        📅 <?= date('d/m/Y', strtotime($fecha)) ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($busqueda)): ?>
                    <span style="
                background: rgba(252,58,5,0.15);
                border: 1px solid #FC3A05;
                color: #FC3A05;
                padding: 0.3rem 0.75rem;
                border-radius: 20px;
                font-weight: 700;
            ">
                        🔍 "<?= htmlspecialchars($busqueda) ?>"
                    </span>
                <?php endif; ?>

                <a href="eventos_todos.php" style="color: var(--text-muted); text-decoration: underline;">
                    Limpiar filtros
                </a>
            </div>
        <?php endif; ?>
        
        <?php if (empty($eventos)): ?>
            <div class="sin-eventos">
                <i class="fas fa-calendar-times"></i>
                <p>No hay eventos disponibles en este momento.</p>
            </div>
        <?php else: ?>
            <div class="eventos-grid">
                <?php foreach ($eventos as $ev): ?>
                    <div class="evento-card">

                        <!-- Imagen -->
                        <?php if (!empty($ev['imagen_evento'])): ?>
                            <img class="evento-imagen"
                                src="<?= htmlspecialchars($ev['imagen_evento']) ?>"
                                alt="<?= htmlspecialchars($ev['descripcion']) ?>">
                        <?php else: ?>
                            <div class="evento-imagen-placeholder">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Info -->
                        <div class="evento-body">
                            <div class="evento-titulo">
                                <?= htmlspecialchars($ev['descripcion']) ?>
                            </div>
                            <div class="evento-comediante">
                                <i class="fas fa-microphone"></i>
                                <?= htmlspecialchars($ev['comediante']) ?>
                            </div>
                            <div class="evento-info">
                                <div class="evento-dato">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?= date('d/m/Y', strtotime($ev['fechahora'])) ?>
                                </div>
                                <div class="evento-dato">
                                    <i class="fas fa-clock"></i>
                                    <?= date('H:i', strtotime($ev['fechahora'])) ?> hrs
                                </div>
                                <div class="evento-dato">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($ev['ubicacion'] ?? 'Por confirmar') ?>
                                </div>
                            </div>
                        </div>

                        <!-- Botón -->
                        <div class="evento-footer">
                            <a href="Event.php?id=<?= $ev['IDEvento'] ?>" class="btn-ver">
                                Ver evento
                            </a>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- FOOTER -->
    <footer class="footer-section">
        <div class="footer-wrap">
            <span class="footer-copy">© 2026 StandApp — Todos los derechos reservados</span>
        </div>
    </footer>

</body>

</html>