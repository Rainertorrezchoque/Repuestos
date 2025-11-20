<?php

require_once __DIR__ . '/../config/database.php';

class Sale {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    
    public function createSale($branch_id, $user_id, $total_amount, $payment_method) {

        $sql = "
            INSERT INTO sales (sale_number, branch_id, user_id, total_amount, payment_method)
            VALUES (:sale_number, :branch_id, :user_id, :total_amount, :payment_method)
        ";

        $sale_number = "V-" . time();

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "sale_number" => $sale_number,
            "branch_id" => $branch_id,
            "user_id" => $user_id,
            "total_amount" => $total_amount,
            "payment_method" => $payment_method
        ]);

        return $this->conn->lastInsertId();
    }

    
    public function addItem($sale_id, $product_id, $quantity, $price, $cost) {

        $sql = "
            INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, unit_cost, subtotal)
            VALUES (:sale_id, :product_id, :quantity, :unit_price, :unit_cost, :subtotal)
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "sale_id" => $sale_id,
            "product_id" => $product_id,
            "quantity" => $quantity,
            "unit_price" => $price,
            "unit_cost" => $cost,
            "subtotal" => $quantity * $price
        ]);
    }

    
    public function updateInventory($product_id, $branch_id, $quantity) {

        $sql = "
            UPDATE inventories SET quantity = quantity - :qty
            WHERE product_id = :product_id AND branch_id = :branch_id
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "qty" => $quantity,
            "product_id" => $product_id,
            "branch_id" => $branch_id
        ]);
    }

    
    public function registerMovement($product_id, $branch_id, $qty, $sale_id, $user_id) {

        $sql = "
            INSERT INTO stock_movements 
            (product_id, branch_id, movement_type, quantity, reference_table, reference_id, note, created_by)
            VALUES (:product_id, :branch_id, 'OUT', :qty, 'sales', :sale_id, 'Venta realizada', :created_by)
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "product_id" => $product_id,
            "branch_id" => $branch_id,
            "qty" => $qty,
            "sale_id" => $sale_id,
            "created_by" => $user_id
        ]);
    }

    // 1. Total vendido HOY (Caja del Día)
    public function getDailyTotal() {
        
        $sql = "SELECT SUM(total_amount) as total FROM sales WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // 2. Ranking de Empleados (Ranking de Vagos/Efectivos)
    public function getEmployeeRanking() {
        
        $sql = "SELECT u.full_name, COUNT(s.id) as sales_count, SUM(s.total_amount) as total_money
                FROM sales s
                JOIN users u ON s.user_id = u.id
                WHERE MONTH(s.created_at) = MONTH(CURRENT_DATE())
                GROUP BY s.user_id
                ORDER BY total_money DESC
                LIMIT 5";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
