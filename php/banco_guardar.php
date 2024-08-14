<?php
include_once("main.php");

//almacenando datos
$nombre = $_POST["nombre"];
$ruc = $_POST["ruc"];
$direccion = $_POST["direccion"];
$telefono = $_POST["telefono"];
$mail = $_POST["mail"];

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
$check_nombre = $check_nombre->query("SELECT ban_nombre FROM bancos WHERE ban_nombre = '$nombre'");//checks if exists
if($check_nombre->rowCount()>0){//found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Esta entidad bancaria ya está registrada en la base de datos.
    </div>';
    exit();
}
$check_nombre=null;//close db connection

//guardando datos
$guardar_datos = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_datos = $guardar_datos->prepare("INSERT INTO bancos(ban_nombre, ban_ruc, ban_direccion, ban_telefono, ban_mail) VALUES(:ban_nombre, :ban_ruc, :ban_direccion, :ban_telefono, :ban_mail)");

$marcadores=[
    ":ban_nombre" => $nombre,
    ":ban_ruc" => $ruc,
    ":ban_direccion" => $direccion,
    ":ban_telefono" => $telefono,
    ":ban_mail" => $mail
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