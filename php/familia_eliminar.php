<?php
$family_id_del = limpiar_cadena($_GET["family_id_del"]);

//verifica categoria
$check_family = con();
$check_family=$check_family->query("SELECT familia_id FROM familia WHERE familia_id = '$family_id_del'");

if($check_family->rowCount()==1){
        //verifica productos asociados a familia
        $check_productos = con();
        $check_productos=$check_productos->query("SELECT familia_id FROM producto WHERE familia_id = '$family_id_del' LIMIT 1");// minimo 1 producto
        
        if($check_productos->rowCount()<=0){//no tiene productos asociados
            $eliminar_familia = con();
            $eliminar_familia=$eliminar_familia->prepare("DELETE FROM familia WHERE familia_id=:id");
            //filtro prepare para evitar inyecciones sql xss
    
            $eliminar_familia->execute([":id"=> $family_id_del]);

            if($eliminar_familia->rowCount()==1){
                echo '
                <div class="notification is-success is-light">
                    <strong>Familia eliminada!</strong><br>
                    La familia ha sido eliminada exitosamente.
                </div>';
            } else {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar la familia, inténtelo nuevamente.
                </div>';
            }
            $eliminar_familia=null;
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La familia tiene productos asociados.
            </div>';
        }
        $check_productos=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        La categoría que intenta eliminar no existe.
    </div>';
}
$check_family=null;