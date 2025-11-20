<?php

require_once __DIR__ . '/../config/database.php';

class User {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([ 'username' => $username ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([ 'id' => $id ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Listar usuarios con nombres de Rol y Sucursal
    public function all() {
        $sql = "SELECT u.*, r.name as role_name, b.name as branch_name 
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                LEFT JOIN branches b ON u.branch_id = b.id
                ORDER BY u.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO users (full_name, username, email, password_hash, role_id, branch_id, is_active) 
                VALUES (:name, :user, :email, :pass, :role, :branch, :active)";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $data['full_name'],
            ':user' => $data['username'],
            ':email' => $data['email'],
            ':pass' => $data['password'], // Ya debe venir hasheada
            ':role' => $data['role_id'],
            ':branch' => $data['branch_id'],
            ':active' => $data['is_active']
        ]);
    }

    public function update($id, $data) {
        // Construcción dinámica por si no cambiamos la contraseña
        $sql = "UPDATE users SET full_name=:name, username=:user, email=:email, role_id=:role, branch_id=:branch, is_active=:active";
        
        // Si viene password nueva, la agregamos al SQL
        if (!empty($data['password'])) {
            $sql .= ", password_hash=:pass";
        }
        
        $sql .= " WHERE id=:id";

        $params = [
            ':name' => $data['full_name'],
            ':user' => $data['username'],
            ':email' => $data['email'],
            ':role' => $data['role_id'],
            ':branch' => $data['branch_id'],
            ':active' => $data['is_active'],
            ':id' => $id
        ];

        if (!empty($data['password'])) {
            $params[':pass'] = $data['password'];
        }

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Helpers para los Selects
    public function getRoles() {
        $stmt = $this->conn->query("SELECT * FROM roles");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBranches() {
        $stmt = $this->conn->query("SELECT id, name FROM branches WHERE is_active = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}