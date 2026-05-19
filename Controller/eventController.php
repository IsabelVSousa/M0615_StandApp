<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventController = new EventController();
}

if (isset($_POST["create_event"])) {
    $eventController->create();
}

if (isset($_POST["update_event"])) {
    $eventController->update();
}

if (isset($_POST["delete_event"])) {
    $eventController->delete();
}


class EventController
{
    private PDO $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host=localhost;dbname=standapp;charset=utf8mb4",
                "root",
                ""
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // CREATE
public function create(): void
{
    if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
        header("Location: ../View/LogIn.php");
        exit;
    }

    $descripcion      = trim($_POST['descripcion'] ?? '');
    $descripcion_larga = trim($_POST['descripcion_larga'] ?? '');
    $comediante       = trim($_POST['comediante'] ?? '');
    $fechahora        = trim($_POST['fechahora'] ?? '');
    $ubicacion        = trim($_POST['ubicacion'] ?? '');
    $idPersona        = $_SESSION['IDPersona'];

    if (empty($descripcion) || empty($comediante) || empty($fechahora) || empty($ubicacion)) {
        header("Location: ../View/event_create.php?error=campos_vacios");
        exit;
    }

    if (strlen($descripcion) > 50) {
        header("Location: ../View/event_create.php?error=descripcion_larga");
        exit;
    }

    // Guardar imagen del evento
    $imagen_evento = null;
    if (isset($_FILES['imagen_evento']) && $_FILES['imagen_evento']['error'] === 0) {
        $carpeta = "../View/img/eventos/";
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $idEvento_temp = uniqid();
        $destino = $carpeta . $idEvento_temp . "_" . basename($_FILES['imagen_evento']['name']);
        move_uploaded_file($_FILES['imagen_evento']['tmp_name'], $destino);
        $imagen_evento = "img/eventos/" . $idEvento_temp . "_" . basename($_FILES['imagen_evento']['name']);
    }

    $idEvento = uniqid();

    $stmt = $this->conn->prepare(
        "INSERT INTO evento (IDEvento, descripcion, descripcion_larga, comediante, fechahora, ubicacion, imagen_evento, IDPersona)
         VALUES (:id, :descripcion, :descripcion_larga, :comediante, :fechahora, :ubicacion, :imagen_evento, :idPersona)"
    );
    $ok = $stmt->execute([
        ':id'               => $idEvento,
        ':descripcion'      => $descripcion,
        ':descripcion_larga'=> $descripcion_larga,
        ':comediante'       => $comediante,
        ':fechahora'        => $fechahora,
        ':ubicacion'        => $ubicacion,
        ':imagen_evento'    => $imagen_evento,
        ':idPersona'        => $idPersona
    ]);

    if ($ok) {
        header("Location: ../View/perfil_admin.php?exito=evento_creado");
        exit;
    } else {
        header("Location: ../View/event_create.php?error=error_bd");
        exit;
    }
}

    // READ — Obtener todos los eventos (devuelve array)
    public function readAll(): array
    {
        $idPersona = $_SESSION['IDPersona'] ?? '';

        $stmt = $this->conn->prepare(
            "SELECT * FROM evento WHERE IDPersona = :id ORDER BY fechahora ASC"
        );
        $stmt->execute([':id' => $idPersona]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAllPublic(): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM evento ORDER BY fechahora ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ — Obtener un evento por ID
    public function readOne(string $idEvento): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM evento WHERE IDEvento = :id"
        );
        $stmt->execute([':id' => $idEvento]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE — Actualizar evento
public function update(): void
{
    if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
        header("Location: ../View/LogIn.php");
        exit;
    }

    $idEvento          = $_POST['idEvento'] ?? '';
    $descripcion       = trim($_POST['descripcion'] ?? '');
    $descripcion_larga = trim($_POST['descripcion_larga'] ?? '');
    $comediante        = trim($_POST['comediante'] ?? '');
    $fechahora         = trim($_POST['fechahora'] ?? '');
    $ubicacion         = trim($_POST['ubicacion'] ?? '');

    if (empty($descripcion) || empty($comediante) || empty($fechahora) || empty($ubicacion)) {
        header("Location: ../View/event_edit.php?id=$idEvento&error=campos_vacios");
        exit;
    }

    if (strlen($descripcion) > 50) {
        header("Location: ../View/event_edit.php?id=$idEvento&error=descripcion_larga");
        exit;
    }

    // Verificar si subió imagen nueva
    if (isset($_FILES['imagen_evento']) && $_FILES['imagen_evento']['error'] === 0) {
        $carpeta = "../View/img/eventos/";
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }
        $nombreArchivo = uniqid() . "_" . basename($_FILES['imagen_evento']['name']);
        move_uploaded_file($_FILES['imagen_evento']['tmp_name'], $carpeta . $nombreArchivo);
        $imagen_evento = "img/eventos/" . $nombreArchivo;

        // UPDATE con nueva imagen
        $stmt = $this->conn->prepare(
            "UPDATE evento SET
                descripcion       = :descripcion,
                descripcion_larga = :descripcion_larga,
                comediante        = :comediante,
                fechahora         = :fechahora,
                ubicacion         = :ubicacion,
                imagen_evento     = :imagen_evento
             WHERE IDEvento = :id"
        );
        $ok = $stmt->execute([
            ':descripcion'       => $descripcion,
            ':descripcion_larga' => $descripcion_larga,
            ':comediante'        => $comediante,
            ':fechahora'         => $fechahora,
            ':ubicacion'         => $ubicacion,
            ':imagen_evento'     => $imagen_evento,
            ':id'                => $idEvento
        ]);
    } else {
        // UPDATE sin cambiar imagen
        $stmt = $this->conn->prepare(
            "UPDATE evento SET
                descripcion       = :descripcion,
                descripcion_larga = :descripcion_larga,
                comediante        = :comediante,
                fechahora         = :fechahora,
                ubicacion         = :ubicacion
             WHERE IDEvento = :id"
        );
        $ok = $stmt->execute([
            ':descripcion'       => $descripcion,
            ':descripcion_larga' => $descripcion_larga,
            ':comediante'        => $comediante,
            ':fechahora'         => $fechahora,
            ':ubicacion'         => $ubicacion,
            ':id'                => $idEvento
        ]);
    }

    if ($ok) {
        header("Location: ../View/perfil_admin.php?exito=evento_actualizado");
        exit;
    } else {
        header("Location: ../View/event_edit.php?id=$idEvento&error=error_bd");
        exit;
    }
}

    // DELETE — Eliminar evento
    public function delete(): void
    {
        // Solo admin puede eliminar
        if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
            header("Location: ../View/LogIn.php");
            exit;
        }

        $idEvento = $_POST['idEvento'] ?? '';

        if (empty($idEvento)) {
            header("Location: ../View/Home.php?error=evento_no_encontrado");
            exit;
        }

        $stmt = $this->conn->prepare(
            "DELETE FROM evento WHERE IDEvento = :id"
        );
        $ok = $stmt->execute([':id' => $idEvento]);

        if ($ok) {
            header("Location: ../View/perfil_admin.php?exito=evento_eliminado");
            exit;
        } else {
            header("Location: ../View/perfil_admin.php?error=error_bd");
            exit;
        }
    }
}
