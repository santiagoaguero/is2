<?php
$banco_id_del = limpiar_cadena($_GET["banco_id_del"]);

//verifica categoria
$check_bancos = con();
$check_bancos=$check_bancos->query("SELECT banco_id FROM bancos WHERE banco_id = '$banco_id_del'");

if($check_bancos->rowCount()==1){
        //verifica productos asociados a familia
        // $check_facturas = con();
        // $check_facturas=$check_facturas->query("SELECT banco_id FROM facturas WHERE banco_id = '$banco_id_del' LIMIT 1");// minimo 1 producto
        
        // if($check_facturas->rowCount()<=0){//no tiene productos asociados
        if(true){
            $eliminar_banco = con();
            $eliminar_banco=$eliminar_banco->prepare("DELETE FROM bancos WHERE banco_id=:id");
            //filtro prepare para evitar inyecciones sql xss
    
            $eliminar_banco->execute([":id"=> $banco_id_del]);

            if($eliminar_banco->rowCount()==1){
                echo '
                <div class="notification is-success is-light">
                    <strong>Entidad Bancaria eliminada!</strong><br>
                    El registro ha sido eliminado exitosamente.
                </div>';
            } else {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar la entidad bancaria, inténtelo nuevamente.
                </div>';
            }
            $eliminar_banco=null;
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El banco tiene facturas asociadas.
            </div>';
        }
        $check_facturas=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El banco que intenta eliminar no existe.
    </div>';
}
$check_bancos=null;