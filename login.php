<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['success' => false]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=eventos_culturales_cusco', 'root', '');
    $stmt = $pdo->prepare("SELECT id, tipo, estado FROM usuarios WHERE email=? AND password=? LIMIT 1");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && $user['estado'] == 1) {
        echo json_encode(['success' => true, 'tipo' => $user['tipo'], 'id' => $user['id']]);
    } else {
        echo json_encode(['success' => false]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false]);
}
?>
