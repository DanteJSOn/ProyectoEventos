<?php
require_once("../../modelo/admin.php");
$admin = new AdminModelo();
$usuarios = $admin->listarUsuarios();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fafbfc; font-family: 'Montserrat', Arial, sans-serif; }
        .btn-primary { background: #d50000; border: none; }
        .btn-primary:hover { background: #b71c1c; }
        .btn-danger { background: #b71c1c; border: none; }
        .btn-danger:hover { background: #d32f2f; }
        .table thead { background: #d50000; color: #fff; }
        .table-striped>tbody>tr:nth-of-type(odd)>* { background: #f7eaea; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4 text-center" style="color:#d50000;">Gestión de Usuarios</h2>
        <div class="mb-3 text-end">
            <a href="nuevo.php" class="btn btn-primary">Nuevo Usuario</a>
        </div>
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id']) ?></td>
                    <td><?= htmlspecialchars($u['nombre']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['tipo']) ?></td>
                    <td><?= $u['estado'] ? 'Activo' : 'Inactivo' ?></td>
                    <td>
                        <a href="modificar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="borrar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar usuario?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-3">
            <a href="../../index.php" class="btn btn-secondary">Volver al inicio</a>
        </div>
    </div>
</body>
</html>