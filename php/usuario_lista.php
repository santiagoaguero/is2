<?php
$inicio = ($pagina>0) ? (($registros*$pagina)-$registros): 0;
$tabla = "";

if(isset($busqueda) && $busqueda != ""){//busqueda especifica por nombre, apellido, usuario o email
    $consulta_datos = "SELECT * FROM usuario WHERE
    ((usuario_id != '".$_SESSION["id"]."') AND (usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%')) ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(usuario_id) FROM usuario WHERE
    ((usuario_id != '".$_SESSION["id"]."') AND (usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%'))";

} else {//busqueda total usuarios
    //mostrar todos menos el usuario que inicio sesion
    $consulta_datos = "SELECT * FROM usuario WHERE
     usuario_id != '".$_SESSION["id"]."' ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";

     $consulta_total = "SELECT COUNT(usuario_id) FROM usuario WHERE
     usuario_id != '".$_SESSION["id"]."'";
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
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Usuario</th>
                <th>Email</th>
                <th colspan="2">Opciones</th>
            </tr>
        </thead>
        <tbody>

';

if($total>=1 && $pagina <= $Npaginas){
    
    $contador = $inicio+1;//contador de usuarios
    $pag_inicio=$inicio+1;//ej: mostrando usuario 1 al 7

    foreach($datos as $user){
        $tabla.='
            <tr class="has-text-centered" >
                <td>'.$contador.'</td>
                <td>'.$user["usuario_nombre"].'</td>
                <td>'.$user["usuario_apellido"].'</td>
                <td>'.$user["usuario_usuario"].'</td>
                <td>'.$user["usuario_email"].'</td>
                <td>
                    <a href="index.php?vista=user_update&user_id_upd='.$user["usuario_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
                </td>
                <td>
                    <a href="'.$url.$pagina.'&user_id_del='.$user["usuario_id"].'" class="button is-danger is-rounded is-small">Eliminar</a>
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
        <p class="has-text-right">Mostrando usuarios <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>
    ';
    }


$conexion=null;
echo $tabla;

if($total>=1 && $pagina <= $Npaginas){
    echo paginador($pagina, $Npaginas, $url, 2);

}