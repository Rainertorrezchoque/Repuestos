<?php

$isEdit = isset($product) && $product;
$title = $isEdit ? 'Editar Producto' : 'Nuevo Producto';
$action = $isEdit ? '/Repuestos/public/productos/update' : '/Repuestos/public/productos/store';
?>
<br></br>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><?= $title ?></h4>
            <a href="/Repuestos/public/productos" class="btn btn-light btn-sm">Volver</a>
        </div>
        <div class="card-body">

            <form action="<?= $action ?>" method="POST">
                <input type="hidden" name="_csrf" value="<?= csrf_token() ?>">

                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <?php endif; ?>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Código SKU <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control" required 
                               value="<?= $isEdit ? htmlspecialchars($product['sku']) : '' ?>"
                               placeholder="Ej: AMORT-001">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nombre del Repuesto <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required 
                               value="<?= $isEdit ? htmlspecialchars($product['name']) : '' ?>"
                               placeholder="Ej: Amortiguador Delantero Mazda 3">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Marca</label>
                        <input type="text" name="brand" class="form-control" 
                               value="<?= $isEdit ? htmlspecialchars($product['brand']) : '' ?>"
                               placeholder="Ej: Toyota, Bosch, Genérico">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Categoría</label>
                        <select name="category_id" class="form-select">
                            <option value="">-- Seleccionar Categoría --</option>
                            <?php if (isset($categories) && !empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" 
                                        <?= ($isEdit && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Costo de Compra (Bs.)</label>
                        <input type="number" step="0.01" name="cost" class="form-control" 
                               value="<?= $isEdit ? $product['cost'] : '0.00' ?>">
                        <div class="form-text">Solo visible para administración.</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Precio de Venta (Bs.) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="price" class="form-control border-success" required 
                               value="<?= $isEdit ? $product['price'] : '0.00' ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Stock Mínimo (Alerta)</label>
                        <input type="number" name="min_stock" class="form-control" 
                               value="<?= $isEdit ? $product['min_stock'] : '2' ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Descripción Detallada</label>
                        <textarea name="description" class="form-control" rows="3"><?= $isEdit ? htmlspecialchars($product['description']) : '' ?></textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="is_active" id="active" value="1" 
                                   <?= (!$isEdit || $product['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="active">Producto Activo (Se puede vender)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="visible_public" id="public" value="1" 
                                   <?= ($isEdit && $product['visible_public']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="public">Visible en Web Pública</label>
                        </div>
                    </div>

                    <?php if (!$isEdit): ?>
                        <div class="col-12">
                            <div class="card bg-light border-info mt-3">
                                <div class="card-header bg-info text-white">
                                    <strong>Inventario Inicial (Opcional)</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Sucursal de Ingreso</label>
                                            <select name="init_branch_id" class="form-select">
                                                <option value="">-- Seleccionar Sucursal --</option>
                                                <?php if (isset($branches) && !empty($branches)): ?>
                                                    <?php foreach ($branches as $b): ?>
                                                        <option value="<?= $b['id'] ?>">
                                                            <?= htmlspecialchars($b['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Cantidad Inicial</label>
                                            <input type="number" name="init_quantity" class="form-control" value="0" min="0">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Ubicación Física</label>
                                            <input type="text" name="init_location" class="form-control" placeholder="Ej: Estante A-1">
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        * Si dejas la cantidad en 0, el producto se creará sin stock ("No inventariado").
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <hr class="mt-4">

                    <div class="col-12 text-end">
                        <a href="/Repuestos/public/productos" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">
                            Guardar Producto
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>