<?php
$mysqli = new mysqli("localhost", "root", "", "eventos_culturales_cusco");
if ($mysqli->connect_errno) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "
    SELECT e.*, c.nombre AS categoria
    FROM eventos e
    LEFT JOIN evento_categoria ec ON e.id = ec.id_evento
    LEFT JOIN categorias c ON ec.id_categoria = c.id
    WHERE e.id = $id
    LIMIT 1
";
$result = $mysqli->query($query);
$evento = $result && $result->num_rows > 0 ? $result->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $evento ? htmlspecialchars($evento['titulo']) : 'Detalle de Evento'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .breadcrumb {
            background: none;
            padding-left: 0;
        }
        .evento-imagen {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .evento-secundaria {
            width: 200px;
            float: right;
            margin-left: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            object-fit: cover;
        }
        .categoria-link {
            color: #d50000;
            text-decoration: none;
            font-weight: 600;
        }
        .categoria-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../../index.php">Inicio</a></li>
            <li class="breadcrumb-item"><a href="#">Calendario</a></li>
            <li class="breadcrumb-item"><a href="#" class="text-danger"><?= $evento['categoria'] ?? 'Evento' ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($evento['titulo']) ?></li>
        </ol>
    </nav>

    <?php if ($evento): ?>
        <!-- Imagen principal -->
        <?php if (!empty($evento['imagen'])): ?>
            <img src="<?= htmlspecialchars($evento['imagen']) ?>" alt="Imagen del evento" class="evento-imagen mb-4">
        <?php endif; ?>

        <!-- Título -->
        <h1 class="mb-3 text-danger"><?= htmlspecialchars($evento['titulo']) ?></h1>
        <hr>

        <!-- Fecha y hora -->
        <p class="text-muted fs-5 mb-4">
            <?= date("d F Y", strtotime($evento['fecha_evento'])) ?> | <?= date("h:i A", strtotime($evento['hora_evento'])) ?>
        </p>

        <!-- Descripción con imagen secundaria flotante -->
        <div class="mb-4">
            <?php if (!empty($evento['imagen'])): ?>
                <img src="<?= htmlspecialchars($evento['imagen']) ?>" alt="Imagen secundaria" class="evento-secundaria">
            <?php endif; ?>
            <p><?= nl2br(htmlspecialchars($evento['descripcion'] ?? 'Sin descripción.')) ?></p>
        </div>

        <!-- Datos adicionales -->
        <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item"><strong>Lugar:</strong> <?= htmlspecialchars($evento['lugar']) ?></li>
            <li class="list-group-item"><strong>Estado:</strong> <?= htmlspecialchars($evento['estado']) ?></li>
            <li class="list-group-item"><strong>Publicado en:</strong> <?= $evento['fecha_publicacion'] ?? 'N/D' ?></li>
        </ul>

        <!-- Panel de ubicación -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                Ubicación del evento
            </div>
            <div class="card-body">
                <?php if (!empty($evento['lugar'])): ?>
                    <div id="mapa-ubicacion" style="width:100%;height:300px;border-radius:8px;"></div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">No hay información de ubicación disponible.</div>
                <?php endif; ?>
            </div>
        </div>

        <a href="index.php" class="btn btn-secondary">&laquo; Volver al inicio</a>
    <?php else: ?>
        <div class="alert alert-warning text-center">No se encontró el evento.</div>
    <?php endif; ?>
</div>

<?php if ($evento && !empty($evento['lugar'])): ?>
<!-- Google Maps API (requiere clave válida) -->
<script src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&callback=initMap" async defer></script>
<script>
function initMap() {
    var geocoder = new google.maps.Geocoder();
    var address = <?= json_encode($evento['lugar']) ?>;
    var map = new google.maps.Map(document.getElementById('mapa-ubicacion'), {
        zoom: 15,
        center: {lat: -13.53195, lng: -71.967463} // Centro de Cusco por defecto
    });
    geocoder.geocode({ 'address': address + ', Cusco, Perú' }, function(results, status) {
        if (status === 'OK') {
            map.setCenter(results[0].geometry.location);
            new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
        } else {
            document.getElementById('mapa-ubicacion').innerHTML = '<div class="alert alert-warning">No se pudo mostrar el mapa para esta dirección.</div>';
        }
    });
}
</script>
<?php endif; ?>
</body>
</html>
