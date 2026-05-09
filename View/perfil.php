<?php
session_start();

if(isset($_SESSION['nombre'])){

}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - StandApp</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-orange: #FC3A05;
            --bg-dark: #202020;
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
            font-family: 'Outfit', sans-serif;
            color: white;
            text-decoration: none;
        }

        /* ── NAVBAR ── */
        .barra-superior {
            background-color: var(--primary-orange);
        }

        .navegacion-principal {
            background-color: var(--primary-orange);
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
        }

        .enlaces-navegacion a:last-child {
            border-right: none;
            padding-right: 0;
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

        .menu-checkbox {
            display: none;
        }

        .menu-toggle {
            display: none;
            font-size: 1.6rem;
            color: white;
            cursor: pointer;
        }

        /* ── MAIN ── */
        main {
            flex: 1;
            width: 90%;
            max-width: 1200px;
            margin: 3rem auto;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* ── HERO PERFIL ── */
        .perfil-hero {
            background-color: var(--card-bg);
            border-radius: 15px;
            padding: 2rem 2.5rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            position: relative;
            overflow: hidden;
        }

        .perfil-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background-color: var(--primary-orange);
            border-radius: 15px 0 0 15px;
        }

        .avatar-grande {
            width: 7rem;
            height: 7rem;
            background-color: var(--input-bg);
            border-radius: 50%;
            border: 3px solid var(--primary-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .avatar-grande .material-icons-round {
            font-size: 3.5rem;
            color: var(--text-muted);
        }

        .perfil-info {
            flex: 1;
        }

        .perfil-nombre {
            font-size: 1.8rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .perfil-email {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
        }

        .perfil-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background-color: var(--primary-orange);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        .perfil-badge .material-icons-round {
            font-size: 0.95rem;
        }

        .boton-editar {
            background-color: var(--primary-orange);
            border: none;
            color: white;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.9rem;
            align-self: flex-start;
            margin-left: auto;
        }

        .boton-editar .material-icons-round {
            font-size: 1.1rem;
        }

        /* ── GRID CARDS ── */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 15px;
            padding: 1.75rem;
            position: relative;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card-titulo {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--primary-orange);
            letter-spacing: 1px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .card-titulo .material-icons-round {
            font-size: 1rem;
        }

        .card-valor {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }

        .card-subtitulo {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .card-icon-grande {
            font-size: 2.5rem;
            color: var(--primary-orange);
            opacity: 0.2;
            position: absolute;
            bottom: 1rem;
            right: 1.25rem;
        }

        /* Card acción con botón de settings */
        .btn-settings {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0.3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s, background 0.2s;
        }

        .btn-settings:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Estadísticas en fila */
        .card-estadisticas {
            grid-column: span 2;
        }

        .stats-row {
            display: flex;
            gap: 2rem;
            margin-top: 0.5rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-numero {
            font-size: 2rem;
            font-weight: 800;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-divider {
            width: 1px;
            background: rgba(255, 255, 255, 0.1);
            align-self: stretch;
        }

        /* Valoración con estrellas */
        .estrellas {
            display: flex;
            gap: 0.25rem;
            margin-top: 0.75rem;
        }

        .estrella {
            color: var(--primary-orange);
            font-size: 1.4rem;
        }

        .estrella-vacia {
            color: #444;
        }

        /* Sección historial */
        .seccion-titulo {
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .seccion-titulo::after {
            content: '';
            flex: 1;
            height: 2px;
            background: var(--card-bg);
        }

        .historial-lista {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .historial-item {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: background 0.2s;
        }

        .historial-item:hover {
            background-color: #404040;
        }

        .historial-fecha {
            background-color: var(--primary-orange);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            text-align: center;
            flex-shrink: 0;
            min-width: 3.5rem;
        }

        .historial-fecha-dia {
            font-size: 1.2rem;
            font-weight: 800;
            line-height: 1;
        }

        .historial-fecha-mes {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .historial-info {
            flex: 1;
        }

        .historial-nombre {
            font-weight: 700;
            font-size: 0.95rem;
        }

        .historial-lugar {
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: 0.2rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .historial-lugar .material-icons-round {
            font-size: 0.9rem;
        }

        .historial-precio {
            font-weight: 800;
            font-size: 1rem;
            color: var(--primary-orange);
        }

        /* ── FOOTER ── */
        .footer-section {
            background-color: var(--primary-orange);
            padding: 2.5rem 5%;
            margin-top: auto;
        }

        .footer-wrap {
            justify-content: space-between;
            align-items: center;
            display: flex;
        }

        .footer-copy {
            font-size: 0.85rem;
            opacity: 0.85;
        }

        .footer-social-wrap {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .footer-social-wrap a {
            opacity: 0.85;
            transition: opacity 0.2s;
        }

        .footer-social-wrap a:hover {
            opacity: 1;
        }

        /* ── RESPONSIVE ── */
        @media screen and (max-width: 992px) {
            .cards-grid {
                grid-template-columns: 1fr 1fr;
            }

            .card-estadisticas {
                grid-column: span 2;
            }
        }

        @media screen and (max-width: 742px) {
            body {
                overflow-x: hidden;
            }

            .menu-toggle {
                display: block;
            }

            .navegacion-principal {
                flex-wrap: wrap;
                position: relative;
            }

            .enlaces-navegacion,
            .botones-navegacion {
                display: none;
                flex-direction: column;
                align-items: center;
                background-color: var(--primary-orange);
                position: absolute;
                top: 100%;
                left: 50%;
                transform: translateX(-50%);
                padding: 1rem 0;
                gap: 1rem;
                z-index: 1000;
                width: 90%;
                border-radius: 10px;
            }

            .menu-checkbox:checked~.enlaces-navegacion,
            .menu-checkbox:checked~.botones-navegacion {
                display: flex;
            }

            .perfil-hero {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
            }

            .perfil-hero::before {
                width: 100%;
                height: 5px;
            }

            .boton-editar {
                margin: 0 auto;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }

            .card-estadisticas {
                grid-column: span 1;
            }

            .footer-wrap {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <header class="barra-superior">
        <nav class="navegacion-principal">
            <input type="checkbox" id="menu-toggle" class="menu-checkbox">

            <div class="navegacion-izquierda">
                <div class="logo-circulo">
                    <img src="/STAND_APP/img/logotipo2_StandApp_Dunia.png" alt="Logo StandApp">
                </div>
                <span class="logo-texto">Stand-App</span>
            </div>

            <label for="menu-toggle" class="menu-toggle">
                <span class="material-icons-round">menu</span>
            </label>

            <div class="enlaces-navegacion">
                <a href="Home.php">Home</a>
                <a href="Event.html">Descuentos</a>
                <a href="foro.html">Foro</a>
                <a href="organizadores.html">Organizadores</a>
            </div>

            <div class="botones-navegacion">
                <form action="../Controller/UserController.php" method="post">
                    <button type="submit" name="logout" class="boton boton-contorno">Cerrar Sesión</button>
                </form>
            </div>
        </nav>
    </header>

    <main>

        <!-- HERO -->
        <section class="perfil-hero">
            <div class="avatar-grande">
                <span class="material-icons-round">person_outline</span>
            </div>
            <div>
                <div class="perfil-nombre">Lorem Ipsum</div>
                <div class="perfil-email">loremipsum@email.com</div>
            </div>
        </section>

        <!-- FILA: Actividad + Valoración -->
        <div class="fila-stats">

            <div class="card">
                <div class="card-titulo">
                    <span class="material-icons-round">bar_chart</span>
                    Mi actividad
                </div>
                <div class="stats-row">
                    <div class="stat-item">
                        <span class="stat-numero">12</span>
                        <span class="stat-label">Shows asistidos</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-numero">3</span>
                        <span class="stat-label">Próximos eventos</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-numero">5</span>
                        <span class="stat-label">Reseñas escritas</span>
                    </div>
                </div>
                <span class="material-icons-round card-icon-fondo">bar_chart</span>
            </div>

            <div class="card">
                <div class="card-titulo">
                    <span class="material-icons-round">star</span>
                    Mi valoración media
                </div>
                <div class="valoracion-numero">4.2 <span style="font-size:1rem; color:var(--text-muted); font-weight:400;">/ 5</span></div>
                <div class="estrellas">
                    <span class="material-icons-round estrella">star</span>
                    <span class="material-icons-round estrella">star</span>
                    <span class="material-icons-round estrella">star</span>
                    <span class="material-icons-round estrella">star</span>
                    <span class="material-icons-round estrella-vacia">star_border</span>
                </div>
                <span class="material-icons-round card-icon-fondo">grade</span>
            </div>

        </div>

        <!--
            ══ DATOS PERSONALES (ancho completo) ══
            El checkbox #toggle-editar y .card-datos son hermanos directos dentro de <main>.
            El selector CSS "#toggle-editar:checked ~ .card-datos" funciona porque
            el ~ selecciona hermanos que vienen DESPUÉS en el mismo padre.
            El <label for="toggle-editar"> activa/desactiva el checkbox sin JS.
            "Cancelar" recarga la página con href, lo que resetea el checkbox.
        -->
        <input type="checkbox" id="toggle-editar">

        <div class="card card-datos">

            <label for="toggle-editar" class="label-editar" title="Editar datos personales">
                <span class="material-icons-round icono-editar">edit</span>
                <span class="material-icons-round icono-cerrar">close</span>
            </label>

            <div class="card-titulo">
                <span class="material-icons-round">person</span>
                Datos personales
            </div>

            <!-- Vista de solo lectura -->
            <div class="datos-vista">
                <div class="dato-fila">
                    <span class="dato-label">Nombre</span>
                    <span class="dato-valor">Lorem</span>
                </div>
                <div class="dato-fila">
                    <span class="dato-label">Apellido</span>
                    <span class="dato-valor">Ipsum</span>
                </div>
                <div class="dato-fila">
                    <span class="dato-label">Teléfono</span>
                    <span class="dato-valor">+34 000 00 00 00</span>
                </div>
                <div class="dato-fila">
                    <span class="dato-label">Correo electrónico</span>
                    <span class="dato-valor">loremipsum@email.com</span>
                </div>
                <div class="dato-fila">
                    <span class="dato-label">Contraseña</span>
                    <span class="dato-valor">••••••••</span>
                </div>
            </div>

            <!-- Formulario de edición (CSS lo muestra al activar el checkbox) -->
            <form class="datos-form" action="../Controller/UserController.php" method="post">
                <input type="hidden" name="accion" value="actualizar_perfil">
                <div class="form-grid">
                    <div class="form-grupo">
                        <label for="inputNombre">Nombre</label>
                        <input type="text" id="inputNombre" name="nombre" placeholder="Nombre" required>
                    </div>
                    <div class="form-grupo">
                        <label for="inputApellido">Apellido</label>
                        <input type="text" id="inputApellido" name="apellido" placeholder="Apellido">
                    </div>
                    <div class="form-grupo">
                        <label for="inputTelefono">Teléfono</label>
                        <input type="tel" id="inputTelefono" name="telefono" placeholder="+34 000 00 00 00">
                    </div>
                    <div class="form-grupo">
                        <label for="inputEmail">Correo electrónico</label>
                        <input type="email" id="inputEmail" name="email" placeholder="correo@email.com" required>
                    </div>
                    <div class="form-grupo">
                        <label for="inputPassword">Nueva contraseña</label>
                        <input type="password" id="inputPassword" name="password" placeholder="Dejar vacío para no cambiar">
                    </div>
                </div>
                <div class="form-acciones">
                    <a href="perfil.php" class="btn-cancelar-edit">Cancelar</a>
                    <button type="submit" class="btn-guardar">Guardar cambios</button>
                </div>
            </form>

            <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
                <div class="mensaje-ok">
                    <span class="material-icons-round" style="font-size:1rem;">check_circle</span>
                    Datos actualizados correctamente
                </div>
            <?php endif; ?>

        </div>

        <!-- HISTORIAL -->
        <section>
            <div class="seccion-titulo">
                <span class="material-icons-round" style="color:var(--primary-orange);">history</span>
                Historial de eventos
            </div>
            <div class="historial-lista">

                <div class="historial-item">
                    <div class="historial-fecha">
                        <div class="historial-fecha-dia">14</div>
                        <div class="historial-fecha-mes">Nov</div>
                    </div>
                    <div class="historial-info">
                        <div class="historial-nombre">Stand Up Noche de Comedia</div>
                        <div class="historial-lugar">
                            <span class="material-icons-round">location_on</span>
                            Teatro Lara, Madrid
                        </div>
                    </div>
                    <div class="historial-precio">18,00 €</div>
                </div>

                <div class="historial-item">
                    <div class="historial-fecha">
                        <div class="historial-fecha-dia">02</div>
                        <div class="historial-fecha-mes">Oct</div>
                    </div>
                    <div class="historial-info">
                        <div class="historial-nombre">Open Mic - Nuevos Talentos</div>
                        <div class="historial-lugar">
                            <span class="material-icons-round">location_on</span>
                            Sala Caracol, Barcelona
                        </div>
                    </div>
                    <div class="historial-precio">10,00 €</div>
                </div>

                <div class="historial-item">
                    <div class="historial-fecha">
                        <div class="historial-fecha-dia">19</div>
                        <div class="historial-fecha-mes">Sep</div>
                    </div>
                    <div class="historial-info">
                        <div class="historial-nombre">Gala de Humor - Edición Especial</div>
                        <div class="historial-lugar">
                            <span class="material-icons-round">location_on</span>
                            Auditorio Nacional, Madrid
                        </div>
                    </div>
                    <div class="historial-precio">25,00 €</div>
                </div>

            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="footer-section">
        <div class="footer-wrap">
            <span class="footer-copy">© 2024 StandApp — Todos los derechos reservados</span>
            <div class="footer-social-wrap">
                <a href="#" aria-label="Instagram"><span class="material-icons-round">photo_camera</span></a>
                <a href="#" aria-label="Twitter"><span class="material-icons-round">tag</span></a>
                <a href="#" aria-label="YouTube"><span class="material-icons-round">play_circle</span></a>
            </div>
        </div>
    </footer>

</body>

</html>