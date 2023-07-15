<?php
require_once("main.php");

$buscarProvee = con();

$prov = limpiar_cadena($_POST["busca_provee"]);

$sql = "SELECT prov_id, prov_nombre, prov_ruc FROM proveedor WHERE prov_nombre LIKE :prov OR prov_ruc LIKE :ruc ORDER BY prov_nombre ASC LIMIT 0,10";

$buscarProvee = $buscarProvee->prepare($sql);

$marcadores=[
    ":prov"=>'%'.$prov.'%', ":ruc"=>'%'.$prov.'%'];

$buscarProvee->execute($marcadores);

$html="";

while($row = $buscarProvee->fetch(PDO::FETCH_ASSOC)){
    $html .='
                <div class="media-content">
                    <div class="content">
                        <ul style="list-style-type: \'- \'; cursor: pointer;">
                            <li onclick="seleccionarProv(\''.$row["prov_id"].'\', \''.$row["prov_nombre"].'\', \''.$row["prov_ruc"].'\') " >'.$row["prov_nombre"].' - '.$row["prov_ruc"].'</li>
                        </ul>
                    </div>
                </div>
                <hr class="m-0">';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);


