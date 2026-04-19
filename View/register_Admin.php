<?php
session_start();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
</head>

<body>

    <div class="barra-superior">
        <nav class="navegacion-principal">
            <div class="navegacion-izquierda">
                <div class="logo-circulo">
                    <img src="img/logotipo2_StandApp_Dunia.png" alt="imagen del logo">
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
                <span>Descuentos</span>
                <span>Foro</span>
                <span>Organizadores</span>
            </div>



            <div class="botones-navegacion">
                <a href="LogIn.html" class="boton boton-contorno">Iniciar Sesión</a>
                <a href="register.html" class="boton boton-blanco">Registrarse</a>
            </div>
        </nav>
    </div>
    <section class="contenedor-registro">
        <div class="tarjeta-registro">

            <h1 class="titulo-registro">Registro</h1>
            <?php
            if (isset($_GET['error'])) {
                $errores = [
                    'campos_vacios' => 'Por favor rellena todos los campos.',
                    'email_invalido' => 'El formato del correo no es válido.',
                    'telefono_invalido' => 'El teléfono debe empezar por 6 y tener 9 dígitos.',
                    'nif_invalido' => 'El NIF/NIE/DNI introducido no es válido.',
                    'password_corta' => 'La contraseña debe tener entre 8 y 20 caracteres.',
                    'passwords_no_coinciden' => 'Las contraseñas no coinciden.',
                    'email_ya_registrado' => 'Este correo ya está registrado.',
                    'error_bd' => 'Error al guardar. Inténtalo de nuevo.',
                    'imagen_requerida' => 'Agregue una imagen.',
                ];

                if (array_key_exists($_GET['error'], $errores)) {
                    echo '<p style="color:red;">' . $errores[$_GET['error']] . '</p>';
                }
            }
            ?>
            <div class="layout-registro">

                <div class="foto-perfil">
                    <label for="imagen" class="placeholder-foto">
                        <i class="fas fa-user"></i>
                        <span>Agregar foto</span>
                    </label>
                </div>

                <form class="formulario-registro" action="../Controller/UserController.php" method="post"
                    enctype="multipart/form-data">

                    <input type="hidden" name="tipo" value="admin">
                    <input type="file" id="imagen" name="imagen" accept="image/*" hidden>

                    <div class="grupo-formulario">
                        <label>Nombre</label>
                        <input type="text" name="nombre" placeholder="Nombre">
                    </div>

                    <div class="grupo-formulario">
                        <label>NIF/DNI</label>
                        <input type="text" name="nif" placeholder="nif">
                    </div>

                    <div class="grupo-formulario">
                        <label>Correo electrónico</label>
                        <input type="email" name="email" placeholder="correo@email.com">
                    </div>

                    <div class="grupo-formulario">
                        <label>Teléfono</label>
                        <input type="tel" name="telefono" placeholder="+34 000 00 00 00">
                    </div>

                    <div class="grupo-formulario">
                        <label>Contraseña</label>
                        <input type="password" name="password" placeholder="Mínimo 8 caracteres">
                    </div>

                    <div class="grupo-formulario">
                        <label>Confirmar contraseña</label>
                        <input type="password" name="password2" placeholder="Confirmar contraseña">
                    </div>

                    <div class="acciones-formulario">
                        <button type="button" class="boton-cancelar">Cancelar</button>
                        <button type="submit" name="register_Admin" class="boton-enviar">Registrarse</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
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