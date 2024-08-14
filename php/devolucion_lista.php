<?php
$inicio = ($pagina>0) ? (($registros*$pagina)-$registros): 0;
$tabla = "";

if(isset($busqueda) && $busqueda != ""){//busqueda especifica por nombre
    $consulta_datos = "SELECT D.*, P.producto_nombre, F.factura_numero FROM devoluciones D 
                       LEFT JOIN facturas F
                       ON D.factura_id = F.factura_id
                       LEFT JOIN producto P 
                       ON D.producto_id = P.producto_id 
                       WHERE P.producto_nombre LIKE '%$busqueda%' 
                       ORDER BY dev_fecha DESC LIMIT $inicio, $registros
    ";

    $consulta_total = "SELECT COUNT(D.devolucion_id) FROM devoluciones D LEFT JOIN producto P ON D.producto_id = P.producto_id WHERE P.producto_nombre LIKE '%$busqueda%'";

} else {//busqueda total
    $consulta_datos = "SELECT D.*, P.producto_nombre, F.factura_numero FROM devoluciones D 
                       LEFT JOIN facturas F
                       ON D.factura_id = F.factura_id
                       LEFT JOIN producto P 
                       ON D.producto_id = P.producto_id 
                       ORDER BY dev_fecha DESC LIMIT $inicio, $registros
    ";

    $consulta_total = "SELECT COUNT(devolucion_id) FROM devoluciones";
}

$conexion=con();

$datos = $conexion->query($consulta_datos);
$datos = $datos->fetchAll();

$total = $conexion->query($consulta_total);
$total = (int)$total->fetchColumn();//fetch una unica columna
//cantidad paginas para mostrar
$Npaginas = ceil($total/$registros);//redonde hacia arriba

$tabla.='
    <div class="table-container">
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
            <tr class="has-text-centered">
                <th class="has-text-centered">#</th>
                <th class="has-text-centered">Fecha</th>
                <th class="has-text-centered">Producto</th>
                <th class="has-text-centered">Cantidad</th>
                <th class="has-text-centered">Factura Nº</th>
                <th class="has-text-centered" colspan="2">Opciones</th>
            </tr>
        </thead>
        <tbody>

';

if($total>=1 && $pagina <= $Npaginas){

    $contador = $inicio+1;//contador de usuarios
    $pag_inicio=$inicio+1;//ej: mostrando usuario 1 al 7

    foreach($datos as $devolucion){
        $tabla.='
            <tr class="has-text-centered" >
                <td>'.$contador.'</td>
                <td>'.substr($devolucion["dev_fecha"], 0, 10).'</td>
                <td>'.$devolucion["producto_nombre"].'</td>
                <td>'.$devolucion["dev_cantidad"].'</td>
                <td>'.$devolucion["factura_numero"].'</td>
                <td>
                    <a href="index.php?vista=devolucion_update&devolucion_id_upd='.$devolucion["devolucion_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a href="'.$url.$pagina.'&devolucion_id_del='.$devolucion["devolucion_id"].'" class="button is-danger is-rounded is-small btnDanger">Eliminar</a>
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
            <td colspan="5">
                <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                    Haga clic para recargar el listado
                </a>
            </td>
        </tr> 
        ';
    } else {
        $tabla.='
            <tr class="has-text-centered" >
            <td colspan="6">
                No hay registros en el sistema
            </td>
        </tr>
    ';
    }
}



$tabla.='
            </tbody>
        </table>
    </div>';


if($total>=1 && $pagina <= $Npaginas){
    $tabla.='
        <p class="has-text-right">Mostrando devoluciones <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>
    ';
    }


$conexion=null;
echo $tabla;

if($total>=1 && $pagina <= $Npaginas){
    echo paginador($pagina, $Npaginas, $url, 7);

}