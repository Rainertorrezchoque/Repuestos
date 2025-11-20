<?php
$isEdit = isset($user);
$action = $isEdit ? '/Repuestos/public/usuarios/update' : '/Repuestos/public/usuarios/store';
?>

<br></br>

<div class="container mt-4">
    <div class="card shadow col-md-8 mx-auto">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?= $isEdit ? 'Editar Usuario' : 'Nuevo Usuario' ?></h4>
        </div>
        <div class="card-body">
            <form action="<?= $action ?>" method="POST">
                <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">
                <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= $user['id'] ?>"><?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="full_name" class="form-control" required value="<?= $isEdit ? $user['full_name'] : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre de Usuario (Login)</label>
                        <input type="text" name="username" class="form-control" required value="<?= $isEdit ? $user['username'] : '' ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $isEdit ? $user['email'] : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" <?= !$isEdit ? 'required' : '' ?>>
                        <?php if ($isEdit): ?>
                            <small class="text-muted">Dejar en blanco para no cambiar.</small>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Rol</label>
                        <select name="role_id" class="form-select" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>" <?= ($isEdit && $user['role_id'] == $role['id']) ? 'selected' : '' ?>>
                                    <?= ucfirst($role['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sucursal Asignada</label>
                        <select name="branch_id" class="form-select">
                            <option value="">-- Ninguna (Global/Dueño) --</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>" <?= ($isEdit && $user['branch_id'] == $branch['id']) ? 'selected' : '' ?>>
                                    <?= $branch['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" class="form-check-input" name="is_active" id="active" value="1" <?= (!$isEdit || $user['is_active']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="active">Usuario Activo</label>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="/Repuestos/public/usuarios" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-success">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>