<?php
$forma_pago_id_del = limpiar_cadena($_GET["forma_pago_id_del"]);

//verifica categoria
$check_forma_pago = con();
$check_forma_pago=$check_forma_pago->query("SELECT forma_pago_id FROM formas_pago WHERE forma_pago_id = '$forma_pago_id_del'");

if($check_forma_pago->rowCount()==1){
        //verifica productos asociados a familia
        $check_facturas = con();
        $check_facturas=$check_facturas->query("SELECT forma_pago_id FROM facturas WHERE forma_pago_id = '$forma_pago_id_del' LIMIT 1");// minimo 1 producto
        
        if($check_facturas->rowCount()<=0){//no tiene productos asociados
            $eliminar_forma_pago = con();
            $eliminar_forma_pago=$eliminar_forma_pago->prepare("DELETE FROM formas_pago WHERE forma_pago_id=:id");
            //filtro prepare para evitar inyecciones sql xss
    
            $eliminar_forma_pago->execute([":id"=> $forma_pago_id_del]);

            if($eliminar_forma_pago->rowCount()==1){
                echo '
                <div class="notification is-success is-light">
                    <strong>Forma de Pago eliminada!</strong><br>
                    La forma de pago ha sido eliminada exitosamente.
                </div>';
            } else {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar la forma de pago, inténtelo nuevamente.
                </div>';
            }
            $eliminar_forma_pago=null;
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La forma de pago tiene facturas asociadas.
            </div>';
        }
        $check_facturas=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        La forma de pago que intenta eliminar no existe.
    </div>';
}
$check_forma_pago=null;