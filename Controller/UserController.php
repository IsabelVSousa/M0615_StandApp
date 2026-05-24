<?php
session_start();

// si el metodo es post creo el usuario
//porque no es un isset??
// var_dump($_SERVER);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userController = new UserController();


    // que tipo de post es y derivar a su funcion
    if (isset($_POST["login"])) {
        // echo "<p>Login click</p>";
        $userController->login();
    }

    if (isset($_POST["logout"])) {
        echo "<p>Logout click</p>";
        $userController->lougout();
    }

    if (isset($_POST["updateProfile"])) {
        //echo "<p>Update click</p>";
        $userController->updateProfile();
    }

    if (isset($_POST["register"])) {
        echo "<p>Register click</p>";
        $userController->register();
    }

    if (isset($_POST["register_Admin"])) {
        echo "<p>register_Admin click</p>";
        $userController->register_Admin();
    }

    if (isset($_POST["deleteProfile"])) {
        // echo "<p>Delete click</p>";
        $userController->deleteProfile();
    }
}

class UserController
{

    //hay que poner aqui algun atributo??
    private PDO $conn;

    //aqui tendria que ir el constructor   
    public function __construct()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "standapp";

        $dbport = 3306;

        try {

            $this->conn = new PDO(
                "mysql:host={$servername};port={$dbport};dbname={$dbname};charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_TIMEOUT => 5
                ]
            );
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }


    public function login(): void
    {
        echo "login";

        // o esta otra opcion??
        $email = $_POST['email'];
        $psw = $_POST['password'];
        //$pswHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // necesita que exista las dos cosas para poder enviar el post

        // Preparar consulta, falta password
        $sql = "SELECT IDPERSONA, nombreApellido, email, contraseña, tipo, imagen  FROM persona WHERE email = ? ";


        $stmt = $this->conn->prepare($sql);

        $stmt->execute([$email]);

        if ($fila = $stmt->fetch()) {

            if (password_verify($psw, $fila['contraseña'])) {
                $_SESSION['IDPersona'] = $fila['IDPERSONA'];
                $_SESSION['tipo'] = $fila['tipo'];
                $_SESSION['nombre'] = $fila['nombreApellido'];
                $_SESSION['foto']      = $fila['imagen'] ?? '';

                echo "Bienvenido, " . $fila['nombreApellido'];
                $_SESSION['email'] = $_POST['email'];
                //$_SESSION['password'] = $_POST['password'];
                if ($fila['tipo'] === 'admin') {
                    header("Location: ../View/perfil_admin.php");
                } else {
                    header("Location: ../View/perfil.php");
                }
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

    public function updateProfile(): void
    {
        $idPersona = $_SESSION['IDPersona'];
        $tipo      = $_SESSION['tipo'];
        $email = $_SESSION['email'];
        $name = trim($_POST['nombre'] ?? '');
        $psw = $_POST['password'] ?? '';
        $psw2 = $_POST['password2'] ?? '';

        $paginaPerfil = ($tipo === 'admin') ? 'perfil_Admin.php' : 'perfil.php';
        error_log("paginaPerfil: " . $paginaPerfil);
        error_log("tipo session: " . $tipo);

        $campos = [];
        $params = [':id' => $idPersona];

        if (!empty($name)) {
            $campos[] = 'nombreApellido = :name';
            $params[':name'] = $name;
            $_SESSION['nombre'] = $name;
        }

        if (!empty($psw)) {
            if (strlen($psw) < 8 || strlen($psw) > 20) {
                header("Location: ../View/{$paginaPerfil}?error=password_corta");
                exit;
            }

            $campos[] = 'contraseña = :psw';
            $params[':psw'] = password_hash($psw, PASSWORD_DEFAULT);
        }

        //RESPECTO A LA FOTO DE PERFIL
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $carpeta = "../View/img/perfiles/";
            if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);

            $nombreArchivo = $idPersona . '_' . time() . '_' . basename($_FILES['imagen']['name']);

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta . $nombreArchivo)) {
                $campos[] = 'imagen = :imagen';
                $params[':imagen'] = 'img/perfiles/' . $nombreArchivo;
                $_SESSION['foto'] = 'img/perfiles/' . $nombreArchivo;
            }
        }

        if (empty($campos)) {
            header("Location: ../View/{$paginaPerfil}");
            exit;
        }

        try {
            $sql  = "UPDATE persona SET " . implode(', ', $campos) . " WHERE IDPersona = :id";
            $stmt = $this->conn->prepare($sql);

            $stmt->execute($params);

            header("Location: ../View/{$paginaPerfil}?updated=1");

            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
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


        $pswHash = password_hash($psw, PASSWORD_DEFAULT);
        // Este nos va a servir para ocultar las contraseñas pero debemos ajustar tambien el login

        $telefonoConPrefijo = "+34 " . $telefono;
        // ESTO LO REVISO PORQUE ES DIRECTO CON LA BBDD Y EL XAMMP NO CARGA

        $nombreApellido = $nombre . ' ' . $apellido;
        // IDPersona se genera con uniqid
        $idPersona = uniqid();


        $check = $this->conn->prepare("SELECT IDPersona FROM persona WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetchColumn()) {
            header("Location: ../View/register.php?error=email_ya_registrado");
            exit;
        }

        // Insertar en base de datos
        $sql = "INSERT INTO persona (IDPersona, tipo, nombreApellido, telefono, email, contraseña) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt->execute([$idPersona, $tipo, $nombreApellido, $telefonoConPrefijo, $email, $pswHash])) {
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

        $pswHash = password_hash($psw, PASSWORD_DEFAULT);

        // FIN VALIDACIONES

        $telefonoConPrefijo = "+34 " . $telefono;
        $nombreApellido = $nombre . ' ' . $nif_limpio;
        $idPersona = uniqid();

        $check = $this->conn->prepare("SELECT IDPersona FROM persona WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetchColumn()) {
            header("Location: ../View/register_Admin.php?error=email_ya_registrado");
            exit;
        }

        $carpeta = "../View/img/perfiles/";
        if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);


        $nombreArchivo = $idPersona . "_" . basename($_FILES['imagen']['name']);
        $destino = $carpeta . $nombreArchivo;
        move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);

        $sql = "INSERT INTO persona (IDPersona, tipo, nombreApellido, telefono, email, contraseña, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt->execute([$idPersona, $tipo, $nombreApellido, $telefonoConPrefijo, $email, $pswHash, 'img/perfiles/' . $nombreArchivo])) {



            header("Location: ../View/Home.php?exito=registro_ok");
            exit;
        } else {
            header("Location: ../View/register_Admin.php?error=error_bd");
            exit;
        }
    }

    public function deleteProfile(): void
    {
        if (!isset($_SESSION['IDPersona'], $_SESSION['tipo'])) {
            header("Location: ../View/LogIn.php");
            exit;
        }

        $idPersona = $_SESSION['IDPersona'];
        $tipo = $_SESSION['tipo'];

        try {
            $this->conn->beginTransaction();

            if ($tipo === 'admin') {
                $stmtEventos = $this->conn->prepare("
                DELETE FROM evento
                WHERE IDPersona = :idPersona
            ");
                $stmtEventos->execute(['idPersona' => $idPersona]);
            }

            $stmtEntradas = $this->conn->prepare("
            DELETE FROM entrada
            WHERE IDPersona = :idPersona
        ");
            $stmtEntradas->execute(['idPersona' => $idPersona]);

            $stmtPersona = $this->conn->prepare("
            DELETE FROM persona
            WHERE IDPersona = :idPersona
        ");
            $stmtPersona->execute(['idPersona' => $idPersona]);

            $this->conn->commit();

            session_unset();
            session_destroy();

            header("Location: ../View/LogIn.php?exito=perfileliminado");
            exit;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            header("Location: ../View/LogIn.php?error=errorbd");
            exit;
        }
    }
}
