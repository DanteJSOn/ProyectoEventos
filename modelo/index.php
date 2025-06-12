<?php
    class Modelo{

        //Atributos de la clase
        private $db;
        private $datos;

        //Metodos de la clase
        public function __construct()
        {
            // Conectar a la base de datos correcta
            $this->db = new PDO('mysql:host=localhost;dbname=eventos_culturales_cusco', "root", "");
            $this->datos = [];
        }

        public function insertar($tabla, $data)
        {
            $consulta = "INSERT INTO $tabla VALUES (null, $data)";
            $resultado = $this->db->query($consulta);
            return $resultado ? true : false;
        }

        public function mostrar($tabla, $condicion = "1")
        {
            $consul = "SELECT * FROM $tabla WHERE $condicion;";
            $resu = $this->db->query($consul);
            if ($resu) {
                $this->datos = $resu->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $this->datos = [];
            }
            return $this->datos;
        }

        public function actualizar($tabla, $data, $condicion)
        {
            $consulta = "UPDATE $tabla SET $data WHERE $condicion";
            $resultado = $this->db->query($consulta);
            return $resultado ? true : false;
        }

        public function eliminar($tabla, $condicion)
        {
            $eli = "DELETE FROM $tabla WHERE $condicion";
            $res = $this->db->query($eli);
            return $res ? true : false;
        }

        // Consulta personalizada para consultas complejas (por ejemplo, rankings)
        public function consultaPersonalizada($sql)
        {
            $resu = $this->db->query($sql);
            if ($resu) {
                return $resu->fetchAll(PDO::FETCH_ASSOC);
            }
            return [];
        }
    }
?>