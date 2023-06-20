<?php
$inicio = ($pagina>0) ? (($registros*$pagina)-$registros): 0;
$tabla = "";

if(isset($busqueda) && $busqueda != ""){//busqueda especifica por nombre, apellido, ruc o email
    $consulta_datos = "SELECT * FROM clientes WHERE cliente_nombre LIKE '%$busqueda%' OR cliente_apellido LIKE '%$busqueda%' OR cliente_ruc LIKE '%$busqueda%' OR cliente_email LIKE '%$busqueda%' ORDER BY cliente_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(cliente_id) FROM clientes WHERE cliente_nombre LIKE '%$busqueda%' OR cliente_apellido LIKE '%$busqueda%' OR cliente_ruc LIKE '%$busqueda%' OR cliente_email LIKE '%$busqueda%'";

} else {//busqueda total categorías
    $consulta_datos = "SELECT * FROM clientes ORDER BY cliente_nombre ASC LIMIT $inicio, $registros";

     $consulta_total = "SELECT COUNT(cliente_id) FROM clientes";
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
                <th class="has-text-centered">Nombres</th>
                <th class="has-text-centered">Apellidos</th>
                <th class="has-text-centered">RUC</th>
                <th class="has-text-centered">Email</th>
                <th class="has-text-centered">Teléfono</th>
                <th class="has-text-centered">Dirección</th>
                <th colspan="2" class="has-text-centered">Opciones</th>
            </tr>
        </thead>
        <tbody>

';

if($total>=1 && $pagina <= $Npaginas){
    
    $contador = $inicio+1;//contador de usuarios
    $pag_inicio=$inicio+1;//ej: mostrando usuario 1 al 7

    foreach($datos as $cli){
        $tabla.='
            <tr class="has-text-centered" >
                <td>'.$contador.'</td>
                <td>'.$cli["cliente_nombre"].'</td>
                <td>'.$cli["cliente_apellido"].'</td>
                <td>'.$cli["cliente_ruc"].'</td>
                <td>'.$cli["cliente_email"].'</td>
                <td>'.$cli["cliente_telefono"].'</td>
                <td>'.$cli["cliente_direccion"].'</td>
                <td>
                    <a href="index.php?vista=client_update&client_id_upd='.$cli["cliente_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a href="'.$url.$pagina.'&client_id_del='.$cli["cliente_id"].'" class="button is-danger is-rounded is-small btnDanger">Eliminar</a>
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
            <td colspan="9">
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
        <p class="has-text-right">Mostrando usuarios <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>
    ';
    }


$conexion=null;
echo $tabla;

if($total>=1 && $pagina <= $Npaginas){
    echo paginador($pagina, $Npaginas, $url, 2);

}