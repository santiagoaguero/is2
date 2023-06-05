<?php
$inicio = ($pagina>0) ? (($registros*$pagina)-$registros): 0;
$tabla = "";

if(isset($busqueda) && $busqueda != ""){//busqueda especifica por nombre, apellido, usuario o email
    $consulta_datos = "SELECT * FROM categoria WHERE categoria_nombre LIKE '%$busqueda%' OR categoria_ubicacion LIKE '%$busqueda%' ORDER BY categoria_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(categoria_id) FROM categoria WHERE categoria_nombre LIKE '%$busqueda%' OR categoria_ubicacion LIKE '%$busqueda%'";

} else {//busqueda total categorías
    $consulta_datos = "SELECT * FROM categoria ORDER BY categoria_nombre ASC LIMIT $inicio, $registros";

     $consulta_total = "SELECT COUNT(categoria_id) FROM categoria";
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
                <th>#</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Productos</th>
                <th colspan="2">Opciones</th>
            </tr>
        </thead>
        <tbody>

';

if($total>=1 && $pagina <= $Npaginas){
    
    $contador = $inicio+1;//contador de usuarios
    $pag_inicio=$inicio+1;//ej: mostrando usuario 1 al 7

    foreach($datos as $category){
        $tabla.='
            <tr class="has-text-centered" >
                <td>'.$contador.'</td>
                <td>'.$category["categoria_nombre"].'</td>
                <td>'.substr($category["categoria_ubicacion"],0,20).'</td>
                <td>
                    <a href="index.php?vista=product_category&category_id='.$category["categoria_id"].'" class="button is-link is-rounded is-small">Ver productos</a>
                </td>
                <td>
                    <a href="index.php?vista=category_update&category_id_upd='.$category["categoria_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a href="'.$url.$pagina.'&category_id_del='.$category["categoria_id"].'" class="button is-danger is-rounded is-small btnDanger">Eliminar</a>
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
            <td colspan="6">
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
        <p class="has-text-right">Mostrando categorías <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>
    ';
    }


$conexion=null;
echo $tabla;

if($total>=1 && $pagina <= $Npaginas){
    echo paginador($pagina, $Npaginas, $url, 7);

}