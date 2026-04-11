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
                echo "Contraseña incorrecta";
                header("Location: ../View/LogIn.php");
                exit;
            }

            //si el email no esta asociado a la bbdd 
        } else {
            echo "El email no está asociado a una cuenta vinculada a esta página web";
            header("Location: ../View/LogIn.php");
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
        echo "register";
    }
}
