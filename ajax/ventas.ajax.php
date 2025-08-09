<?php
require_once "../modelo/ventas.modelo.php";
require_once "../controlador/ventas.controlador.php";

class ajaxventas{
    public $fecha;
    public $id_empleado;
    public $total;
    public $id_venta ;
    public $id_producto;
    public $cantidad;
    public $precio_unitario;
    public $subtotal;

    public function agregarventas(){
        $respuesta = controladorventas::ctrventas($this -> fecha,$this -> id_empleado,$this -> total);
        echo json_encode($respuesta);
    }

    public function agregardetalleventas(){
        $respuesta = controladorventas::ctrdetalleventas($this -> id_venta,$this -> id_producto,$this -> cantidad,$this -> precio_unitario,$this -> subtotal);
        echo json_encode($respuesta);
    }
}
if($_POST){
    $guardar = new ajaxventas();
    $guardar -> fecha = $_POST["fecha"];
    $guardar -> id_empleado = $_POST["id_empleado"];
    $guardar -> total = $_POST["total"];
    $guardar -> agregarventas();
}
?>