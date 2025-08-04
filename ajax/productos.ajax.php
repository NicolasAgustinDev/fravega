<?php
require_once "../controlador/productos.controlador.php";
require_once "../modelo/productos.modelo.php";

class ajaxproductos {
    public $id ;
    public $nombre;
    public $precio;
    public $cantidad;

    public function mostrarproductos(){
        $respuesta = controladorproductos::ctrmostrarproductos();
        echo json_encode($respuesta);
    }

    public function agregarproductos(){
        $respuesta = controladorproductos::ctragregarproductos($this -> nombre,$this -> precio,$this -> cantidad);
        echo json_encode($respuesta);
    }

    public function eliminarproductos(){
        $respuesta = controladorproductos::ctreliminarproducto($this -> id);
        echo json_encode($respuesta);
    }
}
if(!isset($_POST["accion"])){
    $respuesta = new ajaxproductos();
    $respuesta -> mostrarproductos();
}else{
    if($_POST["accion"] == "registrar"){
        $registrar = new ajaxproductos();
        $registrar -> nombre =$_POST["nombre"];
        $registrar -> precio =$_POST["precio"];
        $registrar -> cantidad =$_POST["cantidad"];
        $registrar -> agregarproductos();
    }
    if($_POST["accion"] == "eliminar"){
        $eliminar = new ajaxproductos();
        $eliminar -> id =$_POST["id"];
        $eliminar -> eliminarproductos();
    }

}


?>