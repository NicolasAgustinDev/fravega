<?php
require_once "../modelo/empleados.modelo.php";
require_once "../controlador/vendedores.controlador.php";

$vendedores = controladorvendedores::ctrvendedores();
header('Content-Type: application/json');
echo json_encode($vendedores);


?>