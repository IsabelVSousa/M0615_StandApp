<?php
session_start();

//Crear array de mensajes predeterminados
$mensajes = [
    "1" => "Contraseña incorrecta.",
    "2" => "El email no existe.",
    // "campos_vacios" => "Rellena todos los campos."
];

$mensajesExito = [
    "perfileliminado" => "El perfil ha sido eliminado correctamente."
];
// 2. Miramos si hay un error en la URL
// si existe el error recogelo, sino string vacio
$error = $_GET['error'] ?? '';
$exito = $_GET['exito'] ?? '';

// 3. Si el error existe en nuestro diccionario, preparamos el texto
$mensajeError = $mensajes[$error] ?? '';
$mensajeExito = $mensajesExito[$exito] ?? '';

if ($mensajeError): ?>
    <div style="
        background-color: var(--bg-dark) !important;
        color: var(--text-white) !important;
        font-family: 'Outfit', sans-serif !important;
        display: flex !important;
        align-items: center !important;
        padding: 12px 16px !important;
        margin-bottom: 20px !important;
        border-radius: 12px !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3) !important;
        width: 100% !important;
        box-sizing: border-box !important;
    ">
        <span style="
            background-color: var(--primary-orange) !important;
            color: white !important;
            width: 22px !important;
            height: 22px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 50% !important;
            margin-right: 12px !important;
            font-weight: 900 !important;
            font-size: 14px !important;
            flex-shrink: 0 !important;
            ">!</span>
        <span style="font-size: 0.95rem !important; font-weight: 500 !important;">
            <?php echo $mensajeError; ?>
        </span>
    </div>

    <!-- // Cierra el condicional. 
    //Si $mensajeError estaba vacío, todo el bloque de la caja (el div) se ignora por completo y no aparece en la página. -->
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="LogIn.css">
</head>

<body>
    <div class="page">
        <header>
            <h1>Bienvenido a Stand App</h1>
            <p>Regístrate o inicia sesión para descubrir shows de comedia, reservar tus entradas y no perderte ningún
                show.
            </p>
        </header>
        <main>
            <div>
                <section>
                    <h2>Inicia sesión para ver más</h2>
                    <?php if ($mensajeExito): ?>
                        <p style="color: green; font-weight: 700; margin-bottom: 1rem;">
                            <?php echo $mensajeExito; ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($mensajeError): ?>
                        <p style="color: red; font-weight: 700; margin-bottom: 1rem;">
                            <?php echo $mensajeError; ?>
                        </p>
                    <?php endif; ?>
                    <form action="../Controller/UserController.php" method="post">
                        <div class="g_form">
                            <label for="email">Correo electrónico</label>
                            <input type="text" id="email" name="email" placeholder="ejemplo@gmail.com">
                        </div>
                        <div class="g_form">
                            <label for="password">Contraseña</label>
                            <input type="text" id="password" name="password" placeholder="contraseña">
                        </div>
                        <button type="submit" name="login">Iniciar sesión</button>
                        <!-- <input type="submit" value="Iniciar sesión"> -->
                    </form>
                </section>
            </div>
            <section class="g_enlaces">
                <!-- Añadimos el recuperar contraseña aunque no esté habilitado aún -->
                <a href="#">¿Olvidaste tu contraseña?</a>
                <p>O</p>
            </section>
            <div class="g_enlaces">
                <section class="enlaces">
                    <p>¿Aún no tienes cuenta en Stand App?</p>
                    <a href="register.php">Regístrate</a>
                </section>
                <section class="enlaces">
                    <p>¿Eres organizador de stand ups de comedia?</p>
                    <a href="register_Admin.php">Regístrate</a>
                </section>
                <!-- <p>Si continúas, aceptas los Términos del servicio de Stand App y confirmas que has leído nuestra Política
                de
                privacidad. Aviso de recopilación de datos.</p> -->
            </div>
        </main>
    </div>
    <footer>
        <p>© 2026 Proyecto Stand App</p>
    </footer>
</body>

</html>