<?php
require_once "../controlador/puesto.controlador.php";
$puestos = controladorpuesto::ctrpuesto();
header('Content-Type: application/json');
echo json_encode($puestos);

?>