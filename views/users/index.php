<br></br>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión de Usuarios</h2>
        <a href="/Repuestos/public/usuarios/crear" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Nuevo Usuario
        </a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Sucursal</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['full_name']) ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><span class="badge bg-info text-dark"><?= $u['role_name'] ?></span></td>
                        <td><?= $u['branch_name'] ?: '<em class="text-muted">Global</em>' ?></td>
                        <td>
                            <?php if ($u['is_active']): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/Repuestos/public/usuarios/editar?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>