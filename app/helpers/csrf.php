<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function csrf_token() {
    if (!isset($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function verify_csrf($token) {
    if (!isset($_SESSION['_csrf'])) return false;
    return hash_equals($_SESSION['_csrf'], $token);
}
