<?php
include_once("main.php");
$conexion=con();

$fecha_inicio = $_POST["filtro_desde"];
$fecha_fin = $_POST["filtro_hasta"];

// Llamado SQL para enviar a la API

$consulta_datos = "SELECT F.*, C.cliente_nombre, P.descripcion AS forpag_nombre FROM facturas F
                   LEFT JOIN clientes C
                   ON F.cliente_id  = C.cliente_id 
                   LEFT JOIN formas_pago P
                   ON F.forma_pago_id  = P.forma_pago_id 
                   WHERE F.factura_estado = 1 
                   AND F.factura_fecha >= '$fecha_inicio'
                   AND F.factura_fecha <= '$fecha_fin' 
                   ORDER BY F.factura_numero DESC
";
$stmt = $conexion->query($consulta_datos);
$compras = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Generador de PDF
require __DIR__ . "/../vendor/autoload.php";

$filas_reporte = '';
$total = 0;
$row = 0;
foreach($compras as $key) {
    $row++;
    $filas_reporte .= '
        <tr>
            <th scope="row">'.$row.'</th>
            <td>'.$key['factura_numero'].'</td>
            <td>'.$key['factura_fecha'].'</td>
            <td>'.$key['cliente_nombre'].'</td>
            <td>'.$key['forpag_nombre'].'</td>
            <td align="right">'.number_format($key['total_venta'] , 0, ',', '.').'</td>
        </tr>
    ';
    $total = $total + $key['total_venta'];
}

$html = '
    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Reporte</title>
            <style type="text/css">
                * {
                font-family: Verdana, Arial, sans-serif;
                }
                table{
                font-size: x-small;
                }
                tfoot tr td{
                font-weight: bold;
                font-size: x-small;
                }
                .gray {
                background-color: lightgray
                }
            </style>
        </head>
        <body>
            <table width="100%">
                <tr>
                    <td align="center">
                        <h1>Ventas Realizadas</h1>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td align="center" >
                        <strong>Desde:</strong> '.$fecha_inicio.' <strong style="padding-left: 8px">Hasta:</strong> '.$fecha_fin.'
                    </td>
                </tr>
            </table>
            <br/>
            <table width="100%">
                <thead style="background-color: lightgray;">
                    <tr>
                        <th>#</th>
                        <th>Factura</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Condición</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>'.$filas_reporte.'</tbody>
                <tfoot>
                    <!--
                    <tr>
                        <td colspan="3"></td>
                        <td align="right">Subtotal</td>
                        <td align="right">1635.00</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td align="right">Tax</td>
                        <td align="right">294.3</td>
                    </tr>
                    -->
                    <tr>
                        <td colspan="4"></td>
                        <td align="right">Total</td>
                        <td align="right" class="gray">Gs. '.number_format($total , 0, ',', '.').'</td>
                    </tr>
                </tfoot>
            </table>
        </body>
    </html>
';

use Dompdf\Dompdf;
$dompdf = new Dompdf();

$dompdf->loadHtml($html);

$dompdf->render();
$dompdf->stream("reporte_ventas_realizadas_".date("Y-m-d h:i:sa").".pdf", ["Attachment" => 0]);// 0 para visualizar, 1 para descargar