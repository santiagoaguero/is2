<?php
include_once("main.php");
$conexion=con();

$filtro_categoria = ($_POST["filtro_categoria"] == '') ? '1=1' : 'P.categoria_id = '.$_POST["filtro_categoria"];
$filtro_familia = ($_POST["filtro_familia"] == '') ? '1=1' : 'P.familia_id = '.$_POST["filtro_familia"];
$filtro_deposito = ($_POST["filtro_deposito"] == '') ? '1=1' : 'P.deposito_id = '.$_POST["filtro_deposito"];

// Llamado SQL para enviar a la API

$consulta_datos = "SELECT P.*, X.prov_nombre FROM producto P
                   LEFT JOIN proveedor X
                   ON P.prov_id = X.prov_id
                   WHERE $filtro_categoria 
                   AND $filtro_familia
                   AND $filtro_deposito 
                   ORDER BY P.producto_nombre ASC
";
$stmt = $conexion->query($consulta_datos);
$stock = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generador de PDF
require __DIR__ . "/../vendor/autoload.php";

$filas_reporte = '';
$row = 0;
foreach($stock as $key) {
    $row++;
    $filas_reporte .= '
        <tr>
            <th scope="row">'.$row.'</th>
            <td>'.$key['producto_codigo'].'</td>
            <td>'.$key['producto_nombre'].'</td>
            <td>'.$key['prov_nombre'].'</td>
            <td>'.$key['producto_stock'].'</td>
            <td>'.$key['producto_stock_min'].'</td>
        </tr>
    ';
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
                        <h1>Stock Actual</h1>
                    </td>
                </tr>
            </table>
            <br/>
            <table width="100%">
                <thead style="background-color: lightgray;">
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Proveedor</th>
                        <th>Stock</th>
                        <th>Stock Min.</th>
                    </tr>
                </thead>
                <tbody>'.$filas_reporte.'</tbody>
            </table>
        </body>
    </html>
';

use Dompdf\Dompdf;
$dompdf = new Dompdf();

$dompdf->loadHtml($html);

$dompdf->render();
$dompdf->stream("report_stock_".date("Y-m-d h:i:sa").".pdf", ["Attachment" => 0]);// 0 para visualizar, 1 para descargar