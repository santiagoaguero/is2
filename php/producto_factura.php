<?php
$inicio = ($pagina>0) ? (($registros*$pagina)-$registros): 0;

$tabla = "";

$campos = "producto.producto_id, producto.producto_codigo, producto.producto_nombre, producto.producto_precio, producto.producto_stock, producto.producto_foto, categoria.categoria_nombre, usuario.usuario_nombre, usuario.usuario_apellido";


if(isset($busqueda) && $busqueda != ""){//busqueda especifica por codigo o nombre de producto
    $consulta_datos = "SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id WHERE producto.producto_codigo LIKE '%$busqueda%' OR producto.producto_nombre LIKE '%$busqueda%' ORDER BY producto.producto_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(producto_id) FROM producto WHERE producto_codigo LIKE '%$busqueda%' OR producto_nombre LIKE '%$busqueda%'";

} elseif($categoria_id>0){
    $consulta_datos = "SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id WHERE producto.categoria_id = '$categoria_id' ORDER BY producto.producto_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(producto_id) FROM producto WHERE categoria_id='$categoria_id'";
}

else {//busqueda total productos
    $consulta_datos = "SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id ORDER BY producto.producto_nombre ASC LIMIT $inicio, $registros";

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
                            <strong>PRECIO:</strong> ₲s '.$precio_entero.' - 
                            <strong>CANTIDAD:</strong> '.$product["producto_stock"].' -  
                        </p>
                    </div>
                    <div class="has-text-right">
                        <a href="'.$url.$pagina.'&product_id_del='.$product["producto_id"].'" class="button is-danger is-rounded is-small">Eliminar</a>
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