<?php
$prov_id_del = limpiar_cadena($_GET["prov_id_del"]);

//verifica proveedor
$check_provee = con();
$check_provee=$check_provee->query("SELECT prov_id FROM proveedor WHERE prov_id = '$prov_id_del'");

if($check_provee->rowCount()==1){
        //verifica productos asociados a proveedor
        $check_productos = con();
        $check_productos=$check_productos->query("SELECT prov_id FROM producto WHERE prov_id = '$prov_id_del' LIMIT 1");// minimo 1 producto
        
        if($check_productos->rowCount()<=0){//no tiene productos asociados
            $eliminar_prov = con();
            $eliminar_prov=$eliminar_prov->prepare("DELETE FROM proveedor WHERE prov_id=:id");
            //filtro prepare para evitar inyecciones sql xss
    
            $eliminar_prov->execute([":id"=> $prov_id_del]);

            if($eliminar_prov->rowCount()==1){
                echo '
                <div class="notification is-success is-light">
                    <strong>Proveedor eliminado!</strong><br>
                    El proveedor ha sido eliminado exitosamente.
                </div>';
            } else {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar el proveedor, inténtelo nuevamente.
                </div>';
            }
            $eliminar_prov=null;
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El proveedor tiene productos asociados.
            </div>';
        }
        $check_productos=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El proveedor que intenta eliminar no existe.
    </div>';
}
$check_provee=null;