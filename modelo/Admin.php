<?php
class AdminModelo {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=eventos_culturales_cusco', "root", "");
    }

    public function listarUsuarios($condicion = "1") {
        $sql = "SELECT * FROM usuarios WHERE $condicion";
        $res = $this->db->query($sql);
        return $res ? $res->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function insertarUsuario($nombre, $email, $password, $tipo, $estado = 1) {
        $sql = "INSERT INTO usuarios (nombre, email, password, tipo, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $email, $password, $tipo, $estado]);
    }

    public function actualizarUsuario($id, $nombre, $email, $password, $tipo, $estado) {
        $sql = "UPDATE usuarios SET nombre=?, email=?, password=?, tipo=?, estado=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $email, $password, $tipo, $estado, $id]);
    }

    public function eliminarUsuario($id) {
        $sql = "DELETE FROM usuarios WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function obtenerUsuario($id) {
        $sql = "SELECT * FROM usuarios WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
