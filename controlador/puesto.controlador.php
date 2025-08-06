<?php
require_once "../modelo/puesto.modelo.php";

class controladorpuesto{
    static public function ctrpuesto(){
        return modelopuesto::mdlpuesto();
    }
}

?>