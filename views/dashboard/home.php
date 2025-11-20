<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

    <h2 class="mb-4">Dashboard Principal</h2>

    <!-- CARDS -->
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Ventas del día</h6>
                <h3>Bs. <?= number_format($today_sales, 2) ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Productos registrados</h6>
                <h3><?= $total_products ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Reservas pendientes</h6>
                <h3><?= $pending_reservations ?></h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow">
                <h6>Sucursal actual</h6>
                <h4><?= $_SESSION["branch_id"] ?></h4>
            </div>
        </div>
    </div>

    <!-- RANKING -->
    <div class="card p-3 mb-4 shadow">
        <h4>Ranking de vendedores</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Vendedor</th>
                    <th>Total Vendido</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($ranking as $r): ?>
                <tr>
                    <td><?= $r["full_name"] ?></td>
                    <td>Bs. <?= number_format($r["total"], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- STOCK CRÍTICO -->
    <div class="card p-3 mb-4 shadow">
        <h4>Stock crítico</h4>

        <?php if (count($critical_stock) == 0): ?>
            <div class="text-muted">No hay productos en stock crítico.</div>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Stock</th>
                        <th>Ubicación</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($critical_stock as $c): ?>
                    <tr>
                        <td><?= $c["name"] ?></td>
                        <td><?= $c["sku"] ?></td>
                        <td><?= $c["quantity"] ?></td>
                        <td><?= $c["location"] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- ÚLTIMAS VENTAS -->
    <div class="card p-3 mb-4 shadow">
        <h4>Últimas ventas</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($recent_sales as $s): ?>
                <tr>
                    <td><?= $s["sale_number"] ?></td>
                    <td>Bs. <?= number_format($s["total_amount"], 2) ?></td>
                    <td><?= $s["created_at"] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- RESERVAS RECIENTES -->
    <div class="card p-3 mb-4 shadow">
        <h4>Últimas reservas</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>De</th>
                    <th>Para</th>
                    <th>Cant.</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($recent_reservations as $r): ?>
                <tr>
                    <td><?= $r["id"] ?></td>
                    <td><?= $r["product_name"] ?></td>
                    <td><?= $r["from_name"] ?></td>
                    <td><?= $r["to_name"] ?></td>
                    <td><?= $r["quantity"] ?></td>
                    <td><?= $r["status"] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
