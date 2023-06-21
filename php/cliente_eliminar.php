<?php
$client_id_del = limpiar_cadena($_GET["client_id_del"]);

//verifica cliente
$check_cliente = con();
$check_cliente=$check_cliente->query("SELECT * FROM clientes WHERE cliente_id = '$client_id_del'");

if($check_cliente->rowCount()==1){
    $datos = $check_cliente->fetch();

    $eliminar_cliente = con();
    $eliminar_cliente=$eliminar_cliente->prepare("DELETE FROM clientes WHERE cliente_id=:id");
    //filtro prepare para evitar inyecciones sql xss

    $eliminar_cliente->execute([":id"=> $client_id_del]);
    if($eliminar_cliente->rowCount()==1){

        echo '
        <div class="notification is-success is-light">
            <strong>Cliente eliminado!</strong><br>
            El cliente ha sido eliminado exitosamente.
        </div>';
    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo eliminar el cliente, inténtelo nuevamente.
        </div>';
    }
    $eliminar_cliente=null;
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El cliente que intenta eliminar no existe.
    </div>';
}

$check_cliente=null;