<?php
session_start();

$nombre   = $_SESSION['nombre']   ?? 'Usuario';
$email    = $_SESSION['email']    ?? '';
// $password = $_SESSION['password'] ?? '';

$exitos = [
    'evento_creado'      => 'Evento creado correctamente.',
    'evento_actualizado' => 'Evento actualizado correctamente.',
    'evento_eliminado'   => 'Evento eliminado correctamente.',
];
if (isset($_GET['exito']) && array_key_exists($_GET['exito'], $exitos)) {
    echo '<p style="color: var(--primary-orange); font-weight:700;">' . $exitos[$_GET['exito']] . '</p>';
}

var_dump($_SESSION);
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

        /* ══ NAVBAR ══ */
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

        /* ══ MAIN ══ */
        main {
            flex: 1;
            width: 90%;
            max-width: 1200px;
            margin: 3rem auto;
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        /* ══ HERO ══ */
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

        /* Contenedor del avatar con botón de edición */
        .avatar-wrapper {
            position: relative;
            flex-shrink: 0;
            width: 7rem;
            height: 7rem;
        }

        .avatar-grande {
            width: 100%;
            height: 100%;
            background-color: var(--input-bg);
            border-radius: 50%;
            border: 3px solid var(--primary-orange);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .avatar-grande .material-icons-round {
            font-size: 3.5rem;
            color: var(--text-muted);
        }

        /* Foto de perfil si existe */
        .avatar-grande img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Input file real — invisible */
        #input-foto {
            display: none;
        }

        /* Botón naranja pequeño en la esquina inferior derecha del avatar */
        .label-foto {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 2.2rem;
            height: 2.2rem;
            background-color: var(--primary-orange);
            border-radius: 50%;
            border: 3px solid var(--card-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.2s;
        }

        .label-foto:hover {
            transform: scale(1.12);
            background-color: #e03000;
        }

        .label-foto .material-icons-round {
            font-size: 1rem;
            color: white;
        }

        .perfil-nombre {
            font-size: 1.8rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 0.2rem;
        }

        .perfil-email {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* ══ CARD BASE ══ */
        .card {
            background-color: var(--card-bg);
            border-radius: 15px;
            padding: 1.75rem;
            position: relative;
        }

        .card-titulo {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--primary-orange);
            letter-spacing: 1px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .card-titulo .material-icons-round {
            font-size: 1rem;
        }

        .card-icon-fondo {
            font-size: 3.5rem;
            color: var(--primary-orange);
            opacity: 0.1;
            position: absolute;
            bottom: 1rem;
            right: 1.5rem;
            pointer-events: none;
        }

        /* ══ DATOS PERSONALES — TOGGLE CSS PURO ══
           Cómo funciona:
           - #toggle-editar es un checkbox invisible
           - Está FUERA de .card-datos pero es hermano anterior dentro de <main>
           - El selector ~ selecciona el hermano siguiente .card-datos
           - <label for="toggle-editar"> activa/desactiva sin JavaScript
           - "Cancelar" es un <a href> que recarga la página y resetea el checkbox
        */
        #toggle-editar {
            display: none;
        }

        .label-editar {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
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
            user-select: none;
        }

        .label-editar:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Por defecto: lápiz visible, X oculta */
        .icono-cerrar {
            display: none;
        }

        /* Cuando el checkbox está activado */
        #toggle-editar:checked~.card-datos .icono-editar {
            display: none;
        }

        #toggle-editar:checked~.card-datos .icono-cerrar {
            display: flex;
        }

        #toggle-editar:checked~.card-datos .datos-vista {
            display: none;
        }

        #toggle-editar:checked~.card-datos .datos-form {
            display: flex;
        }

        /* Vista lectura: grid 3 columnas */
        .datos-vista {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem 2rem;
        }

        .dato-fila {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .dato-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
        }

        .dato-valor {
            font-size: 1rem;
            font-weight: 600;
        }

        /* Formulario edición: oculto por defecto */
        .datos-form {
            display: none;
            flex-direction: column;
            gap: 1rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .form-grupo {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .form-grupo label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
        }

        .form-grupo input {
            background-color: var(--input-bg);
            border: 1px solid #444;
            border-radius: 0.5rem;
            padding: 0.6rem 0.85rem;
            color: white;
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }

        .form-grupo input:focus {
            outline: none;
            border-color: var(--primary-orange);
        }

        .form-acciones {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .btn-guardar {
            background-color: var(--primary-orange);
            border: none;
            color: white;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 0.9rem;
            padding: 0.6rem 1.75rem;
            border-radius: 0.4rem;
            cursor: pointer;
        }

        .btn-cancelar-edit {
            background: transparent;
            border: 1px solid #555;
            color: var(--text-muted);
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.6rem 1.5rem;
            border-radius: 0.4rem;
            display: inline-flex;
            align-items: center;
            transition: border-color 0.2s, color 0.2s;
        }

        .btn-cancelar-edit:hover {
            border-color: white;
            color: white;
        }

        .mensaje-ok {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(252, 58, 5, 0.12);
            border: 1px solid var(--primary-orange);
            border-radius: 0.5rem;
            padding: 0.65rem 1rem;
            font-size: 0.85rem;
            color: var(--primary-orange);
            font-weight: 700;
            margin-top: 0.75rem;
        }

        /* ══ FILA STATS (2 columnas lado a lado) ══ */
        .fila-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .stats-row {
            display: flex;
        }

        .stat-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 0.25rem 0;
        }

        .stat-item:not(:last-child) {
            border-right: 1px solid rgba(255, 255, 255, 0.07);
        }

        .stat-numero {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.3rem;
        }

        .valoracion-numero {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
        }

        .estrellas {
            display: flex;
            gap: 0.2rem;
            margin-top: 0.6rem;
        }

        .estrella {
            color: var(--primary-orange);
            font-size: 1.4rem;
        }

        .estrella-vacia {
            color: #444;
            font-size: 1.4rem;
        }

        /* ══ HISTORIAL ══ */
        .seccion-titulo {
            font-size: 1rem;
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
            gap: 1.25rem;
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

        /* ══ FOOTER ══ */
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

        /* ══ RESPONSIVE ══ */
        @media screen and (max-width: 992px) {
            .fila-stats {
                grid-template-columns: 1fr 1fr;
            }

            .datos-vista,
            .form-grid {
                grid-template-columns: 1fr 1fr;
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
                position: relative;
                flex-wrap: wrap;
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

            .fila-stats {
                grid-template-columns: 1fr;
            }

            .datos-vista,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-acciones {
                flex-direction: column-reverse;
            }

            .btn-guardar,
            .btn-cancelar-edit {
                width: 100%;
                justify-content: center;
            }

            .footer-wrap {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }
        }

        /* ══ MODAL ELIMINAR ══ */

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.activo {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-box {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 2rem;
            max-width: 420px;
            width: 90%;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .modal-icono {
            font-size: 3rem;
            color: var(--primary-orange);
            margin: 0 auto;
        }

        .modal-titulo {
            font-size: 1.2rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .modal-texto {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .modal-acciones {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn-modal-cancelar {
            background: transparent;
            border: 1px solid #555;
            color: var(--text-muted);
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 0.6rem 1.5rem;
            border-radius: 0.4rem;
            cursor: pointer;
        }

        .btn-modal-confirmar {
            background: var(--primary-orange);
            border: none;
            color: white;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            padding: 0.6rem 1.5rem;
            border-radius: 0.4rem;
            cursor: pointer;
        }

        .btn-eliminar {
            background: transparent;
            border: 1px solid #e03000;
            color: #e03000;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.6rem 1.5rem;
            border-radius: 0.4rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: background 0.2s, color 0.2s;
        }

        .btn-eliminar:hover {
            background: #e03000;
            color: white;
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
                    <img src="img/logotipo2_StandApp_Dunia.png" alt="Logo StandApp">
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

            <!-- Formulario de subida de foto — envía al controller -->
            <form action="../Controller/UserController.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="actualizar_foto">

                <div class="avatar-wrapper">
                    <div class="avatar-grande">
                        <?php if (!empty($_SESSION['foto'])): ?>
                            <img src="<?= htmlspecialchars($_SESSION['foto']) ?>" alt="Foto de perfil">
                        <?php else: ?>
                            <span class="material-icons-round">person_outline</span>
                        <?php endif; ?>
                    </div>

                    <!-- Input file oculto — el label de abajo lo activa -->
                    <input type="file" id="input-foto" name="foto"
                        accept="image/png, image/jpeg, image/webp"
                        onchange="this.form.submit()">

                    <!-- Botón naranja en esquina inferior derecha -->
                    <label for="input-foto" class="label-foto" title="Cambiar foto de perfil">
                        <span class="material-icons-round">photo_camera</span>
                    </label>
                </div>

            </form>

            <div>
                <div class="perfil-nombre"><?= htmlspecialchars($nombre) ?></div>
                <div class="perfil-email"><?= htmlspecialchars($email) ?></div>
            </div>
            <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
                <div style="margin-left: auto;">
                    <a href="event_create.php" class="boton boton-contorno">
                        <span class="material-icons-round" style="font-size:1rem; margin-right:0.4rem;">add_circle</span>
                        Crear evento
                    </a>
                </div>
            <?php endif; ?>
        </section>

        <!-- DATOS PERSONALES (ancho completo, encima de las stats) -->
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
                    <span class="dato-label">Nombre completo</span>
                    <span class="dato-valor"><?= htmlspecialchars($nombre) ?></span>
                </div>
                <div class="dato-fila">
                    <span class="dato-label">Correo electrónico</span>
                    <span class="dato-valor"><?= htmlspecialchars($email) ?></span>
                </div>
                <div class="dato-fila">
                    <span class="dato-label">Contraseña</span>
                    <span class="dato-valor">••••••••</span>
                </div>
            </div>

            <!-- Formulario de edición -->
            <form class="datos-form" action="../Controller/UserController.php" method="post">
                <input type="hidden" name="accion" value="actualizar_perfil">
                <div class="form-grid">
                    <div class="form-grupo">
                        <label for="inputNombre">Nombre completo</label>
                        <input type="text" id="inputNombre" name="nombre"
                            value="<?= htmlspecialchars($nombre) ?>" required>
                    </div>
                    <div class="form-grupo">
                        <label for="inputEmail">Correo electrónico</label>
                        <input type="email" id="inputEmail" name="email"
                            value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="form-grupo">
                        <label for="inputPassword">Nueva contraseña</label>
                        <input type="password" id="inputPassword" name="password"
                            placeholder="Dejar vacío para no cambiar">
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

        <!-- GESTIÓN DE EVENTOS — solo visible en perfil_admin -->
        <?php
        require_once "../Controller/EventController.php";
        $eventController = new EventController();
        $eventos = $eventController->readAll();
        ?>

        <div class="card">
            <div class="card-titulo">
                <span class="material-icons-round">event</span>
                Mis eventos
            </div>

            <?php if (empty($eventos)): ?>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    No hay eventos creados todavía.
                </p>
            <?php else: ?>
                <div class="historial-lista">
                    <?php foreach ($eventos as $evento): ?>
                        <div class="historial-item">

                            <!-- Fecha -->
                            <div class="historial-fecha">
                                <div class="historial-fecha-dia">
                                    <?= date('d', strtotime($evento['fechahora'])) ?>
                                </div>
                                <div class="historial-fecha-mes">
                                    <?= date('M', strtotime($evento['fechahora'])) ?>
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="historial-info">
                                <div class="historial-nombre">
                                    <?= htmlspecialchars($evento['comediante']) ?>
                                </div>
                                <div class="historial-lugar">
                                    <?= htmlspecialchars($evento['descripcion']) ?>
                                </div>
                            </div>

                            <!-- Botones editar y eliminar -->
                            <div style="display:flex; gap:0.5rem; align-items:center;">

                                <!-- Editar -->
                                <a href="event_edit.php?id=<?= $evento['IDEvento'] ?>"
                                    style="
                               background: transparent;
                               border: 1px solid var(--text-muted);
                               color: var(--text-muted);
                               padding: 0.4rem 0.9rem;
                               border-radius: 0.4rem;
                               font-family: 'Outfit', sans-serif;
                               font-size: 0.8rem;
                               font-weight: 700;
                               display: inline-flex;
                               align-items: center;
                               gap: 0.3rem;
                               transition: border-color 0.2s, color 0.2s;
                           "
                                    onmouseover="this.style.borderColor='white'; this.style.color='white';"
                                    onmouseout="this.style.borderColor='var(--text-muted)'; this.style.color='var(--text-muted)';">
                                    <span class="material-icons-round" style="font-size:0.9rem;">edit</span>
                                    Editar
                                </a>

                                <!-- Eliminar -->
                                <form action="../Controller/EventController.php" method="post" style="margin:0;">
                                    <input type="hidden" name="idEvento" value="<?= $evento['IDEvento'] ?>">
                                    <button type="submit" name="delete_event"
                                        style="
                                    background: transparent;
                                    border: 1px solid var(--primary-orange);
                                    color: var(--primary-orange);
                                    padding: 0.4rem 0.9rem;
                                    border-radius: 0.4rem;
                                    font-family: 'Outfit', sans-serif;
                                    font-size: 0.8rem;
                                    font-weight: 700;
                                    cursor: pointer;
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 0.3rem;
                                "
                                        onclick="return confirm('¿Seguro que quieres eliminar este evento?')">
                                        <span class="material-icons-round" style="font-size:0.9rem;">delete</span>
                                        Eliminar
                                    </button>
                                </form>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <span class="material-icons-round card-icon-fondo">event</span>
        </div>

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
        <section>
            <form action="../Controller/UserController.php" method="post">
                <div id="modal-eliminar" class="modal-overlay">
                    <div class="modal-box">
                        <span class="material-icons-round modal-icono">warning</span>
                        <h2 class="modal-titulo">¿Eliminar tu cuenta?</h2>
                        <p class="modal-texto">Esta acción es permanente y no se puede deshacer. Se borrarán todos tus datos.</p>
                        <div class="modal-acciones">
                            <button type="button" id="btn-modal-cancelar" class="btn-modal-cancelar">
                                Cancelar
                            </button>
                            <form action="../Controller/UserController.php" method="post">
                                <button type="submit" name="deleteProfile" class="btn-modal-confirmar">
                                    Sí, eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                <script src="JS/perfil.js"></script>

                <button type="submit" id="btn-eliminar" name="deleteProfile" class="btn-eliminar-perfil"
                    style="
                background: transparent;
                border: 1px solid var(--primary-orange);
                color: var(--primary-orange);
                padding: 0.6rem 1.5rem;
                border-radius: 0.4rem;
                font-family: 'Outfit', sans-serif;
                font-weight: bold;
                cursor: pointer;
                font-size: 0.9rem;
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
            ">
                    <span class="material-icons-round" style="font-size:1rem;">delete</span>
                    Eliminar perfil
                </button>
            </form>
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