<?php
require_once "../modelo/empleados.modelo.php";
require_once "../controlador/empleados.controlador.php";
class ajaxempleados{
    public $id;
    public $nombre;
    public $apellido;
    public $dni;
    public $puesto;

    public function mostrarempleados(){
        $respuesta = controladorempleados::ctrmostrarempleados();
        echo json_encode($respuesta);
    }

    public function agregarempleados(){
        $respuesta = controladorempleados::ctragregarempleados($this -> nombre,$this -> apellido,$this -> dni,$this -> puesto);
        echo json_encode($respuesta);
    }
    public function eliminarempleados() {
        $respuesta=controladorempleados::ctreliminarempleados($this ->id);
        echo json_encode($respuesta);
    }
    public function modificarempleados() {
        $respuesta=controladorempleados::ctrmodificarempleados($this ->id,$this -> nombre,$this -> apellido,$this -> dni,$this -> puesto);
        echo json_encode($respuesta);
    }
}
if(!isset($_POST["accion"])){
    $respuesta = new ajaxempleados();
    $respuesta -> mostrarempleados();
}else{
    if($_POST["accion"] == "registrar"){
        $registrar = new ajaxempleados();
        $registrar -> nombre =$_POST["nombre"];
        $registrar -> apellido =$_POST["apellido"];
        $registrar -> dni =$_POST["dni"];
        $registrar -> puesto =$_POST["puesto"];
        $registrar -> agregarempleados();
    }
    if($_POST["accion"] == "eliminar"){
        $eliminar = new ajaxempleados();
        $eliminar -> id = $_POST["id"];
        $eliminar ->eliminarempleados();
    }
    if($_POST["accion"] == "modificar"){
        $modificar = new ajaxempleados();
        $modificar -> id = $_POST["id"];
        $modificar -> nombre =$_POST["nombre"];
        $modificar -> apellido =$_POST["apellido"];
        $modificar -> dni =$_POST["dni"];
        $modificar -> puesto =$_POST["puesto"];
        $modificar -> modificarempleados();

    }
}
?>