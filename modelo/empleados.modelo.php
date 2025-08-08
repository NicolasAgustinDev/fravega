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

    static public function mdlmostrarvendedores(){
        $st = conexion::conectar() -> prepare("SELECT * FROM `empleados` WHERE puesto=5");
        $st -> execute();
        return $st -> fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlagregarempleado($nombre,$apellido,$dni,$puesto){
        $st = conexion::conectar() -> prepare("INSERT into empleados(nombre,apellido,dni,puesto) VALUES(:nombre,:apellido,:dni,:puesto)");
        $st -> bindParam(":nombre",$nombre,PDO::PARAM_STR);
        $st -> bindParam(":apellido",$apellido,PDO::PARAM_STR);
        $st -> bindParam(":dni",$dni,PDO::PARAM_INT);
        $st -> bindParam(":puesto",$puesto,PDO::PARAM_INT);
        if($st->execute()){
            echo "El producto se registro correctamente";
        }else{
            echo "El producto no se pudo registrar";
        }
    }

    static public function mdleliminarempleados($id){
        $st = conexion::conectar() ->prepare("DELETE FROM empleados WHERE id = :id");
        $st -> bindParam(":id",$id,PDO::PARAM_INT);
        if($st->execute()){
            echo "El producto se elimino correctamente";
        }else{
            echo "El producto no se pudo eliminar";
        }
    }

    static public function mdlmodificarempleados($id,$nombre,$apellido,$dni,$puesto){
        $st = conexion::conectar() -> prepare("UPDATE empleados SET nombre=:nombre ,apellido=:apellido ,dni=:dni,puesto=:puesto WHERE id=:id");
        $st -> bindParam(":id",$id,PDO::PARAM_INT);
        $st -> bindParam(":nombre",$nombre,PDO::PARAM_STR);
        $st -> bindParam(":apellido",$apellido,PDO::PARAM_STR);
        $st -> bindParam(":dni",$dni,PDO::PARAM_INT);
        $st -> bindParam(":puesto",$puesto,PDO::PARAM_INT);
        if($st->execute()){
            echo "El producto se modifico correctamente";
        }else{
            echo "El producto no se pudo modificar";
        }
    }



}
?>