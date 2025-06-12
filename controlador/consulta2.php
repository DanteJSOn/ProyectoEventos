<?php
// Variables de conexión
$hostDB = '127.0.0.1';
$nombreDB = 'bibliotecadb';
$usuarioDB = 'root';
$contrasenaDB = '';

// Conectar con la base de datos
$hostPDO = "mysql:host=$hostDB;dbname=$nombreDB;";
$miPDO = new PDO($hostPDO, $usuarioDB, $contrasenaDB);

// Obtener eventos con estado 'pendiente'
$query = $miPDO->prepare('
    SELECT E.id, L.titulo, P.idUsuario, U.nombre AS nombreUsuario, P.fechaPrestamo
    FROM EJEMPLAR E
    JOIN LIBRO L ON E.idLibro = L.id
    JOIN PRESTAMO P ON E.id = P.idEjemplar
    JOIN USUARIO U ON P.idUsuario = U.id
    WHERE P.fechaDevolucion IS NULL
');
$query->execute();
$eventos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eventos Pendientes - CRUD PHP</title>
</head>
<body>
    <h2>Eventos con estado 'pendiente'</h2>
    <table border="1">
        <tr>
            <th>ID Evento</th>
            <th>Título</th>
            <th>ID Usuario</th>
            <th>Nombre Usuario</th>
            <th>Fecha de Préstamo</th>
        </tr>
        <?php foreach ($eventos as $evento): ?>
            <tr>
                <td><?= $evento['id'] ?></td>
                <td><?= $evento['titulo'] ?></td>
                <td><?= $evento['idUsuario'] ?></td>
                <td><?= $evento['nombreUsuario'] ?></td>
                <td><?= $evento['fechaPrestamo'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
