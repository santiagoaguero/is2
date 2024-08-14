<?php
$movimiento_banco_id_del = limpiar_cadena($_GET["movimiento_banco_id_del"]);

//verifica categoria
$check_movimiento = con();
$check_movimiento=$check_movimiento->query("SELECT movimiento_banco_id FROM movimientos_bancos WHERE movimiento_banco_id = '$movimiento_banco_id_del'");

if($check_movimiento->rowCount()==1){
        //verifica productos asociados a familia
        // $check_facturas = con();
        // $check_facturas=$check_facturas->query("SELECT movimiento_banco_id FROM facturas WHERE movimiento_banco_id = '$movimiento_banco_id_del' LIMIT 1");// minimo 1 producto
        
        // if($check_facturas->rowCount()<=0){//no tiene productos asociados
        if(true){
            $eliminar_movimiento = con();
            $eliminar_movimiento=$eliminar_movimiento->prepare("DELETE FROM movimientos_bancos WHERE movimiento_banco_id=:id");
            //filtro prepare para evitar inyecciones sql xss
    
            $eliminar_movimiento->execute([":id"=> $movimiento_banco_id_del]);

            if($eliminar_movimiento->rowCount()==1){
                echo '
                <div class="notification is-success is-light">
                    <strong>Movimiento Bancario eliminado!</strong><br>
                    El movimiento bancario ha sido eliminado exitosamente.
                </div>';
            } else {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    No se pudo eliminar el movimiento bancario, inténtelo nuevamente.
                </div>';
            }
            $eliminar_movimiento=null;
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                El movimiento bancario tiene facturas asociadas.
            </div>';
        }
        $check_facturas=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El movimiento bancario que intenta eliminar no existe.
    </div>';
}
$check_movimiento=null;