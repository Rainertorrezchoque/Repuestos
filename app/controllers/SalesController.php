<?php

require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/auth.php';

class SalesController {

    public function index() {
        // Verificar sesión
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/ventas'); 

        
        ob_start();
        include __DIR__ . '/../../views/sales/pos.php';
        $content = ob_get_clean();

        
        $title = "Punto de Venta";
        include __DIR__ . '/../../views/layouts/app.php';
    }

    public function create() {

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /Repuestos/public/ventas");
            exit;
        }

        session_start();

        $items = $_POST["items"]; 
        $payment_method = $_POST["payment_method"];

        $branch_id = $_SESSION["branch_id"] ?? 1;
        $user_id = $_SESSION["user_id"];

        $total = 0;

        foreach ($items as $item) {
            $total += $item["qty"] * $item["price"];
        }

        // INSTANCIAS
        $saleModel = new Sale();
        $db = new Database();
        $conn = $db->connect();

        try {
            $conn->beginTransaction();

            
            $sale_id = $saleModel->createSale($branch_id, $user_id, $total, $payment_method);

            
            foreach ($items as $item) {
                $saleModel->addItem(
                    $sale_id,
                    $item["product_id"],
                    $item["qty"],
                    $item["price"],
                    $item["cost"]
                );

                $saleModel->updateInventory(
                    $item["product_id"],
                    $branch_id,
                    $item["qty"]
                );

                $saleModel->registerMovement(
                    $item["product_id"],
                    $branch_id,
                    $item["qty"],
                    $sale_id,
                    $user_id
                );
            }

            $conn->commit();

            $_SESSION["sale_success"] = $sale_id;

            header("Location: /Repuestos/public/ventas?ok=" . $sale_id);
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            die("Error en la venta: " . $e->getMessage());
        }
    }
}
