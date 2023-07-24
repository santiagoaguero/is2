<?php
require_once("main.php");
require_once("../inc/session_start.php");

// Obtener los datos del objeto JSON
$data = json_decode(file_get_contents('php://input'), true);
$ruc = $data['ruc'];
$productos = $data['productos'];

//busca cliente para asociar a la factura
$busca_cliente = "SELECT cliente_id, cliente_nombre, cliente_direccion FROM clientes WHERE cliente_ruc = '$ruc'";
$conexion=con();
$cliente = $conexion->query($busca_cliente);
$cliente = $cliente->fetch();

$clienteId = $cliente["cliente_id"];
$cliente_nom = $cliente["cliente_nombre"];
$cliente_dir = $cliente["cliente_direccion"];

//busca ultimo numero de factura
$consulta = "SELECT presup_numero FROM presup ORDER BY presup_numero DESC LIMIT 1";
$resultado = $conexion->query($consulta);

// Verificar si la consulta tuvo resultados
if ($resultado->rowCount() > 0) {
    // Obtener el número de factura
    $fila = $resultado->fetch();
    $ultimoNumero = $fila['presup_numero'];
    $nuevoNumero = incrementarNumeroFactura($ultimoNumero);
} else {
    // No se encontraron registros en la tabla
    $nuevoNumero = '001-001-0000001';
}

// Función para incrementar el número de factura
function incrementarNumeroFactura($ultimoNumero) {
  $parts = explode("-", $ultimoNumero);
  $part1 = intval($parts[0]);
  $part2 = intval($parts[1]);
  $part3 = intval($parts[2]);
  $nextPart3 = $part3 + 1;
  $nuevoNumero = sprintf("%03d-%03d-%07d", $part1, $part2, $nextPart3);
  return $nuevoNumero;
}

$fechaES = getFechaES();//1 de julio de 2023
$fechaFact = getFechaFact();// 01-07-2023

$totalIva0 = 0;
$totalIva5 = 0;
$totalIva10 = 0;

$usuarioId = $_SESSION["id"];
$usuario_nom = $_SESSION["nombre"]." ".$_SESSION["apellido"];

// Generar el contenido HTML de la factura
$facturaHTML = '
    <html>
    <head>
        <title>Presupuesto de Venta</title>
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

          <div><strong>RUC:</strong><br />80012345-6</div>
          <div><strong>PRESUPUESTO:</strong><br />'.$nuevoNumero.'</div>

      </div>
    </header>
    <div class="cliente">
      <div>Fecha: '.$fechaES.' </div>
      <div>Nombre: ' . $cliente_nom . '</div>
      <div>RUC: ' . $ruc . '</div>
      <div>Direccion: ' . $cliente_dir . '</div>
    </div>
    <main>
      <table>
        <thead>
          <tr>
            <th class="cabecera">CODIGO</th>
            <th class="cabecera" >PRODUCTO</th>
            <th class="cabecera" >CANTIDAD</th>
            <th class="cabecera" >PRECIO UNITARIO</th>
            <th class="cabecera" >Exenta</th>
            <th class="cabecera" >5%</th>
            <th class="cabecera" >10%</th>
          </tr>
        </thead>
        <tbody>';

foreach ($productos as $producto) {
  $precioIva = $producto["precio"] * $producto["cantidad"];
  $facturaHTML .= '
  <tr>
    <td>'.$producto['id'].'</td>
    <td>'.$producto['nombre'].'</td>
    <td>'.$producto['cantidad'].'</td>
    <td>'.$producto['precio'].'</td>';
    switch($producto['iva']){
      case 10:
        $totalIva10 += $precioIva;
        $facturaHTML .= '
        <td>0</td>
        <td>0</td>
        <td>'.$precioIva.'</td>
        ';
        break;
      case 5:
        $totalIva5 += $precioIva;
        $facturaHTML .= '
        <td>0</td>
        <td>'.$precioIva.'</td>
        <td>0</td>
        ';
        break;
      case 0:
        $totalIva0 += $precioIva;
        $facturaHTML .= '
        <td>'.$precioIva.'</td>
        <td>0</td>
        <td>0</td>
        ';
        break;
    }
$facturaHTML .=
  '</tr>';
}

