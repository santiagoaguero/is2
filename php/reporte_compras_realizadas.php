<?php
include_once("main.php");
$conexion=con();

$fecha_inicio = $_POST["filtro_desde"];
$fecha_fin = $_POST["filtro_hasta"];

// Llamado SQL para enviar a la API

$consulta_datos = "SELECT C.*, P.prov_nombre FROM compras C
                   LEFT JOIN proveedor P
                   ON C.prov_id = P.prov_id
                   WHERE C.compra_estado = 1 
                   AND C.compra_fecha >= '$fecha_inicio'
                   AND C.compra_fecha <= '$fecha_fin' 
                   ORDER BY C.compra_fecha DESC
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
            <td>'.$key['compra_factura'].'</td>
            <td>'.$key['compra_fecha'].'</td>
            <td>'.$key['prov_nombre'].'</td>
            <td>'.($key['compra_condicion'] == 1 ? 'Contado' : 'Crédito' ).'</td>
            <td align="right">'.number_format($key['compra_total'] , 0, ',', '.').'</td>
        </tr>
    ';
    $total = $total + $key['compra_total'];
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
                        <h1>Compras Realizadas</h1>
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
                        <th>Proveedor</th>
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
$dompdf->stream("reporte_compras_realizadas_".date("Y-m-d h:i:sa").".pdf", ["Attachment" => 0]);// 0 para visualizar, 1 para descargar