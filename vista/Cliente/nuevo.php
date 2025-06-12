<?php
require_once("../../modelo/cliente.php");
$cliente = new ClienteModelo();
$id_usuario = $_GET['id_usuario'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $fecha_evento = $_POST['fecha_evento'] ?? '';
    $hora_evento = $_POST['hora_evento'] ?? '';
    $lugar = $_POST['lugar'] ?? '';
    $imagen = $_POST['imagen'] ?? '';
    $estado = $_POST['estado'] ?? 'pendiente';
    $latitud = $_POST['latitud'] ?? '';
    $longitud = $_POST['longitud'] ?? '';
    $cliente->insertarEvento($titulo, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen, $id_usuario, $estado, $latitud, $longitud);
    header("Location: listar.php?id_usuario=$id_usuario");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Evento - Cliente</title>
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
        <h2 class="mb-4 text-center" style="color:#d50000;">Crear Nuevo Evento</h2>
        <form method="post">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input id="titulo" type="text" name="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="fecha_evento" class="form-label">Fecha</label>
                <input id="fecha_evento" type="date" name="fecha_evento" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="hora_evento" class="form-label">Hora</label>
                <input id="hora_evento" type="time" name="hora_evento" class="form-control">
            </div>
            <div class="mb-3">
                <label for="lugar" class="form-label">Lugar (dirección o nombre)</label>
                <input id="lugar" type="text" name="lugar" class="form-control" required autocomplete="off">
            </div>
            <!-- Mapa para seleccionar ubicación -->
            <div id="mapa-seleccion" style="width:100%;height:250px;border-radius:8px;margin-bottom:10px;display:none;"></div>
            <!-- Coordenadas seleccionadas -->
            <div id="coordenadas-info" style="display:none; margin-bottom:15px;">
                <label class="form-label">Coordenadas seleccionadas:</label>
                <div>
                    <input type="text" id="latitud-mostrar" class="form-control mb-1" readonly placeholder="Latitud">
                    <input type="text" id="longitud-mostrar" class="form-control" readonly placeholder="Longitud">
                </div>
            </div>
            <!-- Campos ocultos para coordenadas -->
            <input type="hidden" id="latitud" name="latitud">
            <input type="hidden" id="longitud" name="longitud">
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen (URL de internet)</label>
                <input id="imagen" type="url" name="imagen" class="form-control" placeholder="https://ejemplo.com/imagen.jpg">
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select id="estado" name="estado" class="form-select">
                    <option value="pendiente" selected>Pendiente</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="rechazado">Rechazado</option>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <a href="listar.php?id_usuario=<?= $id_usuario ?>" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-primary">Guardar Evento</button>
            </div>
        </form>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
    var mapaMostrado = false;
    var marker, map;

    function mostrarMapa() {
        if (!mapaMostrado) {
            document.getElementById('mapa-seleccion').style.display = 'block';
            map = L.map('mapa-seleccion').setView([-13.53195, -71.967463], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Al hacer clic en el mapa, colocar marcador y guardar coordenadas
            map.on('click', function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
                document.getElementById('latitud').value = lat;
                document.getElementById('longitud').value = lng;
                document.getElementById('latitud-mostrar').value = lat;
                document.getElementById('longitud-mostrar').value = lng;
                document.getElementById('coordenadas-info').style.display = 'block';
            });

            // Geocodificación al salir del campo lugar
            document.getElementById('lugar').addEventListener('blur', function() {
                var address = this.value;
                if (!address) return;
                fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address + ', Cusco, Perú'))
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            var lat = parseFloat(data[0].lat);
                            var lon = parseFloat(data[0].lon);
                            map.setView([lat, lon], 15);
                            if (marker) {
                                marker.setLatLng([lat, lon]);
                            } else {
                                marker = L.marker([lat, lon]).addTo(map);
                            }
                            document.getElementById('latitud').value = lat;
                            document.getElementById('longitud').value = lon;
                            document.getElementById('latitud-mostrar').value = lat;
                            document.getElementById('longitud-mostrar').value = lon;
                            document.getElementById('coordenadas-info').style.display = 'block';
                        }
                    });
            });
            mapaMostrado = true;
        }
    }

    document.getElementById('lugar').addEventListener('focus', mostrarMapa);
    document.getElementById('lugar').addEventListener('click', mostrarMapa);

    // Si ya hay coordenadas cargadas (por ejemplo, al editar), mostrar el marcador y las coordenadas
    window.addEventListener('DOMContentLoaded', function() {
        var lat = document.getElementById('latitud').value;
        var lng = document.getElementById('longitud').value;
        if (lat && lng) {
            document.getElementById('latitud-mostrar').value = lat;
            document.getElementById('longitud-mostrar').value = lng;
            document.getElementById('coordenadas-info').style.display = 'block';
        }
    });
    </script>
</body>
</html>