<?php
$fact_nro = limpiar_cadena($_GET["fact_nro"]);

//verifica proveedor
$check_fact = con();
$check_fact=$check_fact->query("SELECT factura_numero FROM facturas WHERE factura_numero = '$fact_nro'");

if($check_fact->rowCount()==1){
    $datos = $check_fact->fetch();
    $anular = con();
    $anular=$anular->prepare("UPDATE facturas SET factura_estado = 0 WHERE factura_numero = :fact");
    //filtro prepare para evitar inyecciones sql xss

    $anular->execute([":fact"=> $fact_nro]);
    if($anular->rowCount()==1){

        echo '
        <div class="notification is-success is-light">
            <strong>¡Factura anulada!</strong><br>
            La factura ha sido anulada exitosamente.
        </div>';
    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo anular la factura, inténtelo nuevamente.
        </div>';
    }
    $anular=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        La factura que intenta anular no existe.
    </div>';
}