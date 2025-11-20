<?php

require_once __DIR__ . '/../config/database.php';

class Dashboard {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Ventas del día
    public function getTodaySales($branch_id) {
        $sql = "
            SELECT SUM(total_amount) AS total
            FROM sales
            WHERE DATE(created_at) = CURDATE()
            AND branch_id = :branch
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":branch", $branch_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)["total"] ?? 0;
    }

    // Ranking vendedores
    public function getSellerRanking($branch_id) {
        $sql = "
            SELECT u.full_name, SUM(s.total_amount) AS total
            FROM sales s
            JOIN users u ON u.id = s.user_id
            WHERE s.branch_id = :branch
            GROUP BY s.user_id
            ORDER BY total DESC
            LIMIT 5
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":branch", $branch_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Stock crítico
    public function getCriticalStock($branch_id) {
        $sql = "
            SELECT p.name, p.sku, i.quantity, i.location
            FROM inventories i
            JOIN products p ON p.id = i.product_id
            WHERE i.branch_id = :branch
            AND i.quantity <= 3
            ORDER BY i.quantity ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":branch", $branch_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Últimas ventas
    public function getRecentSales($branch_id) {
        $sql = "
            SELECT sale_number, total_amount, created_at
            FROM sales
            WHERE branch_id = :branch
            ORDER BY created_at DESC
            LIMIT 5
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":branch", $branch_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Últimas reservas
    public function getRecentReservations($branch_id) {
        $sql = "
            SELECT r.*, p.name AS product_name, b1.name AS from_name, b2.name AS to_name
            FROM product_reservations r
            JOIN products p ON p.id = r.product_id
            JOIN branches b1 ON b1.id = r.from_branch_id
            JOIN branches b2 ON b2.id = r.to_branch_id
            WHERE r.from_branch_id = :branch OR r.to_branch_id = :branch
            ORDER BY r.requested_at DESC
            LIMIT 5
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":branch", $branch_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar productos
    public function countProducts() {
        $sql = "SELECT COUNT(*) AS total FROM products";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)["total"];
    }

    // Contar reservas pendientes
    public function countPendingReservations() {
        $sql = "
            SELECT COUNT(*) AS total 
            FROM product_reservations
            WHERE status = 'PENDING'
        ";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC)["total"];
    }
}
