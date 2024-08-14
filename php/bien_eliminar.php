<?php
$bien_id_del = limpiar_cadena($_GET["bien_id_del"]);

//verifica categoria
$check_bien = con();
$check_bien=$check_bien->query("SELECT bien_id FROM bienes WHERE bien_id = '$bien_id_del'");

if($check_bien->rowCount()==1){
    $eliminar_bien = con();
    $eliminar_bien=$eliminar_bien->prepare("DELETE FROM bienes WHERE bien_id=:id");
    //filtro prepare para evitar inyecciones sql xss

    $eliminar_bien->execute([":id"=> $bien_id_del]);

    if($eliminar_bien->rowCount()==1){
        echo '
        <div class="notification is-success is-light">
            <strong>Bien/Activo eliminado!</strong><br>
            El registro ha sido eliminado exitosamente.
        </div>';
    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo eliminar el registro, inténtelo nuevamente.
        </div>';
    }
    $eliminar_bien=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El registro que intenta eliminar no existe.
    </div>';
}
$check_bien=null;