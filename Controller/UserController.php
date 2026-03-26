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
            
            if ($psw==$fila['contraseña']){
                echo "Bienvenido, " . $fila['nombreApellido'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['password'] = $_POST['password'];
                header("Location: ../View/perfil.html");
                exit;
            } else {
                echo "Usuario incorrecto";
                // header("Location: ../View/perfil.html");
                header("Location: ../View/LogIn.php");
                exit;
            }
        }
    }

    public function lougout(): void
    {
        echo "logout";
    }

    public function register(): void
    {
        echo "register";
    }
}
