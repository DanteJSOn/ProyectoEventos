<?php
// Variables de conexión
$hostDB = '127.0.0.1';
$nombreDB = 'bibliotecadb';
$usuarioDB = 'root';
$contrasenaDB = '';

// Conectar con la base de datos
$hostPDO = "mysql:host=$hostDB;dbname=$nombreDB;";
$miPDO = new PDO($hostPDO, $usuarioDB, $contrasenaDB);

// Obtener ranking de usuarios por eventos creados
$query = $miPDO->prepare('
    SELECT u.nombre, COUNT(e.id) AS total_eventos
    FROM usuarios u
    LEFT JOIN eventos e ON u.id = e.id_usuario
    GROUP BY u.id
    ORDER BY total_eventos DESC
');
$query->execute();
$rankingUsuarios = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ranking de Usuarios - CRUD PHP</title>
</head>
<body>
    <h2>Ranking de Usuarios con más Eventos Creados</h2>
    <table border="1">
        <tr>
            <th>Nombre del Usuario</th>
            <th>Cantidad de Eventos</th>
        </tr>
        <?php foreach ($rankingUsuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['nombre'] ?></td>
                <td><?= $usuario['total_eventos'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
