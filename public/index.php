<?php

session_start();

// Cargar rutas
$routes = require_once __DIR__ . '/../routes/web.php';

// Cargar configuración
$config = require_once __DIR__ . '/../app/config/app.php';
$baseUrl = rtrim($config["base_url"], "/");

// Obtener la URL solicitada
$request = strtok($_SERVER["REQUEST_URI"], '?');

// Remover la base_url de la petición
if (strpos($request, $baseUrl) === 0) {
    $request = substr($request, strlen($baseUrl));
    if ($request === false) $request = "/";
}

// Si queda vacío, es raíz
if ($request === "") {
    $request = "/";
}

// PROTEGER RUTAS
require_once __DIR__ . '/../app/helpers/auth.php';
requireLogin($request);

// Buscar controlador
if (isset($routes[$request])) {

    list($controller, $method) = explode("@", $routes[$request]);
    $controllerFile = __DIR__ . '/../app/controllers/' . $controller . '.php';

    if (!file_exists($controllerFile)) {
        die("Controlador no encontrado: $controller");
    }

    require_once $controllerFile;
    $controllerInstance = new $controller();

    if (!method_exists($controllerInstance, $method)) {
        die("Método no encontrado: $method");
    }

    echo $controllerInstance->$method();

} else {
    http_response_code(404);
    echo "<h1>404 - Página no encontrada</h1>";
}
