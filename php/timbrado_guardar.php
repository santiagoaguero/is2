<?php
include_once("main.php");

//almacenando datos
$numero = $_POST["timbrado_numero"];
$fecha_emision = $_POST["timbrado_fecha_emision"];
$fecha_vencimiento = $_POST["timbrado_fecha_vencimiento"];
$estado = $_POST["timbrado_estado"];
$descripcion = $_POST["timbrado_descripcion"];

//verifica campos obligatorios
if($numero == "" || $fecha_emision == "" || $fecha_vencimiento == ""){
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
$check_nombre = $check_nombre->query("SELECT numero FROM timbrados 
WHERE numero = '$numero'");//checks if exists
if($check_nombre->rowCount()>0){//found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Este timbrado ya está registrado en la base de datos.
    </div>';
    exit();
}
$check_nombre=null;//close db connection

//guardando datos
$guardar_datos = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_datos = $guardar_datos->prepare("INSERT INTO timbrados(numero, fecha_emision, fecha_vencimiento, estado, descripcion) VALUES(:numero, :fecha_emision, :fecha_vencimiento, :estado, :descripcion)");

$marcadores=[
    ":numero" => $numero,
    ":fecha_emision" => $fecha_emision,
    ":fecha_vencimiento" => $fecha_vencimiento,
    ":estado" => $estado,
    ":descripcion" => $descripcion
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