
if($guardar_cliente->rowCount()==1){// 1 usuario nuevo insertado
    echo '
    <div class="notification is-success is-light">
        <strong>Cliente registrado!</strong><br>
        El cliente se registró exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo registrar el cliente, inténtelo nuevamente.
    </div>';
}
$guardar_cliente=null; //cerrar conexion;