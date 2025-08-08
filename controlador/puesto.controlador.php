<?php
require_once "../modelo/puesto.modelo.php";

class controladorpuesto{
    static public function ctrpuesto(){
        $respuesta = modelopuesto::mdlpuesto();
        return $respuesta;
    }
}

?>