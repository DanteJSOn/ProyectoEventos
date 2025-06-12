<?php
require_once("../../modelo/admin.php");
$admin = new AdminModelo();

if (!isset($_GET['id'])) {
    header('Location: listar.php');
    exit;
}
$id = $_GET['id'];
$usuario = $admin->obtenerUsuario($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $tipo = $_POST['tipo'] ?? 'cliente';
    $estado = $_POST['estado'] ?? 1;
    $admin->actualizarUsuario($id, $nombre, $email, $password, $tipo, $estado);
    header('Location: listar.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fafbfc; font-family: 'Montserrat', Arial, sans-serif; }
        .container { max-width: 500px; margin-top: 40px; }
        .form-label { color: #d50000; font-weight: 600; }
        .btn-primary { background: #d50000; border: none; }
        .btn-primary:hover { background: #b71c1c; }
    </style>
</head>
<body>
    <div class="container shadow rounded p-4 bg-white">
        <h2 class="mb-4 text-center" style="color:#d50000;">Editar Usuario</h2>
        <form method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input id="nombre" type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" type="password" name="password" class="form-control" value="<?= htmlspecialchars($usuario['password']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select id="tipo" name="tipo" class="form-select">
                    <option value="cliente" <?= $usuario['tipo']=='cliente'?'selected':'' ?>>Cliente</option>
                    <option value="admin" <?= $usuario['tipo']=='admin'?'selected':'' ?>>Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select">
                    <option value="1" <?= $usuario['estado']==1?'selected':'' ?>>Activo</option>
                    <option value="0" <?= $usuario['estado']==0?'selected':'' ?>>Inactivo</option>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <a href="listar.php" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>