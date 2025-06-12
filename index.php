<?php
// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "eventos_culturales_cusco");
if ($mysqli->connect_errno) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Obtener filtros desde GET
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';

// Obtener categorías para el filtro
$categorias_rs = $mysqli->query("SELECT id, nombre FROM categorias ORDER BY nombre");

// Construir consulta dinámica
$where = "e.estado = 'aprobado'";
$params = [];

if ($keyword !== '') {
    $where .= " AND (e.titulo LIKE ? OR e.descripcion LIKE ? OR e.lugar LIKE ?)";
    $kw = "%$keyword%";
    $params[] = $kw; $params[] = $kw; $params[] = $kw;
}
if ($categoria > 0) {
    $where .= " AND ec.id_categoria = ?";
    $params[] = $categoria;
}
if ($estado !== '' && in_array($estado, ['pendiente','aprobado','rechazado'])) {
    $where .= " AND e.estado = ?";
    $params[] = $estado;
}

$query = "
    SELECT e.*, c.nombre AS categoria
    FROM eventos e
    LEFT JOIN evento_categoria ec ON e.id = ec.id_evento
    LEFT JOIN categorias c ON ec.id_categoria = c.id
    WHERE $where
    ORDER BY e.fecha_evento ASC, e.hora_evento ASC
";

$stmt = $mysqli->prepare($query);

