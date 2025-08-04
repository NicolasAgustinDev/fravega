<?php
require_once "conexion.php";

class modeloproductos {

    static public function mdlmostrarproductos(){
        $st = conexion::conectar() -> prepare("SELECT * FROM productos");
        $st -> execute();
        return $st -> fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlagregarproducto($nombre,$precio,$cantidad){
        $st = conexion::conectar() -> prepare("INSERT into productos(nombre, precio,cantidad) VALUES(:nombre,:precio,:cantidad)");
        $st -> bindParam(":nombre",$nombre,PDO::PARAM_STR);
        $st -> bindParam(":precio",$precio,PDO::PARAM_INT);
        $st -> bindParam(":cantidad",$cantidad,PDO::PARAM_INT);

        if($st->execute()){
            echo "El producto se registro correctamente";
        }else{
            echo "El producto no se pudo registrar";
        }
    }

    static public function mdleliminarproducto($id){
        $st = conexion::conectar() -> prepare("DELETE FROM productos WHERE id = :id");
        $st -> bindParam(":id",$id,PDO::PARAM_INT);
        if($st->execute()){
            echo "El producto se elimino correctamente";
        }else{
            echo "El producto no se pudo eliminar";
        }
    }
}


?>