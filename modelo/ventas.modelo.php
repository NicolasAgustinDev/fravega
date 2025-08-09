<?php
require_once "conexion.php";

class modeloventas{

    static public function mdlagregarventa($fecha,$id_empleado,$total){
        $conexion = conexion::conectar();
        $st= $conexion -> prepare("INSERT INTO venta(fecha_venta,id_empleado,total) VALUES(:fecha,:id_empleado,:total)");
        $st -> bindParam(":fecha",$fecha,PDO::PARAM_STR);
        $st -> bindParam(":id_empleado",$id_empleado,PDO::PARAM_INT);
        $st -> bindParam(":total",$total,PDO::PARAM_INT);

        if($st -> execute()){
            $id_venta = $conexion -> lastInsertId();
            return $id_venta;
        }else{
            return false;
        }
    }

    static public function mdlagregardetalleventa($id_venta,$id_producto,$cantidad,$precio_unitario,$subtotal){
        $st= conexion::conectar() ->prepare("INSERT INTO detalle_venta(id_venta,id_producto,cantidad,precio_unitario,subtotal) VALUES(:id_venta,:id_producto,:cantidad,:precio_unitario,:subtotal)");
        $st -> bindParam(":id_venta",$id_venta,PDO::PARAM_INT);
        $st -> bindParam(":id_producto",$id_producto,PDO::PARAM_INT);
        $st -> bindParam(":cantidad",$cantidad,PDO::PARAM_INT);
        $st -> bindParam(":precio_unitario",$precio_unitario,PDO::PARAM_INT);
        $st -> bindParam(":subtotal",$subtotal,PDO::PARAM_INT);
        

        if($st -> execute()){
            echo "Los detalles de la venta se registraron correctamente";

        }else{
            echo "Los detalles de la venta no se registraron ";
        }
    }
}

?>