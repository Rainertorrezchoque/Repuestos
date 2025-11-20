<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';

class UserController {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/usuarios');
        
        $model = new User();
        $users = $model->all();

        $title = "Gestión de Usuarios";
        ob_start();
        include __DIR__ . '/../../views/users/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/app.php';
    }

    public function create() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/usuarios/crear');
        csrf_token();

        $model = new User();
        $roles = $model->getRoles();
        $branches = $model->getBranches();

        $title = "Nuevo Usuario";
        ob_start();
        include __DIR__ . '/../../views/users/form.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/app.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') header("Location: /Repuestos/public/usuarios");
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/usuarios');
        
        if (!verify_csrf($_POST['_csrf'] ?? '')) die('CSRF inválido');

        $data = [
            'full_name' => $_POST['full_name'],
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_BCRYPT), 
            'role_id' => $_POST['role_id'],
            'branch_id' => $_POST['branch_id'] ?: null,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        $model = new User();
        $model->create($data);
        header("Location: /Repuestos/public/usuarios?msg=Usuario creado");
    }

    public function edit() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/usuarios');
        csrf_token();

        $id = $_GET['id'];
        $model = new User();
        $user = $model->find($id);
        $roles = $model->getRoles();
        $branches = $model->getBranches();

        $title = "Editar Usuario";
        ob_start();
        include __DIR__ . '/../../views/users/form.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/app.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') header("Location: /Repuestos/public/usuarios");
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/usuarios');
        if (!verify_csrf($_POST['_csrf'] ?? '')) die('CSRF inválido');

        $id = $_POST['id'];
        $data = [
            'full_name' => $_POST['full_name'],
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'role_id' => $_POST['role_id'],
            'branch_id' => $_POST['branch_id'] ?: null,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'password' => !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null
        ];

        $model = new User();
        $model->update($id, $data);
        header("Location: /Repuestos/public/usuarios?msg=Usuario actualizado");
    }
}