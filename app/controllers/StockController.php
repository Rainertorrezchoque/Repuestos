<?php
require_once __DIR__ . '/../models/Stock.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/auth.php'; 

class StockController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        
        if (!isset($_SESSION["branch_id"])) {
            header("Location: /Repuestos/public/login");
            exit;
        }

        $branch_id = $_SESSION["branch_id"];
        $model = new Stock();
        $reservations = $model->getReservations($branch_id);

        
        $title = "Reservas y Transferencias";
        ob_start();
        include __DIR__ . '/../../views/stock/index.php';
        $content = ob_get_clean();
        
        include __DIR__ . '/../../views/layouts/app.php';
    }

    public function reserve()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user_id = $_SESSION["user_id"];
        $from_branch = $_POST["from_branch"];
        $to_branch = $_SESSION["branch_id"];
        $product_id = $_POST["product_id"];
        $qty = $_POST["quantity"];

        $model = new Stock();
        $model->createReservation($product_id, $from_branch, $to_branch, $qty, $user_id);

        header("Location: /Repuestos/public/stock?msg=Solicitud creada");
    }

    public function approve()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user_id = $_SESSION["user_id"];
        
        // Recibir datos
        $reservation_id = $_POST["reservation_id"];
        $product_id = $_POST["product_id"];
        $qty = $_POST["quantity"];
        $from_branch = $_POST["from_branch"];
        $to_branch = $_POST["to_branch"];

        $model = new Stock();
        
        
        $success = $model->approveReservationTransaction($reservation_id, $product_id, $qty, $from_branch, $to_branch, $user_id);

        if ($success) {
            header("Location: /Repuestos/public/stock?msg=Transferencia completada");
        } else {
            die("Error al procesar la transacción.");
        }
    }

    public function reject()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user_id = $_SESSION["user_id"];
        $reservation_id = $_POST["reservation_id"];

        $model = new Stock();
        $model->updateReservationStatus($reservation_id, "CANCELLED", $user_id);

        header("Location: /Repuestos/public/stock?msg=Solicitud rechazada");
    }
}