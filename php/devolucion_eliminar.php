<?php
$devolucion_id_del = limpiar_cadena($_GET["devolucion_id_del"]);

//verifica categoria
$check_dev = con();
$check_dev = $check_dev->query("SELECT devolucion_id, dev_cantidad, producto_id FROM devoluciones WHERE devolucion_id = '$devolucion_id_del'");

if($check_dev->rowCount()==1){
    $datos_devolucion = $check_dev->fetch();
    $dev_cantidad = $datos_devolucion['dev_cantidad'];
    $producto_id = $datos_devolucion['producto_id'];
    
    //actualizar stock
    $conn = con();
    $stock_actual = "SELECT producto_stock FROM producto WHERE producto_id = $producto_id";
    $result = $conn->query($stock_actual);
    $nuevo_stock = (int)$result->fetch()['producto_stock'] + $dev_cantidad;

    $actualizar_stock = $conn->prepare("UPDATE producto SET producto_stock = :nuevo_stock WHERE producto_id = :producto");
    $actualizar_stock->execute([
        ":nuevo_stock" => $nuevo_stock,
        ":producto" => $producto_id
    ]);

    //eliminar
    $elimdev = con();
    $elimdev=$elimdev->prepare("DELETE FROM devoluciones WHERE devolucion_id=:id");
    $elimdev->execute([":id"=> $devolucion_id_del]);

    if($elimdev->rowCount()==1){
        echo '
        <div class="notification is-success is-light">
            <strong>Devolución eliminada!</strong><br>
            La devolución ha sido eliminado exitosamente.
        </div>';
    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo eliminar el registro, inténtelo nuevamente.
        </div>';
    }
    $elimdev=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El registro que intenta eliminar no existe.
    </div>';
}
$check_dev=null;