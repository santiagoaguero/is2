<?php
$inicio = ($pagina>0) ? (($registros*$pagina)-$registros): 0;
$tabla = "";

if(isset($busqueda) && $busqueda != ""){//busqueda especifica por nombre, ruc o telefono
    $consulta_datos = "SELECT * FROM proveedor WHERE prov_nombre LIKE '%$busqueda%' OR prov_ruc LIKE '%$busqueda%' OR prov_telefono LIKE '%$busqueda%' ORDER BY prov_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(prov_id) FROM proveedor WHERE prov_nombre LIKE '%$busqueda%' OR prov_ruc LIKE '%$busqueda%' OR prov_telefono LIKE '%$busqueda%'";

} else {//busqueda total proveedores
    $consulta_datos = "SELECT * FROM proveedor ORDER BY prov_nombre ASC LIMIT $inicio, $registros";

     $consulta_total = "SELECT COUNT(prov_id) FROM proveedor";
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
                <th class="has-text-centered">Nombre</th>
                <th class="has-text-centered">RUC</th>
                <th class="has-text-centered">Telefono</th>
                <th class="has-text-centered">Dirección</th>
                <th colspan="2" class="has-text-centered">Opciones</th>
            </tr>
        </thead>
        <tbody>

';

if($total>=1 && $pagina <= $Npaginas){
    
    $contador = $inicio+1;//contador de usuarios
    $pag_inicio=$inicio+1;//ej: mostrando usuario 1 al 7

    foreach($datos as $prov){
        $tabla.='
            <tr class="has-text-centered" >
                <td>'.$contador.'</td>
                <td>'.$prov["prov_nombre"].'</td>
                <td>'.$prov["prov_ruc"].'</td>
                <td>'.$prov["prov_telefono"].'</td>
                <td>'.$prov["prov_direccion"].'</td>
                <td>
                    <a href="index.php?vista=provee_update&prov_id_upd='.$prov["prov_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a href="'.$url.$pagina.'&prov_id_del='.$prov["prov_id"].'" class="button is-danger is-rounded is-small btnDanger">Eliminar</a>
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
            <td colspan="7">
                <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                    Haga clic para recargar el listado
                </a>
            </td>
        </tr> 
        ';
    } else {
        $tabla.='
            <tr class="has-text-centered" >
            <td colspan="7">
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
        <p class="has-text-right">Mostrando proveedores <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>
    ';
    }


$conexion=null;
echo $tabla;

if($total>=1 && $pagina <= $Npaginas){
    echo paginador($pagina, $Npaginas, $url, 2);

}