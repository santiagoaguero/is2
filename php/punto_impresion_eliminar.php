<?php
$punto_impresion_id_del = limpiar_cadena($_GET["punto_impresion_id_del"]);

//verifica categoria
$check_punto_impresion = con();
$check_punto_impresion=$check_punto_impresion->query("SELECT punto_impresion_id FROM puntos_impresion WHERE punto_impresion_id = '$punto_impresion_id_del'");

if($check_punto_impresion->rowCount()==1){
        //verifica productos asociados a familia
        // $check_facturas = con();
        // $check_facturas=$check_facturas->query("SELECT punto_impresion_id FROM facturas WHERE punto_impresion_id = '$punto_impresion_id_del' LIMIT 1");// minimo 1 producto
        
        // if($check_facturas->rowCount()<=0){//no tiene productos asociados
        if(true) { 
            $eliminar_punto_impresion = con();
            $eliminar_punto_impresion=$eliminar_punto_impresion->prepare("DELETE FROM puntos_impresion WHERE punto_impresion_id=:id");
            //filtro prepare para evitar inyecciones sql xss
    
            $eliminar_punto_impresion->execute([":id"=> $punto_impresion_id_del]);

            if($eliminar_punto_impresion->rowCount()==1){
                echo '
                <div class="notification is-success is-light">
                    <strong>Punto de Impresión eliminado!</strong><br>
                    El punto de impresión ha sido eliminado exitosamente.
                </div>';
            } else {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar el punto de impresión, inténtelo nuevamente.
                </div>';
            }
            $eliminar_punto_impresion=null;
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El punto de impresión tiene facturas asociadas.
            </div>';
        }
        $check_facturas=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El punto de impresión que intenta eliminar no existe.
    </div>';
}
$check_punto_impresion=null;