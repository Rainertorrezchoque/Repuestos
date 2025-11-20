<?php
// debug_auth.php - eliminar luego
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/User.php';

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("No hay conexión a la BD.");
}

// Probar consulta directa
try {
    $stmt = $conn->prepare("SELECT id, username, password_hash, is_active FROM users WHERE username = :u LIMIT 1");
    $u = 'admin';
    $stmt->bindParam(':u', $u);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<pre>Resultado SELECT:\n";
    var_export($row);
    echo "</pre>";

    if ($row) {
        $pw = 'admin123';
        echo "<p>Probando password_verify('admin123', password_hash): ";
        echo password_verify($pw, $row['password_hash']) ? "<strong>VERIFICA ✔</strong>" : "<strong>NO VERIFICA ✖</strong>";
        echo "</p>";
    } else {
        echo "<p>No se encontró el usuario 'admin'.</p>";
    }

} catch (Exception $e) {
    echo "Error en consulta: " . $e->getMessage();
}
