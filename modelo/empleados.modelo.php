<?php
require_once "conexion.php";

class modeloempleados{
    public static function mdlmostrarempleados(){
        $st = conexion::conectar() -> prepare("SELECT empleados.id ,empleados.nombre ,empleados.apellido ,empleados.dni ,puesto.puesto 
                                            FROM empleados
                                            JOIN puesto
                                            ON empleados.puesto=puesto.id_puesto");
        $st -> execute();
        return $st -> fetchAll(PDO::FETCH_ASSOC);
    }
}
?>