<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function requireLogin($request) {
    $public = ["/login","/logout","/","/public/debug_auth.php"];
    
    if (!isset($_SESSION["user_id"]) && !in_array($request, $public)) {
        header("Location: /Repuestos/public/login");
        exit;
    }
}

function requireRole(array $allowedRoles) {
    if (!isset($_SESSION['role'])) {
        header("Location: /Repuestos/public/login"); exit;
    }
    
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        http_response_code(403);
        die("403 - No tienes permiso para ver esta página.");
    }
}
