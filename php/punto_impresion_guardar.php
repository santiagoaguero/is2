<?php
include_once("main.php");

//almacenando datos
$nombre = $_POST["nombre"];
$proximo_nro = $_POST["proximo_nro"];
$timbrado = $_POST["timbrado"];
$sucursal = $_POST["sucursal"];
$estado = $_POST["estado"];

//verifica campos obligatorios
if($nombre == "" || $proximo_nro == "" || $timbrado == ""){
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
$check_nombre = $check_nombre->query("SELECT punimp_nombre FROM puntos_impresion WHERE punimp_nombre = '$nombre'");//checks if exists
if($check_nombre->rowCount()>0){//found
    echo '
    <div class="notification is-danger is-light">
        <strong>¡Ocurrió un error inesperado!</strong><br>
        Este punto de impresión ya está registrado en la base de datos.
    </div>';
    exit();
}
$check_nombre=null;//close db connection

//guardando datos
$guardar_datos = con();
//prepare: prepara la consulta antes de insertar directo a la bd. variables sin comillas ni $
$guardar_datos = $guardar_datos->prepare("INSERT INTO puntos_impresion(punimp_nombre, punimp_prox_nro_fac, timbrado_id, sucursal_id, punimp_estado) VALUES(:punimp_nombre, :punimp_prox_nro_fac, :timbrado_id, :sucursal_id, :punimp_estado)");

$marcadores=[
    ":punimp_nombre" => $nombre,
    ":punimp_prox_nro_fac" => $proximo_nro,
    ":timbrado_id" => $timbrado,
    ":sucursal_id" => $sucursal,
    ":punimp_estado" => $estado
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