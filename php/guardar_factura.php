<?php
require_once("main.php");
require_once("../inc/session_start.php");

// Obtener los datos del objeto JSON
$data = json_decode(file_get_contents('php://input'), true);

$fecha = getFecha();

$nombre = $data['nombre'];
$ruc = $data['ruc'];
$productos = $data['productos'];

$totalVenta = $data['totalVenta'];
$totalVenta = (int)$totalVenta;
$totalVentaES = new NumberFormatter('es', NumberFormatter::SPELLOUT);
$totalVentaES = $totalVentaES->format($totalVenta);

$usuario = $_SESSION["nombre"].$_SESSION["apellido"];

// Generar el contenido HTML de la factura
$facturaHTML = '
    <html>
    <head>
        <title>Factura de Venta</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <link rel="stylesheet" href="./css/factura.css">
    </head>
    <body>
    <header class="">
      <div id="company" class="clearfix">
        <div id="logo">
          <img src="./img/brand.png">
          <div>Premium Market S.A.</div>
          <div>Rca. Argentina 111<br /> Asunción</div>
          <div>(021) 519-0450</div>
          <div><a href="mailto:company@example.com">info@premium.com</a></div>
        </div>
      </div>
      <div id="project">
        <div id="timbrado">
          <div>TIMBRADO: 145145145</div>
          <div>FECHA INICIO VIGENCIA: 01/01/2023</div>
          <div>FECHA FIN VIGENCIA: 31/12/2024</div>
        </div>

          <div><strong>RUC:</strong><br />80012345-6</div>
          <div><strong>FACTURA:</strong><br />001-001-0000001</div>

      </div>
    </header>
    <div class="cliente">
      <div>Fecha: '.$fecha.' </div>
      <div>Condicion de Venta: Contado</div>
      <div>Nombre: ' . $nombre . '</div>
      <div>RUC: ' . $ruc . '</div>
      <div>Direccion:</div>
      <div>Nota de remisión:</div>
    </div>
    <main>
      <table class="table is-bordered">
        <thead>
          <tr>
            <th rowspan="2">CODIGO</th>
            <th rowspan="2" class="service">PRODUCTO</th>
            <th rowspan="2" class="desc">CANTIDAD</th>
            <th rowspan="2">PRECIO</th>
            <th colspan="3">VALOR DE VENTA</th>
          </tr>
          <tr>
            <th>Exenta</th>
            <th>5%</th>
            <th>10%</th>
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
        <br>
        <h3>Total a pagar: ' . $totalVenta . '</h3>
        <h3>Total a pagar en letras: ' . $totalVentaES . '</h3>
        </body>
    </html>';

// Responder con el contenido HTML de la factura
echo $facturaHTML;
?>