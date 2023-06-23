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

}

//verifica integridad de los datos
if(verificar_datos("[1-9]{1,3}",$cantidad)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Cantidad no admitida.
    </div>';
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
}
$check_id=null;

echo $id;