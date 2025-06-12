<?php
require_once("../../modelo/admin.php");
$admin = new AdminModelo();
$usuarios = $admin->listarUsuarios();

// Estadísticas rápidas
$total = count($usuarios);
$activos = count(array_filter($usuarios, fn($u) => $u['estado']));
$inactivos = $total - $activos;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .btn-primary { background: #d50000; border: none; }
        .btn-primary:hover { background: #b71c1c; }
        .btn-danger { background: #b71c1c; border: none; }
        .btn-danger:hover { background: #d32f2f; }
        .table thead { background-color: #d50000; color: white; }
        .card { box-shadow: 0 0 10px rgba(0,0,0,0.05); border: none; }
        .card h5 { font-size: 1.2rem; }
        .search-box input { border-radius: 20px; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4 text-center text-danger">Panel de Administración</h2>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5>Total de Usuarios</h5>
                    <h3 class="text-danger"><?= $total ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5>Usuarios Activos</h5>
                    <h3 class="text-success"><?= $activos ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-3">
                    <h5>Usuarios Inactivos</h5>
                    <h3 class="text-warning"><?= $inactivos ?></h3>
                </div>
            </div>
        </div>

        <!-- Botón y buscador -->
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="nuevo.php" class="btn btn-primary">+ Nuevo Usuario</a>
            </div>
            <div class="col-md-6 text-end search-box">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre o email...">
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle" id="usuariosTable">
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
                        <td><?= ucfirst(htmlspecialchars($u['tipo'])) ?></td>
                        <td>
                            <span class="badge <?= $u['estado'] ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $u['estado'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td>
                            <a href="modificar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                            <a href="borrar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar usuario?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Botón volver -->
        <div class="mt-4 text-center">
            <a href="../../index.php" class="btn btn-secondary">Volver al Inicio</a>
        </div>
    </div>

    <!-- Script para búsqueda -->
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#usuariosTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
