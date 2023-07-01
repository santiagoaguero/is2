<?php
require_once("main.php");
require_once("../inc/session_start.php");
// Obtener los datos del objeto JSON
$data = json_decode(file_get_contents('php://input'), true);

$nombre = $data['nombre'];
$ruc = $data['ruc'];
$productos = $data['productos'];
$usuario = $_SESSION["nombre"].$_SESSION["apellido"];

// Generar el contenido HTML de la factura
$facturaHTML = '
    <html>
    <head>
        <title>Factura de Venta</title>
        <style>
            /* Estilos CSS para la factura */
            /* ... */
        </style>
    </head>
    <body>
        <h1>Factura de Venta</h1>
        <p><strong>Nombre:</strong> ' . $nombre . '</p>
        <p><strong>RUC:</strong> ' . $ruc . '</p>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';

foreach ($productos as $producto) {
    $nombreProducto = $producto['nombre'];
    $cantidad = $producto['cantidad'];
    $precioUnitario = $producto['precio'];
    $total = $producto['total'];

    $facturaHTML .= '
        <tr>
            <td>' . $nombreProducto . '</td>
            <td>' . $cantidad . '</td>
            <td>' . $precioUnitario . '</td>
            <td>' . $total . '</td>
        </tr>';
}

$facturaHTML .= '
            </tbody>
        </table>
    </body>
    </html>';

// Responder con el contenido HTML de la factura
echo $facturaHTML;
?>