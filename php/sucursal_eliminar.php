<?php
$sucursal_id_del = limpiar_cadena($_GET["sucursal_id_del"]);

//verifica categoria
$check_sucursales = con();
$check_sucursales=$check_sucursales->query("SELECT sucursal_id FROM sucursales WHERE sucursal_id = '$sucursal_id_del'");

if($check_sucursales->rowCount()==1){
        //verifica puntos_impresion asociados a sucursal
        $check_facturas = con();
        $check_facturas=$check_facturas->query("SELECT sucursal_id FROM puntos_impresion WHERE sucursal_id = '$sucursal_id_del' LIMIT 1");// minimo 1 producto
        
        if($check_facturas->rowCount()<=0){//no tiene productos asociados
            $eliminar_sucursales = con();
            $eliminar_sucursales=$eliminar_sucursales->prepare("DELETE FROM sucursales WHERE sucursal_id=:id");
            //filtro prepare para evitar inyecciones sql xss
    
            $eliminar_sucursales->execute([":id"=> $sucursal_id_del]);

            if($eliminar_sucursales->rowCount()==1){
                echo '
                <div class="notification is-success is-light">
                    <strong>Sucursal eliminada!</strong><br>
                    La sucursal ha sido eliminada exitosamente.
                </div>';
            } else {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar la sucursal, inténtelo nuevamente.
                </div>';
            }
            $eliminar_sucursales=null;
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La sucursal tiene puntos de impresión asociados.
            </div>';
        }
        $check_facturas=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        La sucursal que intenta eliminar no existe.
    </div>';
}
$check_sucursales=null;