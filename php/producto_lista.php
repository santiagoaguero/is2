<?php
$inicio = ($pagina>0) ? (($registros*$pagina)-$registros): 0;

$tabla = "";

$campos = "producto.producto_id, producto.producto_codigo, producto.producto_nombre, producto.producto_precio, producto.producto_stock, producto.producto_stock_min, producto.producto_foto, categoria.categoria_nombre, familia.familia_nombre, usuario.usuario_nombre, usuario.usuario_apellido";

if(isset($busqueda) && $busqueda != ""){//busqueda especifica por codigo o nombre de producto
    $consulta_datos = "SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN familia ON producto.familia_id = familia.familia_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id WHERE producto.producto_codigo LIKE '%$busqueda%' OR producto.producto_nombre LIKE '%$busqueda%' ORDER BY producto.producto_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(producto_id) FROM producto WHERE producto_codigo LIKE '%$busqueda%' OR producto_nombre LIKE '%$busqueda%'";

} elseif( isset($categoria_id) && ($categoria_id>0) ){
    $consulta_datos = "SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN familia ON producto.familia_id = familia.familia_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id WHERE producto.categoria_id = '$categoria_id' ORDER BY producto.producto_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(producto_id) FROM producto WHERE categoria_id='$categoria_id'";
} 

elseif(isset($deposito_id) && $deposito_id>0){
    $consulta_datos = "SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN familia ON producto.familia_id = familia.familia_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id WHERE producto.deposito_id = '$deposito_id' ORDER BY producto.producto_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(producto_id) FROM producto WHERE deposito_id='$deposito_id'";
}

elseif(isset($familia_id) && $familia_id>0){
    $consulta_datos = "SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN familia ON producto.familia_id = familia.familia_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id WHERE producto.familia_id = '$familia_id' ORDER BY producto.producto_nombre ASC LIMIT $inicio, $registros";

    $consulta_total = "SELECT COUNT(producto_id) FROM producto WHERE familia_id='$familia_id'";
}

else {//busqueda total productos
    $consulta_datos = "SELECT $campos FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN familia ON producto.familia_id = familia.familia_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id ORDER BY producto.producto_nombre ASC LIMIT $inicio, $registros";

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
                <article class="media">
                <figure class="media-left">
                    <p class="image is-64x64">';
                    if(is_file("./img/productos/".$product["producto_foto"])){
                        $tabla.='
                        <img src="./img/productos/'.$product["producto_foto"].'">
                        ';
                    }else {
                        $tabla.='
                        <img src="./img/productos.png">
                        ';
                    }
            $tabla.='
                    </p>
                </figure>
                <div class="media-content">
                    <div class="content">
                        <p>
                            <strong>'.$contador.' - '.$product["producto_nombre"].'</strong><br>
                            <strong>COD. BARRA:</strong> '.$product["producto_codigo"].' - 
                            <strong>PRECIO:</strong> ₲s '.$precio_entero.' - ';
                            //stock mayor al doble del minimo 
                            if($product["producto_stock"] < $product["producto_stock_min"]){
                                $tabla.='
                                <div class="notification is-danger is-light">
                                    <button class="delete"></button>
                                    Stock menor al mínimo, se recomienda reabastecer producto.
                                </div>
                                <strong style="color: red">STOCK:</strong> '.$product["producto_stock"].' -
                                <strong>STOCK MIN:</strong> '.$product["producto_stock_min"].' - ';
                            }
                            //stock mayor al minimo + la mitad del minimo
                            elseif(
                                ($product["producto_stock"] >= $product["producto_stock_min"]) &&
                                ($product["producto_stock"] <= ($product["producto_stock_min"] + $product["producto_stock_min"]/2)) ){
                                $tabla.='
                                <strong style="color: orange">STOCK:</strong> '.$product["producto_stock"].' -
                                <strong>STOCK MIN:</strong> '.$product["producto_stock_min"].' -';
                            }
                            //stock <= al minimo
                            else {
                                $tabla.='
                                <strong style="color: limegreen">STOCK:</strong> '.$product["producto_stock"].' -
                                <strong>STOCK MIN:</strong> '.$product["producto_stock_min"].' - ';
                            }

                            $tabla.='
                            <strong>CATEGORIA:</strong> '.$product["categoria_nombre"].' -
                            <strong>FAMILIA:</strong> '.$product["familia_nombre"].' - 
                            <strong>REGISTRADO POR:</strong> '.$product["usuario_nombre"].' '.$product["usuario_apellido"].'
                        </p>
                    </div>
                    <div class="has-text-right">
                        <a href="index.php?vista=product_img&product_id_upd='.$product["producto_id"].'" class="button is-link is-rounded is-small">Imagen</a>
                        <a href="index.php?vista=product_update&product_id_upd='.$product["producto_id"].'" class="button is-success is-rounded is-small">Actualizar</a>
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