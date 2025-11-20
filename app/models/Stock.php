<?php

require_once __DIR__ . '/../config/database.php';

class Stock {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Crear una reserva
    public function createReservation($product_id, $from_branch, $to_branch, $qty, $requested_by) {

        $sql = "
            INSERT INTO product_reservations
            (product_id, from_branch_id, to_branch_id, quantity, requested_by)
            VALUES (:product_id, :from_branch, :to_branch, :qty, :requested_by)
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            "product_id" => $product_id,
            "from_branch" => $from_branch,
            "to_branch" => $to_branch,
            "qty" => $qty,
            "requested_by" => $requested_by
        ]);

        return $this->conn->lastInsertId();
    }


    // Cambiar estado de reserva
    public function updateReservationStatus($id, $status, $handled_by) {

        $sql = "
            UPDATE product_reservations
            SET status = :status, handled_by = :handled_by, handled_at = NOW()
            WHERE id = :id
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "status" => $status,
            "handled_by" => $handled_by,
            "id" => $id
        ]);
    }


    // Obtener reservas de una sucursal (por solicitud o por origen)
    public function getReservations($branch_id) {

        $sql = "
            SELECT r.*, p.name AS product_name, b1.name AS from_name, b2.name AS to_name
            FROM product_reservations r
            JOIN products p ON p.id = r.product_id
            JOIN branches b1 ON b1.id = r.from_branch_id
            JOIN branches b2 ON b2.id = r.to_branch_id
            WHERE r.from_branch_id = :branch OR r.to_branch_id = :branch
            ORDER BY r.requested_at DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":branch", $branch_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Actualizar inventario de sucursal
    public function updateInventory($product_id, $branch_id, $qty, $operator = "-") {

        $sql = "
            UPDATE inventories 
            SET quantity = quantity $operator :qty
            WHERE product_id = :product_id AND branch_id = :branch_id
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "qty" => $qty,
            "product_id" => $product_id,
            "branch_id" => $branch_id
        ]);
    }


    // Registrar movimiento
    public function registerMovement($product_id, $branch_id, $type, $qty, $res_id, $user_id) {

        $sql = "
            INSERT INTO stock_movements 
            (product_id, branch_id, movement_type, quantity, reference_table, reference_id, note, created_by)
            VALUES (:product_id, :branch_id, :type, :qty, 'product_reservations', :ref, 'Reserva de stock', :user_id)
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            "product_id" => $product_id,
            "branch_id" => $branch_id,
            "type" => $type,
            "qty" => $qty,
            "ref" => $res_id,
            "user_id" => $user_id
        ]);
    }

    public function approveReservationTransaction($res_id, $product_id, $qty, $from_branch, $to_branch, $user_id) {
        try {
            $this->conn->beginTransaction();

            // 1. Descontar de Origen
            $this->updateInventory($product_id, $from_branch, $qty, "-");
            $this->registerMovement($product_id, $from_branch, "TRANSFER_OUT", $qty, $res_id, $user_id);

            // 2. Aumentar en Destino (Usamos ON DUPLICATE KEY UPDATE por si no existe)
            $sqlInsert = "INSERT INTO inventories (product_id, branch_id, quantity, location) 
                          VALUES (:pid, :bid, :qty, 'Recepción') 
                          ON DUPLICATE KEY UPDATE quantity = quantity + :qty";
            $stmt = $this->conn->prepare($sqlInsert);
            $stmt->execute(['pid'=>$product_id, 'bid'=>$to_branch, 'qty'=>$qty]);
            
            $this->registerMovement($product_id, $to_branch, "TRANSFER_IN", $qty, $res_id, $user_id);

            // 3. Actualizar Estado
            $this->updateReservationStatus($res_id, "COMPLETED", $user_id);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}

