<?php
require_once("main.php");

$product_id = limpiar_cadena($_POST["img_up_id"]);//input hidden

//verificar en bd
$check_producto = con();
$check_producto = $check_producto->query("SELECT * FROM producto WHERE producto_id = '$product_id'");

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

//comprueba si se selecciono una img valida
if($_FILES["producto_foto"]["name"] == "" || $_FILES["producto_foto"]["size"]==0){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se seleccionó ninguna imagen.
    </div>';
    exit();
}

    //crea dir
if(!file_exists($img_dir)){
    if(!mkdir($img_dir, 0777)){//crea carpeta con permisos de lectura y escritura
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo crear el directorio.
        </div>';
        exit();
    }
}

chmod($img_dir, 0777);//permisos de escritura y lectura

//verifica formato imgs
if(mime_content_type($_FILES["producto_foto"]["tmp_name"]) != "image/jpeg" && mime_content_type($_FILES["producto_foto"]["tmp_name"]) != "image/png"){//verifica formato de archivo
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Formato de archivo no permitido.
    </div>';
    exit();
}

//verifica tamaño de img
if(($_FILES["producto_foto"]["size"] / 1024) > 3072){//mb to kb
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Tamaño de archivo supera el permitido.
    </div>';
    exit();
}
//verifica extension img
switch(mime_content_type($_FILES["producto_foto"]["tmp_name"])){
    case "image/jpeg":
        $img_ext = ".jpg";
        break;
    case "image/png":
        $img_ext = ".png";
        break;
}

$img_nombre = renombrar_fotos($datos["producto_nombre"]);
$foto = $img_nombre.$img_ext;

//mueve la img del form al directorio
if(!move_uploaded_file($_FILES["producto_foto"]["tmp_name"], $img_dir.$foto)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo guardar la imagen, inténtelo nuevamente
    </div>';
    exit();
}

//eliminar img anterior
if(is_file($img_dir.$datos["producto_foto"]) && $datos["producto_foto"] != $foto){
    chmod($img_dir.$datos["producto_foto"], 0777);
    unlink($img_dir.$datos["producto_foto"]);
}

//actualizando img
$actualizar_producto = con();
$actualizar_producto = $actualizar_producto->prepare("UPDATE producto SET 
producto_foto = :foto WHERE producto_id = :id");

$marcadores=[
    ":foto"=>$foto,
    ":id"=>$product_id
];

if($actualizar_producto->execute($marcadores)){
    echo '
    <div class="notification is-success is-light">
        <strong>Imagen actualizada!</strong><br>
        La imagen se actualizó exitosamente.<br>
        Haga click en Aceptar para actualizar.

        <p class="has-text-centered pt-5 pb-5"><a 
        href="index.php?vista=product_img&product_id_upd='.$product_id.'"
        class="button is-link is-rounded">Aceptar</a></p>
    </div>';
} else {
    //si no se puede guardar la img en la bd, se elimina la img del dir
    if(is_file($img_dir.$foto)){
        chmod($img_dir.$foto, 0777);
        unlink($img_dir.$foto);
    }
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo actualizar la imagen, inténtelo nuevamente
    </div>';   
}

$actualizar_producto=null;