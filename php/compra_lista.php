<?php
$inicio = ($pagina>0) ? (($registros*$pagina)-$registros): 0;

$tabla = "";

$campos = "compras.compra_id, compras.compra_fecha, compras.compra_factura, compras.compra_total, compras.compra_condicion, compras.compra_estado, proveedor.prov_nombre, proveedor.prov_ruc";


if(isset($busqueda) && $busqueda != ""){//busqueda especifica por numero fact o nombre proveedor
    $consulta_datos = "SELECT $campos FROM compras INNER JOIN proveedor ON compras.prov_id = proveedor.prov_id WHERE compras.compra_factura LIKE '%$busqueda%' OR proveedor.prov_nombre LIKE '%$busqueda%' ORDER BY compras.compra_fecha DESC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(compra_factura) FROM compras INNER JOIN proveedor ON compras.prov_id = proveedor.prov_id WHERE compras.compra_factura LIKE '%$busqueda%' OR proveedor.prov_nombre LIKE '%$busqueda%'";

} elseif( isset($prov_id) && ($prov_id>0) ){

    $consulta_datos = "SELECT $campos FROM compras INNER JOIN proveedor ON compras.prov_id = proveedor.prov_id WHERE proveedor.prov_id='$prov_id' ORDER BY compras.compra_fecha DESC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(compra_factura) FROM compras WHERE prov_id='$prov_id'";

} else {//busqueda total facturas
    $consulta_datos = "SELECT $campos FROM compras INNER JOIN proveedor ON compras.prov_id = proveedor.prov_id ORDER BY compras.compra_fecha DESC LIMIT $inicio, $registros";

     $consulta_total = "SELECT COUNT(compra_factura) FROM compras";
}

$conexion=con();

$datos = $conexion->query($consulta_datos);
$datos = $datos->fetchAll();

$total = $conexion->query($consulta_total);
$total = (int)$total->fetchColumn();//fetch una unica columna
//cantidad paginas para mostrar
$Npaginas = ceil($total/$registros);//redondea hacia arriba

$tabla.='
    <div class="table-container">
        <table class="table is-fullwidth">
            <thead>
                <tr class="has-text-centered">
                    <th class="has-text-centered">#</th>
                    <th class="has-text-centered">Fecha</th>
                    <th class="has-text-centered">Factura</th>
                    <th class="has-text-centered">Total Compra</th>
                    <th class="has-text-centered">RUC</th>
                    <th class="has-text-centered">Proveedor</th>
                    <th class="has-text-centered">Condición</th>
                    <th colspan="2" class="has-text-centered">Opciones</th>
                </tr>
            </thead>
            <tbody>

';

if($total>=1 && $pagina <= $Npaginas){
    
    $contador = $inicio+1;//contador de usuarios
    $pag_inicio=$inicio+1;//ej: mostrando usuario 1 al 7

    foreach($datos as $fact){
        $precio_entero = number_format($fact["compra_total"], 0, ',', '.');//format precio
        $fecha_obj = new DateTime($fact["compra_fecha"]);//format fecha
        $fechaES = $fecha_obj->format('d-m-Y');
        $tabla.='
                <tr class="has-text-centered" >
                    <td>'.$contador.'</td>
                    <td>'.$fechaES.'</td>
                    <td>'.$fact["compra_factura"].'</td>
                    <td>'.$precio_entero.'</td>
                    <td>'.$fact["prov_ruc"].'</td>
                    <td>'.$fact["prov_nombre"].'</td>';
                    $estado = $fact["compra_condicion"] == 1 ? "Contado" : "Crédito";
                    $btnEstado = $fact["compra_estado"] == 1 ? 
                    '<a href='.$url.$pagina.'&id='.$fact["compra_id"].' class="button is-danger is-rounded is-small btnDanger">Anular</a>' : 
                    '<a href="#" class="button is-danger is-rounded is-small is-outlined">Anulado</a>
                    ';
        $tabla.='   
                    <td>'.$estado.'</td>
                    <td>
                        <a href="index.php?vista=compra_det&id='.$fact["compra_id"].'&nro='.$fact["compra_factura"].'" class="button is-success is-rounded is-small">Ver detalle</a>
                    </td>
                    <td>
                        '.$btnEstado.'
                    </td>
            </tr>
        ';
        $contador++;
    }
    $pag_final=$contador-1;//ej: mostrando usuario 1 al 7
} else {
    if($total>=1){//si introduce una pagina no existente te muestra boton para llevarte a la pag 1
        $tabla.='
            <tr class="has-text-centered" >
                <td colspan="8">
                    <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                        Haga clic para recargar el listado
                    </a>
                </td>
            </tr>
        ';
    } else {
        $tabla.='
            <tr class="has-text-centered" >
                <td colspan="8">
                    No hay registros en el sistema
                </td>
            </tr>';
    }
}

$tabla.='
        </tbody>
    </table>
</div>';

if($total>=1 && $pagina <= $Npaginas){
    $tabla.='
        <p class="has-text-right">Mostrando facturas <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>
    ';
    }

$conexion=null;
echo $tabla;

if($total>=1 && $pagina <= $Npaginas){
    echo paginador($pagina, $Npaginas, $url, 7);

}