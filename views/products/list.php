<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<br></br>
<body class="bg-light">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Listado de Productos</h2>
    <a href="/Repuestos/public/productos/create" class="btn btn-primary">Nuevo Producto</a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-info"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>SKU</th>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Precio</th>
            <th>Activo</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['sku'] ?></td>
            <td><?= $p['name'] ?></td>
            <td><?= $p['brand'] ?></td>
            <td>Bs. <?= number_format($p['price'], 2) ?></td>
            <td><?= $p['is_active'] ? 'Sí' : 'No' ?></td>
            <td>
                <a href="/Repuestos/public/productos/edit?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>

                <form method="POST" action="/Repuestos/public/productos/delete"
                      style="display:inline-block"
                      onsubmit="return confirm('¿Eliminar producto?')">

                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">

                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
