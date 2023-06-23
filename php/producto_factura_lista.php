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
           
                    <div class="form-rest mb-2 mt-2"></div>

                    <form action="./php/productos_detalle_temp.php" method="POST" class="formAgregarProd" autocomplete="off" >

                        <input type="hidden" name="prod_id" value="'.$product["producto_id"].'" required >

                        <div class="field is-grouped is-grouped-centered">
                            <div class="control is-expanded">


                                <input name="prod_cant" class="input is-rounded" type="number" pattern="[1-9]{1,3}" placeholder="Cantidad" maxlength="3" required >

                                <input type="hidden" name="prod_precio" value="'.number_format($product["producto_precio"], 0,',', '').'">

                            </div>
                            <div class="control">
                                <p class="has-text-centered">
                                    <button type="submit" class="button is-success is-rounded">Agregar</button>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
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