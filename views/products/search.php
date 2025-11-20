<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscador de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<br></br>   
<body class="bg-light">

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Buscador Maestro de Productos</h2>
        <a href="/Repuestos/public/stock" class="btn btn-outline-secondary">Ir a Reservas</a>
    </div>

    <form method="GET" action="/Repuestos/public/productos/buscar" class="row g-3 mb-4">
        <div class="col-auto flex-grow-1">
            <input type="text" name="q" class="form-control" 
                   placeholder="Buscar por nombre, SKU, descripción..."
                   value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" 
                   required>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary">Buscar</button>
        </div>
        <div class="col-auto">
            <a href="/Repuestos/public/productos/buscar" class="btn btn-secondary">Limpiar</a>
        </div>
    </form>

    <?php if (isset($results) && count($results) > 0): ?>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>SKU</th>
                                <th>Producto</th>
                                <th>Sucursal</th>
                                <th>Stock</th>
                                <th>Ubicación</th>
                                <th>Precio</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($results as $r): ?>
                            <?php 
                                // Variables auxiliares
                                $qty = $r["quantity"]; // IMPORTANTE: Usamos quantity, no stock
                                $hasStock = $qty > 0;
                                
                                // Detectar si es mi sucursal (Asumiendo que hay sesión iniciada)
                                $myBranchId = $_SESSION['branch_id'] ?? 0;
                                $isRemote = ($r['branch_id'] != $myBranchId);
                            ?>
                            <tr>
                                <td><small><?= $r["sku"] ?></small></td>
                                <td>
                                    <strong><?= $r["name"] ?></strong><br>
                                    <small class="text-muted"><?= $r["brand"] ?></small>
                                </td>
                                <td><?= $r["branch_name"] ?></td>
                                <td>
                                    <?php if (!$hasStock): ?>
                                        <span class="badge bg-danger">Sin stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-success fs-6"><?= $qty ?> u.</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $r["location"] ?: '-' ?></td>
                                <td><strong>Bs. <?= number_format($r["price"], 2) ?></strong></td>
                                <td>
                                    <?php if ($isRemote && $hasStock): ?>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="openRequestModal(
                                                <?= $r['id'] ?>, 
                                                '<?= htmlspecialchars($r['name']) ?>', 
                                                <?= $r['branch_id'] ?>, 
                                                '<?= htmlspecialchars($r['branch_name']) ?>',
                                                <?= $qty ?>
                                            )">
                                            <i class="bi bi-box-seam"></i> Solicitar
                                        </button>
                                    <?php elseif (!$isRemote && $hasStock): ?>
                                        <span class="badge bg-light text-dark border">En tienda</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php elseif (isset($_GET["q"])): ?>
        <div class="alert alert-warning mt-3">
            No se encontraron resultados para <strong><?= htmlspecialchars($_GET["q"]) ?></strong>
        </div>
    <?php endif; ?>

</div>

<div class="modal fade" id="requestModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="/Repuestos/public/stock/reserve" method="POST">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Solicitar Repuesto a otra Sucursal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="product_id" id="reqProductId">
                <input type="hidden" name="from_branch" id="reqBranchId">
                
                <div class="mb-3">
                    <label class="form-label text-muted">Producto:</label>
                    <input type="text" class="form-control fw-bold" id="reqProductName" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Solicitar a:</label>
                    <input type="text" class="form-control" id="reqBranchName" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cantidad a pedir:</label>
                    <input type="number" name="quantity" class="form-control" min="1" value="1" required>
                    <div class="form-text">Stock disponible allá: <span id="reqMaxStock" class="fw-bold"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Confirmar Solicitud</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openRequestModal(prodId, prodName, branchId, branchName, maxQty) {
    document.getElementById('reqProductId').value = prodId;
    document.getElementById('reqProductName').value = prodName;
    document.getElementById('reqBranchId').value = branchId;
    document.getElementById('reqBranchName').value = branchName;
    document.getElementById('reqMaxStock').innerText = maxQty;
    
    new bootstrap.Modal(document.getElementById('requestModal')).show();
}
</script>

</body>
</html>