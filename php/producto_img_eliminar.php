<?php
require_once("main.php");

$product_id = limpiar_cadena($_POST["img_del_id"]);//input hidden

//verificar en bd
$check_producto = con();
$check_producto = $check_producto->query("SELECT * FROM producto WHERE producto_id = '$product_id'");

if($check_producto->rowCount()<=0){//no existe id
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se encontró el producto.
    </div>';
    exit();
} else {
    $datos = $check_producto->fetch();
}
$check_producto=null;

//directorio imagenes productos
$img_dir = "../img/productos/";
chmod($img_dir, 0777);//permisos de escritura y lectura

if(is_file($img_dir.$datos["producto_foto"])){
    chmod($img_dir.$datos["producto_foto"], 0777);

    if(!unlink($img_dir.$datos["producto_foto"])){
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo eliminar la imagen del producto.
        </div>';
        exit();
    }
}

//cambiando nombre a texto vacio ""
$actualizar_producto = con();
$actualizar_producto = $actualizar_producto->prepare("UPDATE producto SET 
producto_foto = :foto WHERE producto_id = :id");

$marcadores=[
    ":foto"=>"",
    ":id"=>$product_id
];

if($actualizar_producto->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Imagen eliminada!</strong><br>
        La imagen se eliminó exitosamente.<br>
        Haga click en Aceptar para actualizar.

        <p class="has-text-centered pt-5 pb-5"><a 
        href="index.php?vista=product_img&product_id_upd='.$product_id.'"
        class="button is-link is-rounded">Aceptar</a></p>
    </div>';
} else {
    echo '
    <div class="notification is-warning is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Algunos inconvenientes pudieron ocurrir, actualice para verificar.

        <p class="has-text-centered pt-5 pb-5"><a 
        href="index.php?vista=product_img&product_id_upd='.$product_id.'"
        class="button is-link is-rounded">Verificar</a></p>
    </div>';   
}

$actualizar_producto=null;