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

if (isset($_POST["register_Admin"])) {
    echo "<p>register_Admin click</p>";
    $userController->register_Admin();
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
        $sql = "SELECT IDPERSONA, nombreApellido, email, contraseña, tipo  FROM persona WHERE email = ? ";


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
                $_SESSION['tipo'] = $fila['tipo'];

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
        session_start();
        session_unset();
        session_destroy();

        header("Location: ../View/LogIn.php");
        exit();
    }

    public function register(): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $psw = $_POST['password'] ?? '';
        $psw2 = $_POST['password2'] ?? '';
        $tipo = $_POST['tipo'] ?? 'standard';

        if (empty($nombre) || empty($email) || empty($psw)) {
            header("Location: ../View/register.php?error=campos_vacios");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Con esta linea filtramos el emali para que las personas pongan el formato correcto
            header("Location: ../View/register.php?error=email_invalido");
            exit;
        }

        if (!preg_match('/^6[0-9]{8}$/', $telefono)) {
            header("Location: ../View/register.php?error=telefono_invalido");
            exit;
        }

        // En register.php lineas 95 y 96 sale el pop up de contraseña no existe un validador como el del email 
        if (strlen($psw) < 8 || strlen($psw) > 20) { // Este nos funcina para limitar la clave entre 8 y 20 caracteres 
            header("Location: ../View/register.php?error=password_corta");
            exit;
        }

        if ($psw !== $psw2) { // Con esto validamos que las contraseñas sean iguales
            header("Location: ../View/register.php?error=passwords_no_coinciden");
            exit;
        }


        // $pswHash = password_hash($psw, PASSWORD_DEFAULT);  // Este nos va a servir para ocultar las contraseñas pero debemos ajustar tambien el login

        $telefonoConPrefijo = "+34 " . $telefono;
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
        $sql = "INSERT INTO persona (IDPersona, tipo, nombreApellido, telefono, email, contraseña) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $idPersona, $tipo, $nombreApellido, $telefonoConPrefijo, $email, $psw);

        if ($stmt->execute()) {
            // Imagen de perfil solo para admin (req. 2.5)
            if ($tipo === 'admin' && isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $destino = "../View/img/perfiles/" . $idPersona . "_" . basename($_FILES['imagen']['name']);
                move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
            }

            header("Location: ../View/Home.php?exito=registro_ok");
            exit;
        } else {
            header("Location: ../View/register.php?error=error_bd");
            exit;
        }

    }
    public function register_Admin(): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $nif = trim($_POST['nif'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $psw = $_POST['password'] ?? '';
        $psw2 = $_POST['password2'] ?? '';
        $tipo = $_POST['tipo'] ?? 'admin';

        // INICIO VALIDACIONES
        if (empty($nombre) || empty($email) || empty($psw)) {
            header("Location: ../View/register_Admin.php?error=campos_vacios");
            exit;
        }

        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== 0) {
            header("Location: ../View/register_Admin.php?error=imagen_requerida");
            exit;
        }

        $nif_limpio = strtoupper(trim($nif));
        $esDNI = preg_match('/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/', $nif_limpio);
        $esNIE = preg_match('/^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKE]$/', $nif_limpio);
        $esEmpresa = preg_match('/^[ABCDEFGHJNPQRSUVW][0-9]{7}[0-9A-J]$/', $nif_limpio);

        if (!$esDNI && !$esNIE && !$esEmpresa) {
            header("Location: ../View/register_Admin.php?error=nif_invalido");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../View/register_Admin.php?error=email_invalido");
            exit;
        }

        if (!preg_match('/^6[0-9]{8}$/', $telefono)) {
            header("Location: ../View/register_Admin.php?error=telefono_invalido");
            exit;
        }

        if (strlen($psw) < 8 || strlen($psw) > 20) {
            header("Location: ../View/register_Admin.php?error=password_corta");
            exit;
        }

        if ($psw !== $psw2) {
            header("Location: ../View/register_Admin.php?error=passwords_no_coinciden");
            exit;
        }
        
        // FIN VALIDACIONES

        $telefonoConPrefijo = "+34 " . $telefono;
        $nombreApellido = $nombre . ' ' . $nif_limpio;
        $idPersona = uniqid(); 

        $check = $this->conn->prepare("SELECT IDPersona FROM persona WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            header("Location: ../View/register_Admin.php?error=email_ya_registrado");
            exit;
        }

        $sql = "INSERT INTO persona (IDPersona, tipo, nombreApellido, telefono, email, contraseña) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $idPersona, $tipo, $nombreApellido, $telefonoConPrefijo, $email, $psw);

        if ($stmt->execute()) {

            $carpeta = "../View/img/perfiles/";
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            $destino = $carpeta . $idPersona . "_" . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);

            header("Location: ../View/Home.php?exito=registro_ok");
            exit;
        } else {
            header("Location: ../View/register_Admin.php?error=error_bd");
            exit;
        }
    }

}
