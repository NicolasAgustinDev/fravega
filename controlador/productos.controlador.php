<?php
require_once "../modelo/productos.modelo.php";

class controladorproductos{
    static public function ctrmostrarproductos(){
        $respuesta =  modeloproductos::mdlmostrarproductos();
        return $respuesta;
    }

    static public function ctragregarproductos($nombre,$precio,$cantidad){
        $respuesta = modeloproductos::mdlagregarproducto($nombre,$precio,$cantidad);
        return $respuesta;
    }
    static public function ctreliminarproducto($id){
        $respuesta = modeloproductos::mdleliminarproducto($id);
        return $respuesta;
    }
    static public function ctrmodificarproducto($id,$nombre,$precio,$cantidad){
        $respuesta = modeloproductos::mdlmodificarproducto($id,$nombre,$precio,$cantidad);
        return $respuesta;
    }

}
?>