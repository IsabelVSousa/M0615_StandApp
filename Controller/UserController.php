<?php
session_start();

// si el metodo es post creo el usuario
//porque no es un isset??
// var_dump($_SERVER);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userController = new UserController();
}

// que tipo de post es y derivar a su funcion
if (isset($_POST["login"])) {
    echo "<p>Login click</p>";
    $userController->login();
}

if (isset($_POST["logout"])) {
    echo "<p>Logout click</p>";
    $userController->lougout();
}

if (isset($_POST["register"])) {
    echo "<p>Register click</p>";
    $userController->register();
}


class UserController
{

    //hay que poner aqui algun atributo??
    private $conn;

    //aqui tendria que ir el constructor   
    public function __construct()
    {
        $servername = "localhost";
        $username = "root";

        //falta asignar la password
        $password = "";
        $dbname = "standapp";

        $this->conn = new mysqli("localhost", "root", "", "standapp");

        //dentro del constructor??
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }

        echo "Conexión exitosa";

        $this->conn->set_charset("utf8mb4");
    }

    public function login(): void
    {
        echo "login";

        // o esta otra opcion??
        $email = $_POST['email'];
        $psw = $_POST['password'];
        // necesita que exista las dos cosas para poder enviar el post

        // Preparar consulta, falta password
        $sql = "SELECT IDPERSONA, nombreApellido, email, contraseña  FROM persona WHERE email = ? ";


        $stmt = $this->conn->prepare($sql);

        // Vincular parámetros (tipos: i=integer, s=string, d=double, b=blob)
        $stmt->bind_param("s", $email);

        // Ejecutar
        $stmt->execute();

        // Obtener resultados
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {

            if ($psw == $fila['contraseña']) {
                $_SESSION['usuario_id'] = $fila['IDPERSONA'];

                echo "Bienvenido, " . $fila['nombreApellido'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['password'] = $_POST['password'];
                header("Location: ../View/perfil.php");
                exit;
            } else {
                // echo "Contraseña incorrecta";
                header("Location: ../View/LogIn.php?error=1");
                exit;
            }

        } else {
            echo "El email no está asociado a una cuenta vinculada a esta página web";
            header("Location: ../View/LogIn.php?error=2");
            exit;
        }
    }

    public function lougout(): void
    {

        // Initialize the session.
        // If you are using session_name("something"), don't forget it now!
        session_start();

        // Unset all of the session variables.
        session_unset();
        // $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!

        //hace falta hacer este paso de las cookies??
        // if (ini_get("session.use_cookies")) {
        //     $params = session_get_cookie_params();
        //     setcookie(
        //         session_name(),
        //         '',
        //         time() - 42000,
        //         $params["path"],
        //         $params["domain"],
        //         $params["secure"],
        //         $params["httponly"]
        //     );
        // }

        // Finally, destroy the session.
        session_destroy();

        header("Location: ../View/LogIn.php");
        exit();
    }

    public function register(): void
{
        $nombre   = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $psw      = $_POST['password'] ?? '';
        $psw2     = $_POST['password2'] ?? '';
        $tipo     = $_POST['tipo'] ?? 'standard';

        if (empty($nombre) || empty($email) || empty($psw)) {
            header("Location: ../View/register.php?error=campos_vacios");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Con esta linea filtramos el emali para que las personas pongan el formato correcto
            header("Location: ../View/register.php?error=email_invalido");
            exit;
        }
// En register.php lineas 95 y 96 sale el pop up de contraseña no existe un validador como el del email perro
        if (strlen($psw) < 8 || strlen($psw) > 20) { // Este nos funcina para limitar la clave entre 8 y 20 caracteres 
            header("Location: ../View/register.php?error=password_corta");
            exit;
        }

        if ($psw !== $psw2) { // Con esto validamos que las contraseñas sean iguales
            header("Location: ../View/register.php?error=passwords_no_coinciden");
            exit;
        }

       
        $pswHash = password_hash($psw, PASSWORD_DEFAULT);  // Este nos va a servir para ocultar las contraseñas

// ESTO LO REVISO PORQUE ES DIRECTO CON LA BBDD Y EL XAMMP NO CARGA

        $nombreApellido = $nombre . ' ' . $apellido;
        // IDPersona se genera con uniqid
        $idPersona = uniqid();


        $check = $this->conn->prepare("SELECT IDPersona FROM persona WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            header("Location: ../View/register.php?error=email_ya_registrado");
            exit;
        }

        // Insertar en base de datos
        $sql  = "INSERT INTO persona (IDPersona, tipo, nombreApellido, telefono, email, contraseña) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $idPersona, $tipo, $nombreApellido, $telefono, $email, $pswHash);

        if ($stmt->execute()) {
            // Imagen de perfil solo para admin (req. 2.5)
            if ($tipo === 'admin' && isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $destino = "../View/img/perfiles/" . $idPersona . "_" . basename($_FILES['imagen']['name']);
                move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
            }

            header("Location: ../View/Home.html?exito=registro_ok");
            exit;
        } else {
            header("Location: ../View/register.php?error=error_bd");
            exit;
        }

    }

}
