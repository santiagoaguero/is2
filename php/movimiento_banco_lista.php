<?php
$inicio = ($pagina>0) ? (($registros*$pagina)-$registros): 0;
$tabla = "";

if(isset($busqueda) && $busqueda != ""){//busqueda especifica por nombre
    $consulta_datos = "SELECT M.*, B1.ban_nombre AS banco_uno, B2.ban_nombre AS banco_dos 
        FROM movimientos_bancos M 
        LEFT JOIN bancos B1
        ON M.banco_id = B1.banco_id
        LEFT JOIN bancos B2
        ON M.movban_destino = B2.banco_id
        WHERE M.movban_comprobante_nro LIKE '%$busqueda%' 
        ORDER BY M.movban_fecha DESC 
        LIMIT $inicio, $registros
    ";

    $consulta_total = "SELECT COUNT(banco_id) FROM movimientos_bancos WHERE movban_comprobante_nro LIKE '%$busqueda%'";

} else {//busqueda total
    $consulta_datos = "SELECT M.*, B1.ban_nombre AS banco_uno, B2.ban_nombre AS banco_dos 
        FROM movimientos_bancos M 
        LEFT JOIN bancos B1
        ON M.banco_id = B1.banco_id
        LEFT JOIN bancos B2
        ON M.movban_destino = B2.banco_id
        ORDER BY M.movban_fecha 
        DESC LIMIT $inicio, $registros
    ";

    $consulta_total = "SELECT COUNT(banco_id) FROM movimientos_bancos";
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
                <th class="has-text-centered">Origen</th>
                <th class="has-text-centered">Destino</th>
                <th class="has-text-centered">Comprobante Nº</th>
                <th class="has-text-centered" colspan="2">Opciones</th>
            </tr>
        </thead>
        <tbody>

';

if($total>=1 && $pagina <= $Npaginas){
    
    $contador = $inicio+1;//contador de usuarios
    $pag_inicio=$inicio+1;//ej: mostrando usuario 1 al 7

    foreach($datos as $banco){
        $tabla.='
            <tr class="has-text-centered" >
                <td>'.$contador.'</td>
                <td>'.substr($banco["movban_fecha"], 0, 10).'</td>
                <td>'.$banco["banco_uno"].'</td>
                <td>'.$banco["banco_dos"].'</td>
                <td>'.$banco["movban_comprobante_nro"].'</td>
                <td>
                    <a href="index.php?vista=movimiento_banco_update&movimiento_banco_id_upd='.$banco["movimiento_banco_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a href="'.$url.$pagina.'&movimiento_banco_id_del='.$banco["movimiento_banco_id"].'" class="button is-danger is-rounded is-small btnDanger">Eliminar</a>
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
        <p class="has-text-right">Mostrando entidades bancarias <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>
    ';
    }


$conexion=null;
echo $tabla;

if($total>=1 && $pagina <= $Npaginas){
    echo paginador($pagina, $Npaginas, $url, 7);

}