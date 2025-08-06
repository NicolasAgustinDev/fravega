<?php
require_once "../modelo/empleados.modelo.php";
require_once "../controlador/empleados.controlador.php";
class ajaxempleados{
    public $id;
    public $nombre;
    public $apellido;
    public $DNI;
    public $cargo;

    public function mostrarempleados(){
        $respuesta = controladorempleados::ctrmostrarempleados();
        echo json_encode($respuesta);
    }
}
if(!isset($_POST["accion"])){
    $respuesta = new ajaxempleados();
    $respuesta -> mostrarempleados();
}
?>