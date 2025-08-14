<?php
require_once "../librerias/dompdf-3.1.0/autoload.inc.php";
use Dompdf\Dompdf;
// 1.Conexion a la base de datos
require_once "../modelo/conexion.php";

// 2.Recibir el ID de la venta
$id_venta = isset($_POST['id_venta']) ? (int) $_POST['id_venta'] : 0;
if ($id_venta <= 0) {
    die("ID de venta inválido.");
}

// 3.Consultar datos de venta 
$sqlventa = conexion::conectar() -> prepare("SELECT venta.id_venta, venta.fecha_venta ,venta.total,empleados.nombre as Empleado, empleados.apellido
FROM venta
JOIN empleados on venta.id_empleado=empleados.id
WHERE venta.id_venta=:id_venta");
$sqlventa -> bindParam(':id_venta',$id_venta,PDO::PARAM_INT);
$sqlventa -> execute();
$venta = $sqlventa->fetch(PDO::FETCH_ASSOC);


// 4.Consulta detalle de la venta
$sqldetalle = conexion::conectar() -> prepare("SELECT productos.id as Codigo, productos.nombre, detalle_venta.cantidad,detalle_venta.precio_unitario,detalle_venta.subtotal
FROM detalle_venta
JOIN productos ON detalle_venta.id_producto=productos.id
WHERE detalle_venta.id_venta=:id_venta");
$sqldetalle -> bindParam(':id_venta',$id_venta,PDO::PARAM_INT);
$sqldetalle -> execute();
$detalle = $sqldetalle->fetchAll(PDO::FETCH_ASSOC);

$logoPath = __DIR__ . '/../img/fravega3279.jpg';
$type = pathinfo($logoPath, PATHINFO_EXTENSION);
$data = file_get_contents($logoPath);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

// 5. Crear HTML para la factura
$html='
<style>
body { font-family: Arial, sans-serif; font-size: 12px; }
.header-table { width: 100%; border-bottom: 2px solid #000; margin-bottom: 10px; }
.header-table td { vertical-align: top; }
.empresa { font-size: 7px; line-height: 1.0; }
.factura-b { text-align: center; border: 2px solid #000; font-size: 28px; font-weight: bold; width: 50px; }
.titulo-factura { font-size: 16px; font-weight: bold; }
.cliente-table, .productos { width: 100%; border-collapse: collapse; margin-top: 10px; }
.cliente-table td { padding: 4px; }
.productos th, .productos td { border: 1px solid #000; padding: 5px; text-align: left; }
.productos th { background-color: #f0f0f0; }
.total-table { width: 100%; margin-top: 10px; }
.total-table td { padding: 5px; font-weight: bold; }
.text-right { text-align: right; }
</style>

<div class="watermark">B</div>

<table class="header-table">
    <tr>
        <td><img src="'.$base64.'" width="140"></td>
        <td class="empresa">
            <strong>Fravega S.A.</strong><br>
            CUIT: 30-12345678-9<br>
            Dirección:  Hipólito Yrigoyen 48, B1842BZB Monte Grande, Provincia de Buenos Aires, Argentina<br>
            Tel: (123) 456-7890
        </td>
        <td style="text-align:center;">
            <div class="factura-b">B</div>
            <div class="titulo-factura">FACTURA Nº '.$id_venta.'</div>
            Fecha: '.$venta['fecha_venta'].date("Y-m-d").'
        </td>
    </tr>
</table>

<h1 style="text-align:center;">Factura N°'.$venta['id_venta'].'</h1>
<p><strong>Fecha:</strong>'.$venta['fecha_venta'] .'</p>
<p><strong>Empleado:</strong>'.$venta['Empleado'].' '.$venta['apellido'].'</p>
<hr>
<table border="1" width="100%" cellspacing="0" cellpadding="5">
<thead>
<tr>
<th>Codigo</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Precio Unitario</th>
<th>% Bonif</th>
<th>Imp. Bonif</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody>';
foreach($detalle as $item){
    $html .='<tr>
        <td>'.$item['Codigo'].'</td>
        <td>'.$item['nombre'].'</td>
        <td>'.$item['cantidad'].'</td>
        <td>'.number_format($item['precio_unitario'],2).'</td>
        <td>'. 0 .'</td>
        <td>'. 0 .'</td>
        <td>'.number_format($item['subtotal'],2).'</td>
    </tr>';
}
 
$html .= '</tbody></table>';
$html .= '<h3 style="text-align:right;">Total: $'.number_format($venta['total'], 2).'</h3>';
$html .= '</body></html>';


// 6. Crear el pdf con Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("factura_{$venta['id_venta']}.pdf", ["Attachment" => false]);
exit;