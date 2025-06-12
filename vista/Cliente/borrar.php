<?php
require_once("../../modelo/cliente.php");
$cliente = new ClienteModelo();
$id_usuario = $_GET['id_usuario'] ?? 1;

if (isset($_GET['id'])) {
    $cliente->eliminarEvento($_GET['id']);
}
header("Location: listar.php?id_usuario=$id_usuario");
exit;
?>