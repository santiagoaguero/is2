<?php
$timbrado_id_del = limpiar_cadena($_GET["timbrado_id_del"]);

//verifica categoria
$check_timbrado = con();
$check_timbrado=$check_timbrado->query("SELECT timbrado_id FROM timbrados WHERE timbrado_id = '$timbrado_id_del'");

if($check_timbrado->rowCount()==1){
        //verifica productos asociados a familia
        $check_facturas = con();
        $check_facturas=$check_facturas->query("SELECT timbrado_id FROM facturas WHERE timbrado_id = '$timbrado_id_del' LIMIT 1");// minimo 1 producto
        
        if($check_facturas->rowCount()<=0){//no tiene productos asociados
            $eliminar_timbrado = con();
            $eliminar_timbrado=$eliminar_timbrado->prepare("DELETE FROM timbrados WHERE timbrado_id=:id");
            //filtro prepare para evitar inyecciones sql xss
    
            $eliminar_timbrado->execute([":id"=> $timbrado_id_del]);

            if($eliminar_timbrado->rowCount()==1){
                echo '
                <div class="notification is-success is-light">
                    <strong>Timbrado eliminado!</strong><br>
                    El timbrado ha sido eliminado exitosamente.
                </div>';
            } else {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar el timbrado, inténtelo nuevamente.
                </div>';
            }
            $eliminar_timbrado=null;
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El timbrado tiene facturas asociadas.
            </div>';
        }
        $check_facturas=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El timbrado que intenta eliminar no existe.
    </div>';
}
$check_timbrado=null;