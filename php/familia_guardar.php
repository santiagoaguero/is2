<?php
include_once("main.php");

//almacenando datos
$nombre=limpiar_cadena($_POST["familia_nombre"]);

//verifica campos obligatorios
if($nombre == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

//verifica integridad de los datos
if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        El NOMBRE no coincide con el formato esperado.
    </div>';
    exit();
}

//verifica nombre familia unico
$check_nombre=con();
//query: inserta la consulta directo a la bd
$check_nombre = $check_nombre->query("SELECT familia_nombre FROM familia 
WHERE familia_nombre = '$nombre'");//checks if familia exists
if($check_nombre->rowCount()>0){//familia found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Esta familia ya está registrada en la base de datos, por favor elija otra familia.
    </div>';
    exit();
}
$check_nombre=null;//close db connection

//guardando datos
$guardar_familia = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_familia = $guardar_familia->prepare("INSERT INTO
    familia(familia_nombre) VALUES(:nombre)");

$marcadores=[
    ":nombre"=>$nombre];

$guardar_familia->execute($marcadores);
if($guardar_familia->rowCount()==1){//1 fila afectada
    echo '
    <div class="notification is-success is-light">
        <strong>Familia registrada!</strong><br>
        La familia se registró exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo registrar la familia, inténtelo nuevamente.
    </div>';
}
$guardar_familia=null;