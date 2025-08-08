<?php
require_once "../modelo/empleados.modelo.php";

class controladorempleados{
    static public function ctrmostrarempleados(){
        $respuesta =  modeloempleados::mdlmostrarempleados();
        return $respuesta;
    }

    static public function ctragregarempleados($nombre,$apellido,$dni,$puesto){
        $respuesta = modeloempleados::mdlagregarempleado($nombre,$apellido,$dni,$puesto);
        return $respuesta;
    }

    static public function ctreliminarempleados($id){
        $respuesta = modeloempleados::mdleliminarempleados($id);
        return $respuesta;
    }
    static public function ctrmodificarempleados($id,$nombre,$apellido,$dni,$puesto){
        $respuesta = modeloempleados::mdlmodificarempleados($id,$nombre,$apellido,$dni,$puesto);
        return $respuesta;
    }
}
?>