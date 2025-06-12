<?php
require_once("../../modelo/cliente.php");
$cliente = new ClienteModelo();

// Obtener el ID del usuario autenticado (en este ejemplo, fijo como 1)
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

<!-- Barra de navegación -->
<nav class="navbar navbar-expand-lg navbar-dark bg-danger">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Eventos Culturales</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">
        Bienvenido, Usuario <?= $id_usuario ?>
      </span>
      <a href="../../logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
    </div>
  </div>
</nav>

<!-- Contenido principal -->
<div class="container mt-4">
    <h2 class="mb-4 text-center" style="color:#d50000;">Mis Eventos</h2>

    <div class="mb-3 text-end">
        <a href="nuevo.php?id_usuario=<?= $id_usuario ?>" class="btn btn-primary">+ Nuevo Evento</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
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
                <?php if (!empty($eventos)): ?>
                    <?php foreach($eventos as $e): ?>
                        <tr>
                            <td><?= htmlspecialchars($e['id']) ?></td>
                            <td><?= htmlspecialchars($e['titulo']) ?></td>
                            <td><?= htmlspecialchars($e['descripcion']) ?></td>
                            <td><?= htmlspecialchars($e['fecha_evento']) ?></td>
                            <td><?= htmlspecialchars($e['hora_evento']) ?></td>
                            <td><?= htmlspecialchars($e['lugar']) ?></td>
                            <td>
                                <?php if (strtolower($e['estado']) === 'activo'): ?>
                                    <span class="badge bg-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($e['estado']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="modificar.php?id=<?= $e['id'] ?>&id_usuario=<?= $id_usuario ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="borrar.php?id=<?= $e['id'] ?>&id_usuario=<?= $id_usuario ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar evento?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No tienes eventos registrados.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 text-start">
        <a href="../../index.php" class="btn btn-secondary">Volver al inicio</a>
    </div>
</div>

</body>
</html>
