<?php
$inicio = ($pagina>0) ? (($registros*$pagina)-$registros): 0;

$tabla = "";

$campos = "producto_id, producto_codigo, producto_nombre, producto_precio";


if(isset($busqueda) && $busqueda != ""){//busqueda especifica por codigo o nombre de producto
    $consulta_datos = "SELECT $campos FROM producto WHERE producto_codigo LIKE '%$busqueda%' OR producto_nombre LIKE '%$busqueda%' ORDER BY producto_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(producto_id) FROM producto WHERE producto_codigo LIKE '%$busqueda%' OR producto_nombre LIKE '%$busqueda%'";

}
else {//busqueda total productos
    $consulta_datos = "SELECT $campos FROM producto ORDER BY producto_nombre ASC LIMIT $inicio, $registros";

     $consulta_total = "SELECT COUNT(producto_id) FROM producto";
}

$conexion=con();

$datos = $conexion->query($consulta_datos);
$datos = $datos->fetchAll();

$total = $conexion->query($consulta_total);
$total = (int)$total->fetchColumn();//fetch una unica columna
//cantidad paginas para mostrar
$Npaginas = ceil($total/$registros);//redonde hacia arriba


if($total>=1 && $pagina <= $Npaginas){
    
    $contador = $inicio+1;//contador de usuarios
    $pag_inicio=$inicio+1;//ej: mostrando usuario 1 al 7

    foreach($datos as $product){
        $precio_entero = number_format($product["producto_precio"], 0, ',', '.');
        $tabla.='
                <div class="media-content">
                    <div class="content">
                        <p>
                            <strong>'.$contador.' - '.$product["producto_nombre"].'</strong><br>
                            <strong>COD. BARRA:</strong> '.$product["producto_codigo"].' - 
                            <strong>PRECIO:</strong> ₲s '.$precio_entero.' -
                        </p>
                    </div>
                    <div class="has-text-right">
                        <div class="columns">
                            <div class="column">
                                <div class="control">
                                    <input class="input is-rounded" type="text" name="producto_cantidad" pattern="[0-9]{1,3}" placeholder="Cantidad" maxlength="3" required >
                                </div>
                            </div>
                        <div class="column">
                            <div class="control">
                            <a href="index.php?vista=product_update&product_id_upd='.$product["producto_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </article>


            <hr>
        ';
        $contador++;
    }
    $pag_final=$contador-1;//ej: mostrando usuario 1 al 7
} else {
    if($total>=1){//si introduce una pagina no existente te muestra boton para llevarte a la pag 1
        $tabla.='
        <p class="has-text-centered">
                <a href="'.$url.'1" class="button is-link is-rounded is-small mt-4 mb-4">
                    Haga clic para recargar el listado
                </a>
</p>
        ';
    } else {
        $tabla.='<p class="has-text-centered">No hay registros en el sistema</p>';
    }
}

if($total>=1 && $pagina <= $Npaginas){
    $tabla.='
        <p class="has-text-right">Mostrando productos <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>
    ';
    }

$conexion=null;
echo $tabla;

if($total>=1 && $pagina <= $Npaginas){
    echo paginador($pagina, $Npaginas, $url, 7);

}