<?php
$deposito_id_del = limpiar_cadena($_GET["deposito_id_del"]);

//verifica categoria
$check_depositos = con();
$check_depositos=$check_depositos->query("SELECT deposito_id FROM depositos WHERE deposito_id = '$deposito_id_del'");

if($check_depositos->rowCount()==1){
        //verifica productos asociados a familia
        // $check_facturas = con();
        // $check_facturas=$check_facturas->query("SELECT deposito_id FROM facturas WHERE deposito_id = '$deposito_id_del' LIMIT 1");// minimo 1 producto
        
        // if($check_facturas->rowCount()<=0){//no tiene productos asociados
        if(true){
            $eliminar_dep = con();
            $eliminar_dep=$eliminar_dep->prepare("DELETE FROM depositos WHERE deposito_id=:id");
            //filtro prepare para evitar inyecciones sql xss
    
            $eliminar_dep->execute([":id"=> $deposito_id_del]);

            if($eliminar_dep->rowCount()==1){
                echo '
                <div class="notification is-success is-light">
                    <strong>Depósito eliminado!</strong><br>
                    El registro ha sido eliminado exitosamente.
                </div>';
            } else {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar el depósito, inténtelo nuevamente.
                </div>';
            }
            $eliminar_dep=null;
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El depósito tiene facturas asociadas.
            </div>';
        }
        $check_facturas=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El depósito que intenta eliminar no existe.
    </div>';
}
$check_depositos=null;