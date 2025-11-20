<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/User.php';

class AuthController
{

    public function login()
    {

        if (isset($_SESSION['user_id'])) {
            header("Location: /Repuestos/public/dashboard");
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($username === '' || $password === '') {
                $error = "Completa usuario y contraseña.";
            } else {

                $userModel = new User();
                $user = $userModel->findByUsername($username);

                if (!$user) {
                    $error = "Usuario no encontrado.";
                } else {

                    if ($user['is_active'] == 0) {
                        $error = "Usuario inactivo.";
                    } elseif (password_verify($password, $user['password_hash'])) {
                        // Crear sesión

                        $_SESSION["user_id"] = $user["id"];
                        $_SESSION["username"] = $user["username"];
                        $_SESSION["role"] = $user["role_id"];

                        // ESTA ES LA CLAVE
                        $_SESSION["branch_id"] = $user["branch_id"];

                        header("Location: /Repuestos/public/dashboard");
                        exit;
                    } else {
                        $error = "Usuario o contraseña incorrectos.";
                    }
                }
            }
        }

        ob_start();
        include __DIR__ . '/../../views/auth/login.php';
        return ob_get_clean();
    }


    public function logout()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        header("Location: /Repuestos/public/login");
        exit;
    }
}
