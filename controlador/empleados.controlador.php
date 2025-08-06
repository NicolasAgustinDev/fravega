<?php
require_once "../modelo/empleados.modelo.php";

class controladorempleados{
    static public function ctrmostrarempleados(){
        $respuesta =  modeloempleados::mdlmostrarempleados();
        return $respuesta;
    }
}
?>