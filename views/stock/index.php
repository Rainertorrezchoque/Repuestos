<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<br></br>
<body class="bg-light">

<div class="container-fluid mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Reservas entre Sucursales</h2>
        <a href="/Repuestos/public/productos/buscar" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Solicitar Repuesto
        </a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Origen (Da)</th>
                            <th>Destino (Recibe)</th>
                            <th>Cant.</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">No hay reservas registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $res): ?>
                                <?php 
                                    // Lógica visual
                                    $badgeClass = match($res['status']) {
                                        'PENDING' => 'bg-warning text-dark',
                                        'COMPLETED' => 'bg-success',
                                        'CANCELLED' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                    
                                    // ¿Soy yo quien debe aprobar? (Soy el origen y está pendiente)
                                    $amISource = $res['from_branch_id'] == $_SESSION['branch_id'];
                                    $canApprove = ($res['status'] === 'PENDING' && $amISource);
                                ?>
                                <tr>
                                    <td><?= $res['id'] ?></td>
                                    <td><?= date('d/m H:i', strtotime($res['requested_at'])) ?></td>
                                    <td><?= htmlspecialchars($res['product_name']) ?></td>
                                    
                                    <td class="<?= $amISource ? 'fw-bold text-primary' : '' ?>">
                                        <?= htmlspecialchars($res['from_name']) ?>
                                    </td>
                                    <td class="<?= !$amISource ? 'fw-bold text-primary' : '' ?>">
                                        <?= htmlspecialchars($res['to_name']) ?>
                                    </td>
                                    
                                    <td class="fw-bold fs-5"><?= $res['quantity'] ?></td>
                                    <td>
                                        <span class="badge <?= $badgeClass ?>"><?= $res['status'] ?></span>
                                    </td>
                                    <td>
                                        <?php if ($canApprove): ?>
                                            <div class="d-flex gap-2">
                                                <form action="/Repuestos/public/stock/approve" method="POST" 
                                                      onsubmit="return confirm('¿Confirmar envío de stock?');">
                                                    <input type="hidden" name="reservation_id" value="<?= $res['id'] ?>">
                                                    <input type="hidden" name="product_id" value="<?= $res['product_id'] ?>">
                                                    <input type="hidden" name="quantity" value="<?= $res['quantity'] ?>">
                                                    <input type="hidden" name="from_branch" value="<?= $res['from_branch_id'] ?>">
                                                    <input type="hidden" name="to_branch" value="<?= $res['to_branch_id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-success" title="Aprobar envío">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>

                                                <form action="/Repuestos/public/stock/reject" method="POST"
                                                      onsubmit="return confirm('¿Rechazar solicitud?');">
                                                    <input type="hidden" name="reservation_id" value="<?= $res['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Rechazar">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php elseif($res['status'] === 'PENDING'): ?>
                                            <span class="text-muted small">Esperando aprobación...</span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
