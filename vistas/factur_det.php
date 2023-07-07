<div class="container is-fluid mb-6">
    <h1 class="title">Factura</h1>
    <?php 
        require("./php/main.php");
        $numero = $_GET["fact_nro"];
        echo '<h2 class="subtitle">'.$numero.'</h2>';
        echo '<div class="container pb-6 pt-0">';
        include("./inc/btn_back.php");
        $factura = con();
        $sql = "SELECT facturas.factura_fecha, facturas.factura_estado, clientes.cliente_nombre, clientes.cliente_ruc, clientes.cliente_direccion, usuario.usuario_nombre, usuario.usuario_apellido FROM facturas INNER JOIN clientes ON facturas.cliente_id = clientes.cliente_id INNER JOIN usuario ON facturas.usuario_id = usuario.usuario_id WHERE facturas.factura_numero = '$numero' ORDER BY facturas.factura_fecha DESC";
        $factura = $factura->query($sql);
        if($factura->rowCount()>0){
            $factura = $factura->fetchAll();
            foreach($factura as $fact){
                //$precio_entero = number_format($fact["total_venta"], 0, ',', '.');//format precio
                $fecha_obj = new DateTime($fact["factura_fecha"]);//format fecha
                $fechaES = $fecha_obj->format('d-m-Y');
                $usuario_nom = $fact["usuario_nombre"]. " " . $fact["usuario_apellido"];
                echo '
                    <div class="cliente">
                        <div>Fecha: '.$fechaES.' </div>
                        <div>Condicion de Venta: Contado</div>
                        <div>Nombre: ' . $fact["cliente_nombre"] . '</div>
                        <div>RUC: ' . $fact["cliente_ruc"] . '</div>
                        <div>Direccion: ' . $fact["cliente_direccion"] . '</div>
                        <div>Nota de remisión:</div>
                    </div>';
                }
                $factura=null;
                echo'
                <main>
                <table class="table">
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

                $productos = con();
                $sql = "SELECT producto.producto_id, producto.producto_nombre, producto.producto_iva, detalle_factura.cantidad, detalle_factura.precio_venta FROM detalle_factura INNER JOIN producto ON detalle_factura.producto_id = producto.producto_id WHERE detalle_factura.factura_numero = '$numero'";
                $productos = $productos->query($sql);
                if($productos->rowCount()>0){
                    $productos = $productos->fetchAll();
                    $totalIva0 = 0;
                    $totalIva5 = 0;
                    $totalIva10 = 0;
                    foreach($productos as $producto){
                        $precioIva = $producto["precio_venta"] * $producto["cantidad"];
                        //$precio_entero = number_format($fact["total_venta"], 0, ',', '.');//format precio
                        echo '
                        <tr>
                        <td>'.$producto['producto_id'].'</td>
                        <td>'.$producto['producto_nombre'].'</td>
                        <td>'.$producto['cantidad'].'</td>
                        <td>'.$producto['precio_venta'].'</td>';
                        switch($producto['producto_iva']){
                          case 10:
                            $totalIva10 += $precioIva;
                            echo '
                            <td>0</td>
                            <td>0</td>
                            <td>'.$precioIva.'</td>
                            ';
                            break;
                          case 5:
                            $totalIva5 += $precioIva;
                            echo '
                            <td>0</td>
                            <td>'.$precioIva.'</td>
                            <td>0</td>
                            ';
                            break;
                          case 0:
                            $totalIva0 += $precioIva;
                            echo '
                            <td>'.$precioIva.'</td>
                            <td>0</td>
                            <td>0</td>
                            ';
                            break;
                        }
                    echo
                      '</tr>
                      </tbody>
                      ';
                      $totalVenta = $totalIva0 + $totalIva5 + $totalIva10;
                      $totalVentaES = new NumberFormatter('es', NumberFormatter::SPELLOUT);
                      $totalVentaES = $totalVentaES->format($totalVenta);
                      $iva10 = round( $totalIva10 / 11);
                      $iva5 = round($totalIva5 / 21);
                        }
                    echo '
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
                        </tr>
                        </tfoot>

                    </table>';
                        $productos=null;
                }

        } else {echo "NO";}