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
?>