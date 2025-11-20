<br></br>
<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Panel de Control Gerencial</h2>
        <div class="text-muted">
            <small>Fecha: <?= date('d/m/Y') ?></small>
        </div>
    </div>

    <div class="row g-4 mb-4">
        
        <div class="col-md-4">
            <div class="card shadow border-start border-success border-5 h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Caja del Día (Global)</h6>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success text-white rounded p-3">
                            <i class="bi bi-cash-coin fs-3"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="mb-0 fw-bold">Bs. <?= number_format($dailyTotal, 2) ?></h2>
                            <small class="text-success">Ventas de hoy</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-start border-danger border-5 h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Stock Crítico</h6>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger text-white rounded p-3">
                            <i class="bi bi-exclamation-triangle fs-3"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="mb-0 fw-bold"><?= $alertsCount ?></h2>
                            <small class="text-danger">Productos por agotarse</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-start border-primary border-5 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h6 class="text-muted mb-2">Botón de Pánico</h6>
                    <a href="/Repuestos/public/reportes/inventario-pdf" target="_blank" class="btn btn-outline-dark w-100">
                        <i class="bi bi-file-pdf"></i> Imprimir Reporte General
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-trophy"></i> Ranking de Vendedores (Mes)</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Empleado</th>
                                <th class="text-center">Ventas</th>
                                <th class="text-end">Total Generado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ranking)): ?>
                                <tr><td colspan="4" class="text-center py-3">Sin ventas este mes</td></tr>
                            <?php else: ?>
                                <?php foreach($ranking as $i => $rank): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td class="fw-bold"><?= htmlspecialchars($rank['full_name']) ?></td>
                                    <td class="text-center"><?= $rank['sales_count'] ?></td>
                                    <td class="text-end text-success fw-bold">Bs. <?= number_format($rank['total_money'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-box-seam"></i> Alerta de Inventario</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Sucursal</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($criticalStock)): ?>
                                    <tr><td colspan="3" class="text-center py-3 text-success">¡Inventario Saludable!</td></tr>
                                <?php else: ?>
                                    <?php foreach($criticalStock as $item): ?>
                                    <tr>
                                        <td>
                                            <small><?= htmlspecialchars($item['name']) ?></small><br>
                                            <small class="text-muted"><?= $item['sku'] ?></small>
                                        </td>
                                        <td><small><?= $item['branch'] ?></small></td>
                                        <td>
                                            <span class="badge bg-danger rounded-pill">
                                                Queda: <?= $item['quantity'] ?>
                                            </span>
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

    </div>
</div>