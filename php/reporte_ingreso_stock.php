<?php
include_once("main.php");
include_once("../inc/api_keys.php");
$conexion=con();

$fecha_inicio = $_POST["filtro_desde"];
$fecha_fin = $_POST["filtro_hasta"];

// Llamado SQL para enviar a la API

$consulta_datos = "SELECT factura_numero, factura_fecha 
                   FROM facturas 
                   WHERE factura_estado = 1 
                   AND factura_fecha >= '$fecha_inicio'
                   AND factura_fecha <= '$fecha_fin' 
";
$stmt = $conexion->query($consulta_datos);
$facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$facturas_json = json_encode($facturas);

$consulta_datos = "SELECT D.factura_numero, D.producto_id, D.cantidad
                   FROM detalle_factura D
                   LEFT JOIN facturas F 
                   ON D.factura_numero = F.factura_numero
                   WHERE F.factura_estado = 1 
                   AND F.factura_fecha >= '$fecha_inicio'
                   AND F.factura_fecha <= '$fecha_fin' 
";
$stmt = $conexion->query($consulta_datos);
$detfacturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
$detfacturas_json = json_encode($detfacturas);

$consulta_datos = "SELECT P.producto_id, P.producto_codigo, P.producto_nombre, P.producto_precio_compra, P.producto_stock, P.producto_stock_min FROM producto P";
$stmt = $conexion->query($consulta_datos);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$productos_json = json_encode($productos);

// Llamado la API

$url = 'https://api.openai.com/v1/chat/completions';

$solicitud = 'Dame los productos que debo comprar y su cantidad en (cantidad_a_comprar). Si no necesito comprar un producto no me lo devuelvas en el json. Si tienes datos de años anteriores (basándote en factura_fecha), traéme la cantidad necesaria para mi stock. El stock actual (producto_stock) debe igualar o superar al stock mínimo (producto_stock_min). Si ya tengo stock suficiente(producto_stock), no hace falta que me des para comprar más.  
    Devuelve la respuesta en formato JSON en 1 sólo array sin textos de más con los siguientes campos: producto_nombre, producto_codigo, cantidad_a_comprar, precio_unitario. Sólo quiero json como respuesta, no digas más.
    Tabla productos: '.$productos_json.'
    Tabla facturas: '.$facturas_json.'
    Tabla detalle_facturas: '.$detfacturas_json.'
';
 
$data = [
    'model' => 'gpt-4',
    'messages' => [
        ['role' => 'user', 'content' => $solicitud]
    ],
    'temperature'=> 0,
    'top_p'=> 1,
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . OPEN_AI_API_KEY
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if ($response === false) {
    echo 'Error de cURL: ' . curl_error($ch);
    exit;
} else {
    $response_data = json_decode($response, true);
    
    if (isset($response_data['error'])) {
        echo 'Error de la API: ' . $response_data['error']['message'];
        exit;
    } elseif (isset($response_data['choices'])) {
        $api_response = $response_data['choices'][0]['message']['content'];
    } else {
        echo 'Error: La respuesta de la API no contiene la clave "choices".';
        exit;
    }
}
curl_close($ch);
$report_data = json_decode($api_response, true);

// Generador de PDF
require __DIR__ . "/../vendor/autoload.php";

$filas_reporte = '';
$total = 0;
$row = 0;
foreach($report_data as $key) {
    $row++;
    $filas_reporte .= '
        <tr>
            <th scope="row">'.$row.'</th>
            <td>'.$key['producto_codigo'].'</td>
            <td>'.$key['producto_nombre'].'</td>
            <td>'.$key['cantidad_a_comprar'].'</td>
            <td>'.number_format($key['precio_unitario'], 0, ',', '.').'</td>
            <td>'.number_format($key['precio_unitario']*$key['cantidad_a_comprar'], 0, ',', '.').'</td>
        </tr>
    ';
    $total = $total + ($key['precio_unitario']*$key['cantidad_a_comprar']);
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
                        <h1>Stock a Ingresar</h1>
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
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>A Solicitar</th>
                        <th>Precio Unitario</th>
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
$dompdf->stream("report_stock_income_".date("Y-m-d h:i:sa").".pdf", ["Attachment" => 1]);// 0 para visualizar, 1 para descargar