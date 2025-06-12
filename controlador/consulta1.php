<?php
// Variables de conexión
$hostDB = '127.0.0.1';
$nombreDB = 'bibliotecadb';
$usuarioDB = 'root';
$contrasenaDB = '';

// Conectar con la base de datos
$hostPDO = "mysql:host=$hostDB;dbname=$nombreDB;";
$miPDO = new PDO($hostPDO, $usuarioDB, $contrasenaDB);

// Obtener la lista de usuarios
$query = $miPDO->prepare('SELECT id, nombre FROM USUARIO');
$query->execute();
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

// Comprobar si se recibió datos POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recogemos las variables
    $idUsuario = isset($_POST['idUsuario']) ? $_POST['idUsuario'] : null;

    // Obtener eventos creados por el usuario
    $query = $miPDO->prepare('
        SELECT E.id, E.titulo, E.fechaEvento
        FROM EVENTO E
        WHERE E.idUsuario = :idUsuario
    ');
    $query->bindParam(':idUsuario', $idUsuario);
    $query->execute();
    $eventos = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eventos por Usuario - CRUD PHP</title>
</head>
<body>
    <form action="" method="post">
        <p>
            <label for="idUsuario">Seleccione Usuario</label>
            <select id="idUsuario" name="idUsuario" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>"><?= $usuario['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <input type="submit" value="Buscar">
        </p>
    </form>

    <?php if (isset($eventos)): ?>
        <h2>Eventos creados por el usuario</h2>
        <table border="1">
            <tr>
                <th>ID Evento</th>
                <th>Título</th>
                <th>Fecha del Evento</th>
            </tr>
            <?php foreach ($eventos as $evento): ?>
                <tr>
                    <td><?= $evento['id'] ?></td>
                    <td><?= $evento['titulo'] ?></td>
                    <td><?= $evento['fechaEvento'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
