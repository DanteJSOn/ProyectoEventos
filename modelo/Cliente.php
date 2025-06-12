<?php
class ClienteModelo {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=eventos_culturales_cusco', "root", "");
    }

    public function listarMisEventos($id_usuario) {
        $sql = "SELECT * FROM eventos WHERE id_usuario=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarEvento($titulo, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen, $id_usuario, $estado = 'pendiente') {
        $sql = "INSERT INTO eventos (titulo, descripcion, fecha_evento, hora_evento, lugar, imagen, id_usuario, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$titulo, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen, $id_usuario, $estado]);
    }

    public function actualizarEvento($id, $titulo, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen, $estado) {
        $sql = "UPDATE eventos SET titulo=?, descripcion=?, fecha_evento=?, hora_evento=?, lugar=?, imagen=?, estado=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$titulo, $descripcion, $fecha_evento, $hora_evento, $lugar, $imagen, $estado, $id]);
    }

    public function eliminarEvento($id) {
        $sql = "DELETE FROM eventos WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function obtenerEvento($id) {
        $sql = "SELECT * FROM eventos WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
