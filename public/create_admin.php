<?php
require_once __DIR__ . '/../app/config/database.php';

$db = new Database();
$conn = $db->connect();

$username = "admin";
$full_name = "Administrador General";
$email = "admin@example.com";

// Generar hash válido
$password = password_hash("admin123", PASSWORD_DEFAULT);

// role_id = 2 (admin)
// branch_id = 1 (sucursal inicial)
$role_id = 2;
$branch_id = 1;
$is_active = 1;

$stmt = $conn->prepare("
    INSERT INTO users (username, password_hash, full_name, email, role_id, branch_id, is_active)
    VALUES (:username, :password_hash, :full_name, :email, :role_id, :branch_id, :is_active)
");

try {
    $stmt->execute([
        'username' => $username,
        'password_hash' => $password,
        'full_name' => $full_name,
        'email' => $email,
        'role_id' => $role_id,
        'branch_id' => $branch_id,
        'is_active' => $is_active
    ]);

    echo "✅ Usuario admin creado correctamente.<br>";
    echo "Usuario: <strong>admin</strong><br>";
    echo "Contraseña: <strong>admin123</strong><br>";

} catch (PDOException $e) {
    
    if ($e->getCode() == 23000) {
        echo "❌ Error: El usuario 'admin' ya existe.";
    } else {
        echo "❌ Error de Base de Datos: " . $e->getMessage();
    }
}
