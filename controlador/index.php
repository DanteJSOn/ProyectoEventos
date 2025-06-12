<?php
    require_once("modelo/index.php");

    class modeloController{
        //Atributos de la clase
        private $model;

        public function __construct()
        {
            $this->model = new Modelo();
        }

        // Mostrar eventos (principal)
        static function index()
        {
            $evento  = new Modelo();
            $dato    = $evento->mostrar("eventos", "1");
            require_once("vista/index.php");
        }

        // Nuevo evento
        static function nuevo()
        {
            require_once("vista/nuevo.php");
        }

        // Guardar evento
        static function guardar()
        {
            $titulo        = $_REQUEST['titulo'];
            $descripcion   = $_REQUEST['descripcion'];
            $fecha_evento  = $_REQUEST['fecha_evento'];
            $hora_evento   = $_REQUEST['hora_evento'];
            $lugar         = $_REQUEST['lugar'];
            $imagen        = $_REQUEST['imagen'];
            $id_usuario    = $_REQUEST['id_usuario'];
            $estado        = $_REQUEST['estado'];
            $data = "'$titulo','$descripcion','$fecha_evento','$hora_evento','$lugar','$imagen',$id_usuario,'$estado'";
            $evento = new Modelo();
            $evento->insertar("eventos", $data);
            header("location:".urlsite);
        }

        // Editar evento
        static function editar()
        {
            $id = $_REQUEST['id'];
            $evento = new Modelo();
            $dato = $evento->mostrar("eventos", "id=".$id);
            require_once("vista/editar.php");
        }

        // Actualizar evento
        static function actualizar()
        {
            $id            = $_REQUEST['id'];
            $titulo        = $_REQUEST['titulo'];
            $descripcion   = $_REQUEST['descripcion'];
            $fecha_evento  = $_REQUEST['fecha_evento'];
            $hora_evento   = $_REQUEST['hora_evento'];
            $lugar         = $_REQUEST['lugar'];
            $imagen        = $_REQUEST['imagen'];
            $id_usuario    = $_REQUEST['id_usuario'];
            $estado        = $_REQUEST['estado'];
            $data = "titulo='$titulo',descripcion='$descripcion',fecha_evento='$fecha_evento',hora_evento='$hora_evento',lugar='$lugar',imagen='$imagen',id_usuario=$id_usuario,estado='$estado'";
            $evento = new Modelo();
            $evento->actualizar("eventos", $data, "id=".$id);
            header("location:".urlsite);
        }

        // Eliminar evento
        static function eliminar()
        {
            $id = $_REQUEST['id'];
            $evento = new Modelo();
            $evento->eliminar("eventos", "id=".$id);
            header("location:".urlsite);
        }
    }
?>