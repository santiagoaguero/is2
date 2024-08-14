<?php
include_once("main.php");

//almacenando datos
$nombre = $_POST["nombre"];

//verifica campos obligatorios
if($nombre == ""){
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

//verifica unico
$check_nombre=con();
//query: inserta la consulta directo a la bd
$check_nombre = $check_nombre->query("SELECT dep_nombre FROM depositos WHERE dep_nombre = '$nombre'");//checks if exists
if($check_nombre->rowCount()>0){//found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Este depósito ya está registrado en la base de datos.
    </div>';
    exit();
}
$check_nombre=null;//close db connection

//guardando datos
$guardar_datos = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_datos = $guardar_datos->prepare("INSERT INTO depositos(dep_nombre) VALUES(:dep_nombre)");

$marcadores=[
    ":dep_nombre" => $nombre
];

$guardar_datos->execute($marcadores);
if($guardar_datos->rowCount()==1){//1 fila afectada
    echo '
    <div class="notification is-success is-light">
        <strong>Guardado correctamente!</strong><br>
        El registro se guardó exitosamente.
    </div>';
} else {
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        No se pudo procesar la solicitud, inténtelo nuevamente.
    </div>';
}
$guardar_datos=null;