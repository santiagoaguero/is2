<?php // Verificar los permisos del usuario para esta página
	include("./inc/check_rol.php");
	if (isset($_SESSION['rol']) && isset($_GET['vista'])) {
		$vistaSolicitada = $_GET['vista'];
		$rolUsuario = $_SESSION['rol'];
	
		check_rol($vistaSolicitada, $rolUsuario);
		
	} else {
        header("Location: login.php");
        exit();
    }
?>
<div class="container is-fluid mb-6 ">
    <h1 class="title">Compra</h1>
    <?php 
        require("./php/main.php");
        $numero = $_GET["nro"];
        $id = $_GET["id"];
        echo '<h2 class="subtitle">'.$numero.'</h2>';
        echo '<div class="box pb-6 pt-0 ">';
        include("./inc/btn_back.php");
        $factura = con();
        $sql = "SELECT compras.compra_id, compras.compra_fecha, compras.compra_condicion, compras.compra_estado, proveedor.prov_nombre, proveedor.prov_ruc, proveedor.prov_direccion, usuario.usuario_nombre, usuario.usuario_apellido FROM compras INNER JOIN proveedor ON compras.prov_id = proveedor.prov_id INNER JOIN usuario ON compras.usuario_id = usuario.usuario_id WHERE compras.compra_id = '$id' ORDER BY compras.compra_fecha DESC";
        $factura = $factura->query($sql);
        if($factura->rowCount()>0){
            $factura = $factura->fetchAll();
            foreach($factura as $fact){
                //$precio_entero = number_format($fact["total_venta"], 0, ',', '.');//format precio
                $fecha_obj = new DateTime($fact["compra_fecha"]);//format fecha
                $fechaES = $fecha_obj->format('d-m-Y');
                $usuario_nom = $fact["usuario_nombre"]. " " . $fact["usuario_apellido"];
                $estado = $fact["compra_estado"] == 0 ? "Anulado" : "";
                $condicion = $fact["compra_condicion"] == 1 ? "Contado" : "Crédito";
                echo '
                        <p class="subtitle has-text-centered"
                        style="text-transform: uppercase; color: red; font-weight: bold; font-size:40px";>'.$estado.'</p>
                        <div class="fact-detalle">
                            <div><span style="font-weight: bolder">Fecha:</span> '.$fechaES.' </div>
                            <div><span style="font-weight: bolder">Condición de Compra: </span>'.$condicion.'</div>
                            <div><span style="font-weight: bolder">Proveedor:</span> ' . $fact["prov_nombre"] . '</div>
                            <div><span style="font-weight: bolder">RUC:</span> ' . $fact["prov_ruc"] . '</div>
                            <div><span style="font-weight: bolder">Dirección:</span> ' . $fact["prov_direccion"] . '</div>
                            <div><span style="font-weight: bolder">Nota de crédito:</span></div>
                        </div>
                    ';
                }
                $factura=null;
                echo'
                <main>
                <table class="table is-fullwidth has-text-centered">
                  <thead>
                    <tr>
                      <th class="has-text-centered">CODIGO</th>
                      <th class="has-text-centered">PRODUCTO</th>
                      <th class="has-text-centered">CANTIDAD</th>
                      <th class="has-text-centered">PRECIO UNITARIO</th>
                      <th class="has-text-centered">Exenta</th>
                      <th class="has-text-centered">5%</th>
                      <th class="has-text-centered">10%</th>
                    </tr>
                  </thead>
                  <tbody>';

                $productos = con();
                $sql = "SELECT producto.producto_id, producto.producto_nombre, producto.producto_iva, compras_detalle.cantidad, compras_detalle.precio_compra FROM compras_detalle INNER JOIN producto ON compras_detalle.producto_id = producto.producto_id WHERE compras_detalle.compra_id = '$id'";
                $productos = $productos->query($sql);
                if($productos->rowCount()>0){
                    $productos = $productos->fetchAll();
                    $totalIva0 = 0;
                    $totalIva5 = 0;
                    $totalIva10 = 0;
                    foreach($productos as $producto){
                        $precioIva = $producto["precio_compra"] * $producto["cantidad"];
                        //$precio_entero = number_format($fact["total_venta"], 0, ',', '.');//format precio
                        echo '
                        <tr>
                        <td>'.$producto['producto_id'].'</td>
                        <td>'.$producto['producto_nombre'].'</td>
                        <td>'.$producto['cantidad'].'</td>
                        <td>'.$producto['precio_compra'].'</td>';
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
                            <td colspan="2" style="border: none; background: none;"><p style="color:#C1CED9;">Registrado por: '.$usuario_nom.'</p></td>
                        </tr>
                        </tfoot>

                    </table>';
                        $productos=null;
                }

        } else {
            echo "NO";
        }
    ?>
</div>