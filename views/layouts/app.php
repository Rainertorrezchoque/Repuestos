<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? "Panel" ?> - Repuestos</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }

        /* --- SIDEBAR --- */
        #sidebar {
            width: 240px;
            height: 100vh;
            background: #1f2937;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 70px;
        }

        #sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            font-size: 15px;
        }

        #sidebar a:hover {
            background: #374151;
            color: #fff;
        }

        /* --- CONTENT AREA --- */
        #content {
            margin-left: 250px;
            padding: 25px;
        }

        /* --- TOP BAR --- */
        #topbar {
            height: 60px;
            width: 100%;
            background: #111827;
            color: white;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-title {
            color: #9ca3af;
            font-size: 13px;
            padding-left: 20px;
            margin-top: 10px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

<!-- TOPBAR -->
<div id="topbar">
    <div><strong>REPUESTOS</strong> | Panel</div>
    <div>
        <span><?= $_SESSION["username"] ?> (Rol: <?= $_SESSION["role"] ?>)</span>
        <a href="/Repuestos/public/logout" class="btn btn-sm btn-danger ms-3">Salir</a>
    </div>
</div>

<!-- SIDEBAR -->
<div id="sidebar">

    <div class="menu-title">General</div>
    <a href="/Repuestos/public/dashboard">📊 Dashboard</a>

    <div class="menu-title">Inventario</div>
    <a href="/Repuestos/public/productos">📦 Productos</a>
    <a href="/Repuestos/public/stock">🔄 Reservas</a>
    <a href="/Repuestos/public/ventas">🧾 Ventas</a>

    <div class="menu-title">Administración</div>
    <a href="/Repuestos/public/usuarios">👥 Usuarios</a>
    <a href="/Repuestos/public/roles">🛡 Roles</a>

    <div class="menu-title">Reportes</div>
    <a href="/Repuestos/public/reportes">📑 Reportes PDF / Excel</a>

</div>

<!-- CONTENIDO DINÁMICO -->
<div id="content">
    <?= $content ?>
</div>

</body>
</html>
