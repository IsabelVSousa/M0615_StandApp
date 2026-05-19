<?php
session_start();
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../View/LogIn.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Evento — StandApp</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
    <style>
        .preview-imagen {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 0.5rem;
            display: none;
        }
    </style>
</head>
<body>

<!-- NAVBAR igual al de perfil_admin -->
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
            <a href="perfil_admin.php">Mi perfil</a>
        </div>
        <div class="botones-navegacion">
            <form action="../Controller/UserController.php" method="post">
                <button type="submit" name="logout" class="boton boton-contorno">Cerrar Sesión</button>
            </form>
        </div>
    </nav>
</header>

<section class="contenedor-registro">
    <div class="tarjeta-registro">
        <h1 class="titulo-registro">Crear Evento</h1>

        <?php
        $errores = [
            'campos_vacios'     => 'Por favor rellena todos los campos obligatorios.',
            'descripcion_larga' => 'El título no puede superar 50 caracteres.',
            'error_bd'          => 'Error al guardar. Inténtalo de nuevo.',
        ];
        if (isset($_GET['error']) && array_key_exists($_GET['error'], $errores)) {
            echo '<p style="color:red; margin-bottom:1rem;">' . $errores[$_GET['error']] . '</p>';
        }
        ?>

        <form class="formulario-registro" 
              action="../Controller/EventController.php" 
              method="post" 
              enctype="multipart/form-data">

            <!-- Imagen del evento -->
            <div class="grupo-formulario">
                <label>Imagen del evento</label>
                <input type="file" name="imagen_evento" id="imagen_evento" 
                       accept="image/*"
                       onchange="previewImagen(this)">
                <img id="preview" class="preview-imagen" src="" alt="Preview">
            </div>

            <!-- Nombre del comediante -->
            <div class="grupo-formulario">
                <label>Comediante <span style="color:red;">*</span></label>
                <input type="text" name="comediante" placeholder="Nombre del comediante" required>
            </div>

            <!-- Título corto — aparece en las tarjetas -->
            <div class="grupo-formulario">
                <label>Título del evento <span style="color:red;">*</span></label>
                <input type="text" name="descripcion" placeholder="Título corto (máx. 50 caracteres)" 
                       maxlength="50" required>
            </div>

            <!-- Descripción larga — aparece en Event.php -->
            <div class="grupo-formulario">
                <label>Descripción completa</label>
                <textarea name="descripcion_larga" 
                          placeholder="Describe el evento con detalle..."
                          rows="4"
                          style="
                              background-color: #2a2a2a;
                              border: 1px solid #444;
                              border-radius: 0.5rem;
                              padding: 0.6rem 0.85rem;
                              color: white;
                              font-family: 'Outfit', sans-serif;
                              font-size: 0.9rem;
                              width: 100%;
                              resize: vertical;
                          "></textarea>
            </div>

            <!-- Ubicación -->
            <div class="grupo-formulario">
                <label>Ubicación <span style="color:red;">*</span></label>
                <input type="text" name="ubicacion" 
                       placeholder="Ej: Teatro Lara, Madrid" required>
            </div>

            <!-- Fecha y hora -->
            <div class="grupo-formulario">
                <label>Fecha y hora <span style="color:red;">*</span></label>
                <input type="datetime-local" name="fechahora" required>
            </div>

            <div class="acciones-formulario">
                <a href="perfil_admin.php" class="boton-cancelar">Cancelar</a>
                <button type="submit" name="create_event" class="boton-enviar">
                    Crear Evento
                </button>
            </div>
        </form>
    </div>
</section>

<script>
function previewImagen(input) {
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</body>
</html>