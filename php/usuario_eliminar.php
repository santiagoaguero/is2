<?php
$user_id_del = limpiar_cadena($_GET["user_id_del"]);

//verifica usuario
$check_usuario = con();
$check_usuario=$check_usuario->query("SELECT usuario_id FROM usuario WHERE usuario_id = '$user_id_del'");
if($check_usuario->rowCount()==1){

    //verifica productos asociados a usuario
    $check_productos = con();
    $check_productos=$check_productos->query("SELECT usuario_id FROM producto WHERE usuario_id = '$user_id_del' LIMIT 1");// minimo 1 producto
    if($check_productos->rowCount()<=0){//no tiene productos asociados

        $eliminar_usuario = con();
        $eliminar_usuario=$eliminar_usuario->prepare("DELETE FROM usuario WHERE usuario_id=:id");
        //filtro prepare para evitar inyecciones sql xss

        $eliminar_usuario->execute([":id"=> $user_id_del]);

        if($eliminar_usuario->rowCount()==1){
            echo '
            <div class="notification is-success is-light">
                <strong>¡Usuario eliminado!</strong><br>
                El usuario ha sido eliminado exitosamente.
            </div>';
        } else {
            echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No se pudo eliminar el usuario, inténtelo nuevamente.
            </div>';
        }
         
        $eliminar_usuario=null;

    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            El usuario tiene productos asociados.
        </div>';
    }
    $check_productos=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El usuario que intenta eliminar no existe.
    </div>';
}
//cerrar con
$check_usuario=null;