// Vincular parámetros si hay filtros
if ($params) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CuscoLive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #fafbfc;
            font-family: 'Montserrat', Arial, sans-serif;
            color: #232323;
        }
        .cusco-header {
            background: #fff;
            color: #d50000;
            padding: 1.2rem 0 1rem 0;
            border-bottom: 1px solid #eee;
            margin-bottom: 2rem;
        }
        .cusco-header .logo {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            color: #d50000;
        }
        .cusco-header .logo img {
            width: 40px;
            margin-right: 10px;
        }
        .cusco-header nav {
            font-size: 1rem;
            font-weight: 500;
        }
        .cusco-header nav a {
            color: #232323;
            margin: 0 1rem;
            text-decoration: none;
            transition: color 0.2s;
            border-bottom: 2px solid transparent;
            padding-bottom: 2px;
        }
        .cusco-header nav a.active,
        .cusco-header nav a:hover {
            color: #d50000;
            border-bottom: 2px solid #d50000;
        }
        .login-btns {
            display: flex;
            gap: 0.5rem;
        }
        .login-btns .btn {
            border-radius: 20px;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0.35rem 1.2rem;
            border: none;
            transition: background 0.2s, color 0.2s;
        }
        .login-btns .btn-light {
            background: #fff;
            color: #d50000;
            border: 1px solid #d50000;
        }
        .login-btns .btn-light:hover {
            background: #d50000;
            color: #fff;
        }
        .login-btns .btn-outline-light {
            background: transparent;
            color: #d50000;
            border: 1px solid #d50000;
        }
        .login-btns .btn-outline-light:hover {
            background: #d50000;
            color: #fff;
        }
        .sidebar-box {
            background: #fff;
            border-radius: 10px;
            padding: 1.2rem 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .sidebar-title {
            color: #d50000;
            font-weight: 700;
            font-size: 1.08rem;
            margin-bottom: 0.7rem;
            letter-spacing: 1px;
        }
        .sidebar-box ul {
            padding-left: 0;
            list-style: none;
        }
        .sidebar-box ul li a {
            color: #232323;
            text-decoration: none;
            font-size: 0.97rem;
            display: block;
            padding: 2px 0;
            border-radius: 4px;
            transition: background 0.15s;
        }
        .sidebar-box ul li a:hover {
            background: #f7eaea;
            color: #d50000;
        }
        .event-row {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            padding: 1.2rem 1rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            border: none;
        }
        .event-title {
            color: #d50000;
            font-size: 1.12rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
        }
        .event-date, .event-cat {
            color: #666;
            font-size: 0.98rem;
        }
        .event-cat {
            font-weight: 500;
        }
        .event-img {
            width: 90px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
            margin-left: 1.2rem;
            border: 1px solid #eee;
        }
        .event-link {
            color: #d50000;
            font-weight: 500;
            text-decoration: none;
            font-size: 0.97rem;
        }
        .event-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 991px) {
            .cusco-header .logo { font-size: 1.3rem; }
            .cusco-header nav { font-size: 0.97rem; }
            .event-img { width: 70px; height: 50px; }
        }
        @media (max-width: 767px) {
            .cusco-header { flex-direction: column; align-items: flex-start; }
            .cusco-header nav { margin-top: 0.7rem; }
            .login-btns { margin-top: 0.7rem; }
            .event-row { flex-direction: column !important; align-items: flex-start !important; }
            .event-img { margin: 1rem 0 0 0; }
        }
        @media (max-width: 575px) {
            .cusco-header .logo img { width: 28px; }
            .cusco-header .logo { font-size: 1rem; }
            .sidebar-box { padding: 0.7rem 0.5rem; }
            .event-title { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <header class="cusco-header mb-4 shadow-sm">
        <div class="container d-flex flex-wrap justify-content-between align-items-center">
            <div class="logo">
                <img src="imagenes/logo.jpg" alt="Cusco Live Logo">
                CuscoLive
            </div>
            <nav class="d-flex align-items-center flex-wrap">
                <a href="#" class="active">INICIO</a>
                <a href="#">MAGAZÍN</a>
                <a href="#">CALENDARIO</a>
                <a href="#">DIRECTORIO</a>
                <a href="#">TOURS</a>
                <!-- Buscador y filtros -->
                <form class="d-flex ms-3" role="search" id="buscador-form" method="get" action="index.php">
                    <input class="form-control form-control-sm me-2" type="search" placeholder="Buscar eventos..." aria-label="Buscar" id="buscador-input" name="keyword" value="<?= htmlspecialchars($keyword) ?>" style="min-width:140px;">
                    <select class="form-select form-select-sm me-2" name="categoria">
                        <option value="0">Todas las categorías</option>
                        <?php while($cat = $categorias_rs->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>" <?= $categoria == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <select class="form-select form-select-sm me-2" name="estado">
                        <option value="">Todos los estados</option>
                        <option value="aprobado" <?= $estado == 'aprobado' ? 'selected' : '' ?>>Aprobado</option>
                        <option value="pendiente" <?= $estado == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="rechazado" <?= $estado == 'rechazado' ? 'selected' : '' ?>>Rechazado</option>
                    </select>
                    <button class="btn btn-outline-danger btn-sm" type="submit">Buscar</button>
                </form>
            </nav>
            <div class="login-btns">
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar Sesión</button>
                <button class="btn btn-outline-light btn-sm" onclick="location.href='registro.html'">Registrarse</button>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <!-- Sidebar izquierdo -->
            <div class="col-lg-3 mb-4">
                <div class="sidebar-box">
                    <div class="sidebar-title">SOBRE el CUSCO</div>
                    <ul class="mb-0">
                        <li><a href="#">EL CUSCO</a></li>
                        <li><a href="#">INFORMACIÓN GENERAL</a></li>
                        <li><a href="#">CIRCUITOS TURÍSTICOS</a></li>
                        <li><a href="#">MACHU PICCHU</a></li>
                        <li><a href="#">ALOJAMIENTO</a></li>
                        <li><a href="#">GASTRONOMÍA</a></li>
                        <li><a href="#">TRANSPORTE</a></li>
                        <li><a href="#">COMPRAS / SERVICIOS</a></li>
                    </ul>
                </div>
                <div class="sidebar-box">
                    <div class="sidebar-title">MEDIATECA</div>
                    <ul class="mb-0">
                        <li><a href="#">FOTOGRAFÍA</a></li>
                        <li><a href="#">PINTURA</a></li>
                        <li><a href="#">ESCULTURA</a></li>
                    </ul>
                </div>
            </div>
            <!-- Contenido central -->
            <div class="col-lg-6 mb-4" style="max-height: 80vh; overflow-y: auto;">
                <nav class="mb-2" style="font-size:0.95rem;">
                    <span>HOME</span> / <span>CALENDARIO</span>
                </nav>
                <!-- Mostrar filtros activos -->
                <?php if ($keyword || $categoria || $estado): ?>
                <div class="mb-2">
                    <span class="badge bg-secondary">Filtros:</span>
                    <?php if ($keyword): ?><span class="badge bg-info text-dark">Palabra: <?= htmlspecialchars($keyword) ?></span><?php endif; ?>
                    <?php if ($categoria): ?><span class="badge bg-info text-dark">Categoría: <?= htmlspecialchars($mysqli->query("SELECT nombre FROM categorias WHERE id=$categoria")->fetch_row()[0] ?? '') ?></span><?php endif; ?>
                    <?php if ($estado): ?><span class="badge bg-info text-dark">Estado: <?= htmlspecialchars($estado) ?></span><?php endif; ?>
                    <a href="index.php" class="badge bg-danger text-white text-decoration-none">Limpiar filtros</a>
                </div>
                <?php endif; ?>
                <!-- Eventos principales -->
                <div id="eventos-container">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php foreach ($result as $evento): ?>
                        <div class="event-row">
                            <div class="flex-grow-1">
                                <div class="event-title"><?php echo htmlspecialchars($evento['titulo']); ?></div>
                                <div class="event-date">
                                    <?php
                                        $fecha = date("d M Y", strtotime($evento['fecha_evento']));
                                        $hora = $evento['hora_evento'] ? date("h:i A", strtotime($evento['hora_evento'])) : '';
                                        echo $fecha . ($hora ? " | $hora" : "");
                                    ?>
                                </div>
                                <div class="event-cat"><?php echo htmlspecialchars($evento['categoria'] ?? 'Sin categoría'); ?></div>
                                <a href="paginainfo.php?id=<?php echo $evento['id']; ?>" class="event-link">Saber más &raquo;</a>
                            </div>
                            <?php if (!empty($evento['imagen'])): ?>
                                <img src="<?php echo htmlspecialchars($evento['imagen']); ?>" alt="<?php echo htmlspecialchars($evento['titulo']); ?>" class="event-img">
                            <?php else: ?>
                                <img src="imagenes/evento_default.jpg" alt="Evento" class="event-img">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">No hay eventos disponibles.</div>
                <?php endif; ?>
                </div>
            </div>
            <!-- Sidebar derecho -->
            <div class="col-lg-3 mb-4">
                <div class="sidebar-box text-center">
                    <img src="imagenes/casafeliz.png" alt="Casa Feliz" class="mb-2" style="width: 90%;">
                    <img src="imagenes/latinstudio.png" alt="Latin Studio" class="mb-2" style="width: 90%;">
                    <img src="imagenes/peruvision.png" alt="Peru Vision" style="width: 90%;">
                </div>
                <div class="sidebar-box">
                    <div class="sidebar-title">Gastronomía</div>
                    <div class="mb-2">
                        <img src="imagenes/francesito.jpg" alt="El Francesito" style="width: 45px; height: 45px; object-fit:cover; border-radius:4px; margin-right:8px;">
                        El Francesito
                    </div>
                    <div>
                        <img src="imagenes/sanpedro.jpg" alt="Mercado Central de San Pedro" style="width: 45px; height: 45px; object-fit:cover; border-radius:4px; margin-right:8px;">
                        Mercado Central de San Pedro
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="login-form" method="post" action="login.php" autocomplete="off">
            <div class="modal-header">
              <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <input type="email" class="form-control mb-3" id="login-email" placeholder="Email" required>
              <input type="password" class="form-control mb-3" id="login-password" placeholder="Contraseña" required>
              <div id="login-error" class="text-danger"></div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Buscador de eventos por palabra clave (título, categoría, etc.)
        // Login funcional
        document.getElementById('login-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            const errorDiv = document.getElementById('login-error');
            errorDiv.textContent = '';
            try {
                const res = await fetch('login.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ email, password })
                });
                const data = await res.json();
                if (data.success) {
                    if (data.tipo === 'admin') {
                        window.location.href = 'vista/Admin/listar.php';
                    } else {
                        window.location.href = 'vista/Cliente/listar.php?id_usuario=' + data.id;
                    }
                } else {
                    errorDiv.textContent = 'Credenciales incorrectas o usuario inactivo.';
                }
            } catch (err) {
                errorDiv.textContent = 'Error de conexión. Intente nuevamente.';
            }
        });
    </script>
</body>
</html>
