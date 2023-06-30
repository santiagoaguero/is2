<?php
require_once("main.php");
require_once("../inc/session_start.php");
// Obtener los datos del objeto JSON
$data = json_decode(file_get_contents('php://input'), true);

$nombre = $data['nombre'];
$ruc = $data['ruc'];
$productos = $data['productos'];
$usuario = $_SESSION["nombre"].$_SESSION["apellido"];

// Construir la respuesta en formato HTML
$htmlResponse = '<h2>Datos del cliente:</h2>';
$htmlResponse .= '<p>Nombre: ' . $nombre . '</p>';
$htmlResponse .= '<p>RUC: ' . $ruc . '</p>';

$htmlResponse .= '<h2>Productos seleccionados:</h2>';
$htmlResponse .= '<table>';
$htmlResponse .= '<tr><th>Nombre</th><th>Cantidad</th><th>Precio</th></tr>';

foreach ($productos as $producto) {
    $htmlResponse .= '<tr>';
    $htmlResponse .= '<td>' . $producto['nombre'] . '</td>';
    $htmlResponse .= '<td>' . $producto['cantidad'] . '</td>';
    $htmlResponse .= '<td>' . $producto['precio'] . '</td>';
    $htmlResponse .= '</tr>';
}

$htmlResponse .= '</table>';

// Responder con la cadena HTML como respuesta
echo $htmlResponse;