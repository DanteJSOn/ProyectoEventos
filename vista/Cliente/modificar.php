<?php
require_once("../../modelo/cliente.php");
$cliente = new ClienteModelo();
$id_usuario = $_GET['id_usuario'] ?? 1;

if (!isset($_GET['id'])) {
    header("Location: listar.php?id_usuario=$id_usuario");
    exit;
}
$id = $_GET['id'];
$evento = $cliente->obtenerEvento($id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $fecha_evento = $_POST['fecha_evento'] ?? '';
    $hora_evento = $_POST['hora_evento'] ?? '';
    $lugar = $_POST['lugar'] ?? '';
    $imagen = $_POST['imagen'] ?? '';
    $estado = $_POST['estado'] ?? 'pendiente';
    $cliente->actualizarEvento($id, $titulo, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen, $estado);
    header("Location: listar.php?id_usuario=$id_usuario");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento - Cliente</title>
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
        <h2 class="mb-4 text-center" style="color:#d50000;">Editar Evento</h2>
        <form method="post">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input id="titulo" type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($evento['titulo']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control"><?= htmlspecialchars($evento['descripcion']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="fecha_evento" class="form-label">Fecha</label>
                <input id="fecha_evento" type="date" name="fecha_evento" class="form-control" value="<?= htmlspecialchars($evento['fecha_evento']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="hora_evento" class="form-label">Hora</label>
                <input id="hora_evento" type="time" name="hora_evento" class="form-control" value="<?= htmlspecialchars($evento['hora_evento']) ?>">
            </div>
            <div class="mb-3">
                <label for="lugar" class="form-label">Lugar</label>
                <input id="lugar" type="text" name="lugar" class="form-control" value="<?= htmlspecialchars($evento['lugar']) ?>">
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen (URL)</label>
                <input id="imagen" type="text" name="imagen" class="form-control" value="<?= htmlspecialchars($evento['imagen']) ?>">
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select">
                    <option value="pendiente" <?= $evento['estado']=='pendiente'?'selected':'' ?>>Pendiente</option>
                    <option value="aprobado" <?= $evento['estado']=='aprobado'?'selected':'' ?>>Aprobado</option>
                    <option value="rechazado" <?= $evento['estado']=='rechazado'?'selected':'' ?>>Rechazado</option>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <a href="listar.php?id_usuario=<?= $id_usuario ?>" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>