<?php
require_once "conexion.php";

class modelopuesto{
    static public function mdlpuesto(){
        $st = conexion::conectar() -> prepare("SELECT id_puesto, puesto FROM puesto");
        $st -> execute();
        return $st -> fetchAll(PDO::FETCH_ASSOC);
    }
} 

?>