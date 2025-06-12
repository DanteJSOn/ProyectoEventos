<?php
require_once("../../modelo/cliente.php");
$cliente = new ClienteModelo();
// Aquí deberías obtener el id_usuario autenticado, por ahora ejemplo:
$id_usuario = $_GET['id_usuario'] ?? 1;
$eventos = $cliente->listarMisEventos($id_usuario);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Eventos - Cliente</title>
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
        <h2 class="mb-4 text-center" style="color:#d50000;">Mis Eventos</h2>
        <div class="mb-3 text-end">
            <a href="nuevo.php?id_usuario=<?= $id_usuario ?>" class="btn btn-primary">Nuevo Evento</a>
        </div>
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Lugar</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($eventos as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['id']) ?></td>
                    <td><?= htmlspecialchars($e['titulo']) ?></td>
                    <td><?= htmlspecialchars($e['descripcion']) ?></td>
                    <td><?= htmlspecialchars($e['fecha_evento']) ?></td>
                    <td><?= htmlspecialchars($e['hora_evento']) ?></td>
                    <td><?= htmlspecialchars($e['lugar']) ?></td>
                    <td><?= htmlspecialchars($e['estado']) ?></td>
                    <td>
                        <a href="modificar.php?id=<?= $e['id'] ?>&id_usuario=<?= $id_usuario ?>" class="btn btn-sm btn-primary">Editar</a>
                        <a href="borrar.php?id=<?= $e['id'] ?>&id_usuario=<?= $id_usuario ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar evento?')">Eliminar</a>
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