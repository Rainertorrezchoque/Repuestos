<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/csrf.php';

class ProductController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/productos');
        $model = new Product();
        $products = $model->all(200);
        
        ob_start();
        include __DIR__ . '/../../views/products/list.php';
        $content = ob_get_clean();
        
        $title = "Productos";
        include __DIR__ . '/../../views/layouts/app.php';
    }

    public function create()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/productos/create');

        csrf_token();

        $p = new Product();
        $branches = $p->getBranches(); 
        $categories = $p->getCategories(); 

        $title = "Nuevo Producto";
        ob_start();
        include __DIR__ . '/../../views/products/form.php';
        $content = ob_get_clean();
        
        include __DIR__ . '/../../views/layouts/app.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') header("Location: /Repuestos/public/productos");
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/productos');
        
        require_once __DIR__ . '/../helpers/csrf.php';
        if (!verify_csrf($_POST['_csrf'] ?? '')) die('CSRF inválido');

        $p = new Product();
        
        // datos producto
        $data = [
            'sku' => $_POST['sku'],
            'name' => $_POST['name'],
            'desc' => $_POST['description'],
            'cat' => $_POST['category_id'] ?: null,
            'brand' => $_POST['brand'],
            'cost' => $_POST['cost'],
            'price' => $_POST['price'],
            'min' => $_POST['min_stock'],
            'active' => isset($_POST['is_active']) ? 1 : 0,
            'visible' => isset($_POST['visible_public']) ? 1 : 0
        ];

        
        $inventoryData = [
            'branch_id' => $_POST['init_branch_id'] ?? null,
            'quantity'  => $_POST['init_quantity'] ?? 0,
            'location'  => $_POST['init_location'] ?? ''
        ];

        
        $ok = $p->create($data, $inventoryData);
        
        header("Location: /Repuestos/public/productos?msg=" . ($ok ? 'Producto creado con stock inicial' : 'Error al crear'));
    }

    public function edit()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/productos/edit');
        
        csrf_token();

        $id = (int)($_GET['id'] ?? 0);
        $p = new Product();
        $product = $p->find($id);
        $categories = $p->getCategories(); 
        
        ob_start();
        include __DIR__ . '/../../views/products/form.php';
        $content = ob_get_clean();
        
        $title = "Editar Producto";
        include __DIR__ . '/../../views/layouts/app.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') header("Location: /Repuestos/public/productos");
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/productos');
        
        if (!verify_csrf($_POST['_csrf'] ?? '')) die('CSRF inválido');
        
        $id = (int)$_POST['id'];
        $p = new Product();
        
        $data = [
            'sku' => $_POST['sku'],
            'name' => $_POST['name'],
            'desc' => $_POST['description'],
            'cat' => $_POST['category_id'] ?: null,
            'brand' => $_POST['brand'],
            'cost' => $_POST['cost'],
            'price' => $_POST['price'],
            'min' => $_POST['min_stock'],
            'active' => isset($_POST['is_active']) ? 1 : 0,
            'visible' => isset($_POST['visible_public']) ? 1 : 0
        ];
        
        $ok = $p->update($id, $data);
        header("Location: /Repuestos/public/productos?msg=" . ($ok ? 'Producto actualizado' : 'Error'));
    }

    public function delete()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/productos');
        
        $id = (int)($_POST['id'] ?? 0);
        if (!verify_csrf($_POST['_csrf'] ?? '')) die('CSRF inválido');
        
        $p = new Product();
        $p->delete($id);
        header("Location: /Repuestos/public/productos?msg=Producto eliminado");
    }

    public function search()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        requireLogin('/productos');

        $query = isset($_GET["q"]) ? trim($_GET["q"]) : "";

        $results = [];

        if ($query !== "") {
            $product = new Product();
            $results = $product->search($query);
        }

        ob_start();
        include __DIR__ . '/../../views/products/search.php';
        $content = ob_get_clean();
        $title = "Buscar Productos";

        include __DIR__ . '/../../views/layouts/app.php';
    }

    public function searchJson()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        
        header('Content-Type: application/json');

        $query = isset($_GET["q"]) ? trim($_GET["q"]) : "";
        
        if ($query === "") {
            echo json_encode([]);
            return;
        }

        $p = new Product();
        $results = $p->search($query);
        
        echo json_encode($results);
        exit; 
    }
}