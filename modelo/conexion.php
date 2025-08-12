<?php
class conexion {
    static public function conectar() {
        try {
            $conec = new PDO("mysql:host=localhost;dbname=fravega", "root", "");
            $conec->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conec;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }
}
/* SELECT venta.id_venta,detalle_venta.cantidad,detalle_venta.id_producto,productos.nombre,detalle_venta.precio_unitario,detalle_venta.subtotal FROM venta JOIN detalle_venta on venta.id_venta=detalle_venta.id_venta JOIN productos on detalle_venta.id_producto=productos.id ORDER BY venta.id_venta; */
?>