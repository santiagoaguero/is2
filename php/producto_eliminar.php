<?php
$product_id_del = limpiar_cadena($_GET["product_id_del"]);

//verifica producto
$check_producto = con();
$check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id = '$product_id_del'");

if($check_producto->rowCount()==1){
    $datos = $check_producto->fetch();

    $eliminar_producto = con();
    $eliminar_producto=$eliminar_producto->prepare("DELETE FROM producto WHERE producto_id=:id");
    //filtro prepare para evitar inyecciones sql xss

    $eliminar_producto->execute([":id"=> $product_id_del]);
    if($eliminar_producto->rowCount()==1){

        if(is_file("./img/productos/".$datos["producto_foto"],)){
            chmod("./img/productos/".$datos["producto_foto"], 0777);//permisos lectura escritura
            unlink("./img/productos/".$datos["producto_foto"]);//eliminar img
        }
        echo '
        <div class="notification is-success is-light">
            <strong>¡Producto eliminado!</strong><br>
            El producto ha sido eliminado exitosamente.
        </div>';
    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo eliminar el producto, inténtelo nuevamente.
        </div>';
    }
    $eliminar_producto=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El producto que intenta eliminar no existe.
    </div>';
}

$check_producto=null;