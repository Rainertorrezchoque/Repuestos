<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
        <div class="card shadow p-4" style="width: 380px;">

            <h3 class="text-center mb-4">Inicio de Sesión</h3>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100">Ingresar</button>

            </form>

        </div>
    </div>

</body>
</html>
