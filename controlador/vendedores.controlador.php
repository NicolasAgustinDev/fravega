<?php
require_once "../modelo/empleados.modelo.php";

class controladorvendedores{
    static public function ctrvendedores(){
        $respuesta = modeloempleados::mdlmostrarvendedores();
        return $respuesta;
    }
}

?>