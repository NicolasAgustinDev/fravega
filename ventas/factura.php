<?php
require_once "../librerias/dompdf-3.1.0/autoload.inc.php";
use Dompdf\Dompdf;

// 1.Conexion a la base de datos
require_once "../modelo/conexion.php";

// 2.Recibir el ID de la venta
//$id_venta = $_GET['id_venta'];
$id_venta = isset($_GET['id_venta']) ? (int) $_GET['id_venta'] : 0; 
// 3.Consultar datos de venta 
$sqlventa = conexion::conectar() -> prepare("SELECT venta.id_venta, venta.fecha_venta ,venta.total,empleados.nombre as Empleado 
FROM venta
JOIN empleados on venta.id_empleado=empleados.id
WHERE venta.id_venta=:id_venta");
$sqlventa -> bindParam(':id_venta',$id_venta,PDO::PARAM_INT);
$sqlventa -> execute();
$venta = $sqlventa->fetch(PDO::FETCH_ASSOC);

// 4.Consulta detalle de la venta
$sqldetalle = conexion::conectar() -> prepare("SELECT productos.nombre, detalle_venta.cantidad,detalle_venta.precio_unitario,detalle_venta.subtotal
FROM detalle_venta
JOIN productos ON detalle_venta.id_producto=productos.id
WHERE detalle_venta.id_venta=:id_venta");
$sqldetalle -> bindParam(':id_venta',$id_venta,PDO::PARAM_INT);
$sqldetalle -> execute();
$detalle = $sqldetalle->fetchAll(PDO::FETCH_ASSOC);

if (!$venta) {
    die("No se encontró la venta con ID $id_venta");
}

// 5. Crear HTML para la factura
$html='
<h1 style="text-align:center;">Factura N°'.$venta['id_venta'].'</h1>
<p><strong>Fecha:</strong>'.$venta['fecha_venta'].'</p>
<p><strong>Empleado:</strong>'.$venta['nombre'].'</p>
<hr>
<table border="1" width="100%" cellspacing="0" cellpadding="5">
<thead>
<tr>
<th>Producto</th>
<th>Cantidad</th>
<th>Precio Unitario</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody>';
foreach($detalle as $item){
    $html .='<tr>
        <td>'.$item['nombre'].'</td>
        <td>'.$item['cantidad'].'</td>
        <td>'.number_format($item['precio_unitario'],2).'</td>
        <td>'.number_format($item['subtotal'],2).'</td>
    </tr>';
}
 
$html .= '<tbody>
</table>
<h3 style="text-align:right;">Total: $'.number_format($venta['total'], 2).'</h3>
';
// 6. Crear el pdf con Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("factura_{$venta['id_venta']}.pdf", ["Attachment" => false]);
?> 