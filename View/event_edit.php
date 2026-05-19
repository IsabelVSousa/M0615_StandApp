<?php
session_start();
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../View/LogIn.php");
    exit;
}

require_once "../Controller/EventController.php";
$eventController = new EventController();
$idEvento = $_GET['id'] ?? '';
$evento   = $eventController->readOne($idEvento);

if (!$evento) {
    header("Location: ../View/perfil_admin.php?error=evento_no_encontrado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento — StandApp</title>
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
        }
    </style>
</head>
<body>

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
        <h1 class="titulo-registro">Editar Evento</h1>

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

            <input type="hidden" name="idEvento" value="<?= $evento['IDEvento'] ?>">

            <!-- Imagen actual + opción de cambiarla -->
            <div class="grupo-formulario">
                <label>Imagen del evento</label>
                <?php if (!empty($evento['imagen_evento'])): ?>
                    <img id="preview" class="preview-imagen"
                         src="<?= htmlspecialchars($evento['imagen_evento']) ?>"
                         alt="Imagen actual del evento">
                <?php else: ?>
                    <img id="preview" class="preview-imagen" src="" alt="Preview"
                         style="display:none;">
                <?php endif; ?>
                <input type="file" name="imagen_evento" id="imagen_evento"
                       accept="image/*"
                       onchange="previewImagen(this)"
                       style="margin-top:0.5rem;">
                <small style="color:#b5b5b5;">Deja vacío para mantener la imagen actual.</small>
            </div>

            <!-- Comediante -->
            <div class="grupo-formulario">
                <label>Comediante <span style="color:red;">*</span></label>
                <input type="text" name="comediante"
                       value="<?= htmlspecialchars($evento['comediante']) ?>" required>
            </div>

            <!-- Título corto -->
            <div class="grupo-formulario">
                <label>Título del evento <span style="color:red;">*</span></label>
                <input type="text" name="descripcion"
                       value="<?= htmlspecialchars($evento['descripcion']) ?>"
                       maxlength="50" required>
            </div>

            <!-- Descripción larga -->
            <div class="grupo-formulario">
                <label>Descripción completa</label>
                <textarea name="descripcion_larga"
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
                          "><?= htmlspecialchars($evento['descripcion_larga'] ?? '') ?></textarea>
            </div>

            <!-- Ubicación -->
            <div class="grupo-formulario">
                <label>Ubicación <span style="color:red;">*</span></label>
                <input type="text" name="ubicacion"
                       value="<?= htmlspecialchars($evento['ubicacion'] ?? '') ?>" required>
            </div>

            <!-- Fecha y hora -->
            <div class="grupo-formulario">
                <label>Fecha y hora <span style="color:red;">*</span></label>
                <input type="datetime-local" name="fechahora"
                       value="<?= date('Y-m-d\TH:i', strtotime($evento['fechahora'])) ?>" required>
            </div>

            <div class="acciones-formulario">
                <a href="perfil_admin.php" class="boton-cancelar">Cancelar</a>
                <button type="submit" name="update_event" class="boton-enviar">
                    Guardar cambios
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