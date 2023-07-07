<?php
require_once("main.php");

//almacenando datos
$id=limpiar_cadena($_POST["prod_id"]);
$cantidad=limpiar_cadena($_POST["prod_cant"]);
$precio=limpiar_cadena($_POST["prod_precio"]);

//verifica campos obligatorios
if($cantidad == "" || is_numeric($cantidad) == false){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Debes ingresar una cantidad.
    </div>';
    exit();
}

//verifica integridad de los datos
if(verificar_datos("[1-9]{1,3}",$cantidad)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Cantidad no admitida.
    </div>';
    exit();
}

//verifica producto id
$check_id = con();
$check_id=$check_id->query("SELECT * FROM producto WHERE producto_id = '$id'");

if($check_id->rowCount()<=0){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El producto que intenta agregar no existe.
    </div>';
    exit();
}
$check_id=null;

echo $id;

//guardando datos
$guardar_temp = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_temp = $guardar_temp->prepare("INSERT INTO
    detalle_temp(producto_id, cantidad, precio_venta)
    VALUES(:id, :cantidad, :precio)");

//evitando inyecciones sql xss
$marcadores=[
    ":id"=>$id, ":cantidad"=>$cantidad, ":precio"=>$precio];

$guardar_temp->execute($marcadores);

if($guardar_temp->rowCount()==1){// 1 usuario nuevo insertado
    echo '
    <div class="notification is-success is-light">
        <strong>DEtalle Temporal registrado!</strong><br>
        El detalle temporal se registró exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo detalle temporal , inténtelo nuevamente.
    </div>';
}
$guardar_temp=null; //cerrar conexion;