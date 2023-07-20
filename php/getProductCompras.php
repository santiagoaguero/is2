<?php
require_once("main.php");

$buscarProd = con();

$campo = limpiar_cadena($_POST["campo"]);

$sql = "SELECT producto_id, producto_nombre, producto_codigo, producto_iva from producto WHERE producto_nombre LIKE :producto OR producto_codigo LIKE :producto ORDER BY producto_nombre ASC LIMIT 0,10";

$buscarProd = $buscarProd->prepare($sql);

$marcadores=[
    ":producto"=>'%'.$campo.'%'];

$buscarProd->execute($marcadores);

$html="";
$contador=1;

while($row = $buscarProd->fetch(PDO::FETCH_ASSOC)){
    $html .='
                <div class="columns">
                    <div class="column">
                        <p>
                            <strong>'.$contador.' - '.$row["producto_nombre"].'</strong><br>
                            <strong>COD. BARRA:</strong> '.$row["producto_codigo"].' - 
                        </p>
                    </div>
                    <div class="column has-text-centered">
                        <input name="agregar" type="button" value="Agregar" onclick="seleccionarProducto(\''.$row["producto_id"].'\', \''.$row["producto_nombre"].'\', \''.$row["producto_iva"].'\') " class="button is-success is-rounded"></input>
                    </div>
                </div>

                <hr>';
    $contador++;
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);


