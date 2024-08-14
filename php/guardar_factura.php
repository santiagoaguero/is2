<?php
require_once("main.php");
require_once("../inc/session_start.php");

// Obtener los datos del objeto JSON
$data = json_decode(file_get_contents('php://input'), true);
$ruc = $data['ruc'];
$forma_pago = $data['forma_pago'];
$productos = $data['productos'];

// Obtener los datos del timbrado
$busca_timbrado = "SELECT T.timbrado_id, T.numero, T.fecha_emision, T.fecha_vencimiento
  FROM usuario U
  LEFT JOIN puntos_impresion P
  ON U.punto_impresion_id = P.punto_impresion_id
  LEFT JOIN timbrados T
  ON P.timbrado_id = T.timbrado_id
  WHERE U.usuario_id = '".$_SESSION['id']."'
  AND T.fecha_vencimiento >= '".date("Y-m-d")."'
  AND T.estado = 1
";
$conexion=con();
$timbrado_sql = $conexion->query($busca_timbrado);
if ($timbrado_sql->rowCount() > 0) {
  $timbrado_data = $timbrado_sql->fetch();
  $timbrado = $timbrado_data["timbrado_id"];
} else {
  echo('No se encontró un timbrado disponible para cargar esta factura');
}

//busca cliente para asociar a la factura
$busca_cliente = "SELECT cliente_id, cliente_nombre, cliente_direccion FROM clientes WHERE cliente_ruc = '$ruc'";
$conexion=con();
$cliente = $conexion->query($busca_cliente);
$cliente = $cliente->fetch();

$clienteId = $cliente["cliente_id"];
$cliente_nom = $cliente["cliente_nombre"];
$cliente_dir = $cliente["cliente_direccion"];

//busca forma de pago para asociar a la factura
$busca_forma_pago = "SELECT descripcion FROM formas_pago WHERE forma_pago_id = '$forma_pago'";
$conexion=con();
$forma_pago_res = $conexion->query($busca_forma_pago);
$forma_pago_descripcion = $forma_pago_res->fetch()['descripcion'];

//busca ultimo numero de factura
$consulta = "SELECT factura_numero FROM facturas ORDER BY factura_numero DESC LIMIT 1";
$resultado = $conexion->query($consulta);

// Verificar si la consulta tuvo resultados
if ($resultado->rowCount() > 0) {
    // Obtener el número de factura
    $fila = $resultado->fetch();
    $ultimoNumero = $fila['factura_numero'];
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
          <div>TIMBRADO: '.$timbrado_data["numero"].'</div>
          <div>FECHA INICIO VIGENCIA: '.$timbrado_data["fecha_emision"].'</div>
          <div>FECHA FIN VIGENCIA: '.$timbrado_data["fecha_vencimiento"].'</div>
        </div>

          <div><strong>RUC:</strong><br />80012345-6</div>
          <div><strong>FACTURA:</strong><br />'.$nuevoNumero.'</div>

      </div>
    </header>
    <div class="cliente">
      <div>Fecha: '.$fechaES.' </div>
      <div>Forma de Pago: '.$forma_pago_descripcion.'</div>
      <div>Nombre: ' . $cliente_nom . '</div>
      <div>RUC: ' . $ruc . '</div>
      <div>Direccion: ' . $cliente_dir . '</div>
      <div>Nota de remisión:</div>
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
                <td style="border: none; background: none;"><p style="color:#C1CED9;">Original</p></td>
                <td style="border: none; background: none;"><p style="color:#C1CED9;">Cliente</p></td>
              </tr>
            </tfoot>

        </table>
        </body>
    </html>';

// Consulta preparada para insertar la nueva factura en la base de datos
$consultaInsertar = "INSERT INTO facturas (factura_fecha, timbrado_id, forma_pago_id, cliente_id, usuario_id, total_venta, factura_estado, factura_numero) VALUES (:facturaFecha, :timbrado_id, :forma_pago_id, :clienteId, :usuarioId, :totalVenta, :factura_estado, :factura_numero)";

// Preparar la consulta
$insertar = $conexion->prepare($consultaInsertar);

// Asignar los valores a los parámetros de la consulta preparada
$insertar->bindValue(':facturaFecha', $fechaFact);
$insertar->bindValue(':timbrado_id', $timbrado);
$insertar->bindValue(':forma_pago_id', $forma_pago);
$insertar->bindValue(':clienteId', $clienteId);
$insertar->bindValue(':usuarioId', $usuarioId);
$insertar->bindValue(':totalVenta', $totalVenta);
$insertar->bindValue(':factura_estado', "1");
$insertar->bindValue(':factura_numero', $nuevoNumero);

// Ejecutar la consulta preparada
if (!$insertar->execute()) {
    // Hubo un error al insertar la cabecera de la factura
    echo 'Error al guardar la factura: ' . $insertar->errorInfo()[2];
}
// Cerrar el statement
$insertar = null;
  
// Consulta para insertar los detalles de los productos en la tabla detalle_factura
$consultaInsertarDetalle = "INSERT INTO detalle_factura (factura_numero, producto_id, cantidad, precio_venta) VALUES (:factura_numero, :producto_id, :cantidad, :precio)";

$hayError = false;

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
    $insertar->bindValue(':factura_numero', $nuevoNumero);
    $insertar->bindValue(':producto_id', $producto_id);
    $insertar->bindValue(':cantidad', $cantidad);
    $insertar->bindValue(':precio', $precio);

    // Ejecutar la consulta preparada
    if (!$insertar->execute()) {
      // Hubo un error al insertar el detalle del producto
      $hayError = true;
      break; // Salir del bucle
  }
}
// Cerrar el statement
$insertar=null;

if ($hayError) {
  echo 'Hubo un error al guardar los detalles de los productos.';
} else {
  echo $facturaHTML;
}

//updating stocks
if($hayError){
  $conexion=null;
} else {

  $conexion=con();

  foreach ($productos as $producto) {
      $id_producto = $producto['id'];
      $cantidad_venta = $producto['cantidad'];

      // Consultar el stock actual del producto
      $stock_actual = "SELECT producto_stock FROM producto WHERE producto_id = $id_producto";
      $result = $conexion->query($stock_actual);

      if ($result->rowCount() > 0) {
          $row = $result->fetch();
          $stock_actual = $row['producto_stock'];

          // Calcular el nuevo stock después de la venta
          $nuevo_stock = $stock_actual - $cantidad_venta;

          // Actualizar el stock en la tabla de productos
          $actualizar_stock = "UPDATE producto SET producto_stock = $nuevo_stock WHERE producto_id = $id_producto";

          if (!$conexion->query($actualizar_stock)) {
              echo "Error al actualizar el stock: " . $conexion->errorInfo()[2];
          }
      } else {
          echo "Error: No se encontró el producto con ID $id_producto";
      }
  }
}


?>