$totalVenta = $totalIva0 + $totalIva5 + $totalIva10;
$totalVentaES = new NumberFormatter('es', NumberFormatter::SPELLOUT);
$totalVentaES = $totalVentaES->format($totalVenta);
$iva10 = round( $totalIva10 / 11);
$iva5 = round($totalIva5 / 21);

$facturaHTML .= '
            </tbody>
            <tfoot style="margin-top: 10px;">
              <tr>
                <td colspan="4" style="text-align: left; font-weight: 600">Subtotal:</td>
                <td class="foot">'.$totalIva0.'</td>
                <td class="foot">'.$totalIva5.'</td>
                <td class="foot">'.$totalIva10.'</td>
              </tr>
              <tr>
                <td colspan="5" style="text-align: left; font-weight: 600">Total IVA:</td>
                <td>' . $iva5 . '</td>
                <td>' . $iva10 . '</td>
              </tr>
              <tr>
                <td colspan="6" style="text-align: left; font-weight: 600">Total a pagar:</td>
                <td>'. $totalVenta.'</td>
              </tr>
              <tr>
                <td colspan="2" style="text-align: left; font-weight: 600">Total a pagar en letras:</td>
                <td colspan="5">' . $totalVentaES . '</td>
              </tr>
              <tr>
                <td colspan="2" style="border: none; background: none;"><p style="color:#C1CED9;">Atendido por: '.$usuario_nom.'</p></td>
                <td colspan="3" style="border: none; background: none;"></td>
                <td style="border: none; background: none;"><p style="color:#C1CED9;">Válido:</p></td>
                <td style="border: none; background: none;"><p style="color:#C1CED9;">7 días</p></td>
              </tr>
            </tfoot>

        </table>
        </body>
    </html>';

// Consulta preparada para insertar la nueva factura en la base de datos
$consultaInsertar = "INSERT INTO presup (presup_fecha, cliente_id, usuario_id, total_presup, presup_estado, presup_numero) VALUES (:presupFecha, :clienteId, :usuarioId, :totalPresup, :presup_estado, :presup_numero)";

// Preparar la consulta
$insertar = $conexion->prepare($consultaInsertar);

// Asignar los valores a los parámetros de la consulta preparada
$insertar->bindValue(':presupFecha', $fechaFact);
$insertar->bindValue(':clienteId', $clienteId);
$insertar->bindValue(':usuarioId', $usuarioId);
$insertar->bindValue(':totalPresup', $totalVenta);
$insertar->bindValue(':presup_estado', "1");
$insertar->bindValue(':presup_numero', $nuevoNumero);

// Ejecutar la consulta preparada
if (!$insertar->execute()) {
    // Hubo un error al insertar la cabecera de la factura
    echo 'Error al guardar el presupuesto: ' . $insertar->errorInfo()[2];
}
// Cerrar el statement
$insertar = null;
  
// Consulta para insertar los detalles de los productos en la tabla detalle_factura
$consultaInsertarDetalle = "INSERT INTO detalle_presup (presup_numero, producto_id, cantidad, precio_venta) VALUES (:presup_numero, :producto_id, :cantidad, :precio)";

$huboErrores = false;

// Preparar la consulta
$insertar = $conexion->prepare($consultaInsertarDetalle);

// Obtener el ID de la última factura insertada
$facturaId = $conexion->lastInsertId();

// Recorrer los productos seleccionados
foreach ($productos as $producto) {
    $producto_id = $producto['id'];
    $cantidad = $producto['cantidad'];
    $precio = $producto['precio'];

    // Asignar los valores a los parámetros de la consulta preparada
    $insertar->bindValue(':presup_numero', $nuevoNumero);
    $insertar->bindValue(':producto_id', $producto_id);
    $insertar->bindValue(':cantidad', $cantidad);
    $insertar->bindValue(':precio', $precio);

    // Ejecutar la consulta preparada
    if (!$insertar->execute()) {
      // Hubo un error al insertar el detalle del producto
      $huboErrores = true;
      break; // Salir del bucle
  }
}
// Cerrar el statement
$insertar=null;

if ($huboErrores) {
  echo 'Hubo un error al guardar los detalles de los productos.';
} else {
  echo $facturaHTML;
}


?>