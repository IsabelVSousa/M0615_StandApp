<?php
session_start();

// si el metodo es post creo el usuario
//porque no es un isset??
if ($_SESSION["REQUEST_METHOD"] == "POST") {
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
        $dbname = "MF_StandApp";

        $conexion = new mysqli("localhost", "root", "", "MF_StandApp");

        //dentro del constructor??
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        echo "Conexión exitosa";

        $conexion->set_charset("utf8mb4");
    }

    public function login(): void
    {
        echo "login";
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
