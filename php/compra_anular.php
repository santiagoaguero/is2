<?php
$id = limpiar_cadena($_GET["id"]);

//verifica proveedor
$check_fact = con();
$check_fact=$check_fact->query("SELECT compra_factura FROM compras WHERE compra_id = '$id'");

if($check_fact->rowCount()==1){
    $datos = $check_fact->fetch();
    $anular = con();
    $anular=$anular->prepare("UPDATE compras SET compra_estado = 0 WHERE compra_id = :fact");
    //filtro prepare para evitar inyecciones sql xss

    $anular->execute([":fact"=> $id]);
    if($anular->rowCount()==1){

        echo '
        <div class="notification is-success is-light">
            <strong>Compra anulada!</strong><br>
            La compra ha sido anulada exitosamente.
        </div>';
    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo anular la compra, inténtelo nuevamente.
        </div>';
    }
    $anular=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        La compra que intenta anular no existe.
    </div>';
}