<?php
require_once "../modelo/ventas.modelo.php";

class controladorventas {

    static public function ctrventas($fecha,$id_empleado,$total){
        $respuesta = modeloventas::mdlagregarventa($fecha,$id_empleado,$total);
        return $respuesta;
    }

    static public function ctrdetalleventas($id_venta,$id_producto,$cantidad,$precio_unitario,$subtotal){
        $respuesta = modeloventas::mdlagregardetalleventa($id_venta,$id_producto,$cantidad,$precio_unitario,$subtotal);
        return $respuesta;
    }

}

?>