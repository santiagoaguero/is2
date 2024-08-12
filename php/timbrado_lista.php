<?php
$inicio = ($pagina>0) ? (($registros*$pagina)-$registros): 0;
$tabla = "";

if(isset($busqueda) && $busqueda != ""){//busqueda especifica por nombre
    $consulta_datos = "SELECT * FROM timbrados WHERE numero LIKE '%$busqueda%' ORDER BY numero ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(timbrado_id) FROM timbrados WHERE numero LIKE '%$busqueda%'";

} else {//busqueda total timbrado
    $consulta_datos = "SELECT * FROM timbrados ORDER BY numero ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(timbrado_id) FROM timbrados";
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
                <th class="has-text-centered">Fecha de Emisión</th>
                <th class="has-text-centered">Fecha de Venc.</th>
                <th class="has-text-centered">Estado</th>
                <th class="has-text-centered" colspan="2">Opciones</th>
            </tr>
        </thead>
        <tbody>

';

if($total>=1 && $pagina <= $Npaginas){
    
    $contador = $inicio+1;//contador de usuarios
    $pag_inicio=$inicio+1;//ej: mostrando usuario 1 al 7

    foreach($datos as $timbrado){
        $tabla.='
            <tr class="has-text-centered" >
                <td>'.$contador.'</td>
                <td>'.$timbrado["numero"].'</td>
                <td>'.substr($timbrado["fecha_emision"], 0, 10).'</td>
                <td>'.substr($timbrado["fecha_vencimiento"], 0, 10).'</td>
                <td>'.($timbrado["estado"] == 1 ? "Activo" : "Inactivo").'</td>
                <td>
                    <a href="index.php?vista=timbrado_update&timbrado_id_upd='.$timbrado["timbrado_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a href="'.$url.$pagina.'&timbrado_id_del='.$timbrado["timbrado_id"].'" class="button is-danger is-rounded is-small btnDanger">Eliminar</a>
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
            <td colspan="5">
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
        <p class="has-text-right">Mostrando timbrados <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>
    ';
    }


$conexion=null;
echo $tabla;

if($total>=1 && $pagina <= $Npaginas){
    echo paginador($pagina, $Npaginas, $url, 7);

}