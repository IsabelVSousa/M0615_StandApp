<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entradaController = new EntradaController();
}

if (isset($_POST["reservar"])) {
    $entradaController->reservar();
}

class EntradaController
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

    public function reservar(): void
    {
        // Solo usuarios standard pueden reservar
        if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'standard') {
            header("Location: ../View/LogIn.php");
            exit;
        }

        $idEvento  = $_POST['idEvento'] ?? '';
        $idPersona = $_SESSION['IDPersona'];

        if (empty($idEvento)) {
            header("Location: ../View/Home.php");
            exit;
        }

        // Comprobar si ya tiene reserva para este evento
        $check = $this->conn->prepare(
            "SELECT IDEntrada FROM entrada WHERE IDPersona = :idPersona AND IDEvento = :idEvento"
        );
        $check->execute([':idPersona' => $idPersona, ':idEvento' => $idEvento]);

        if ($check->fetch()) {
            header("Location: ../View/Event.php?id=$idEvento&error=ya_reservado");
            exit;
        }

        // Crear la reserva
        $idEntrada = uniqid();

        $stmt = $this->conn->prepare(
            "INSERT INTO entrada (IDEntrada, IDPersona, IDEvento, precio)
             VALUES (:idEntrada, :idPersona, :idEvento, :precio)"
        );
        $ok = $stmt->execute([
            ':idEntrada' => $idEntrada,
            ':idPersona' => $idPersona,
            ':idEvento'  => $idEvento,
            ':precio'    => 0
        ]);

        if ($ok) {
            header("Location: ../View/Event.php?id=$idEvento&exito=reservado");
            exit;
        } else {
            header("Location: ../View/Event.php?id=$idEvento&error=error_bd");
            exit;
        }
    }

    public function getMisReservas(string $idPersona): array
    {
        $stmt = $this->conn->prepare(
            "SELECT e.*, ev.descripcion, ev.comediante, ev.fechahora, ev.ubicacion, ev.imagen_evento
             FROM entrada e
             JOIN evento ev ON e.IDEvento = ev.IDEvento
             WHERE e.IDPersona = :idPersona
             ORDER BY ev.fechahora ASC"
        );
        $stmt->execute([':idPersona' => $idPersona]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}