<?php
require_once("../../modelo/admin.php");
$admin = new AdminModelo();

if (isset($_GET['id'])) {
    $admin->eliminarUsuario($_GET['id']);
}
header('Location: listar.php');
exit;
?>