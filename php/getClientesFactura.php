<?php
require_once("main.php");

$buscarCliente = con();

$cliente = limpiar_cadena($_POST["busca_cliente"]);

$sql = "SELECT cliente_id, cliente_nombre, cliente_ruc FROM clientes WHERE cliente_nombre LIKE :cliente OR cliente_ruc LIKE :ruc ORDER BY cliente_nombre ASC LIMIT 0,10";

$buscarCliente = $buscarCliente->prepare($sql);

$marcadores=[
    ":cliente"=>'%'.$cliente.'%', ":ruc"=>'%'.$cliente.'%'];

$buscarCliente->execute($marcadores);

$html="";

while($row = $buscarCliente->fetch(PDO::FETCH_ASSOC)){
    $html .='
                <div class="media-content">
                    <div class="content mb-0">
                        <ul style="list-style-type: \'- \'; cursor: pointer;">
                            <li onclick="seleccionarCliente(\''.$row["cliente_id"].'\', \''.$row["cliente_nombre"].'\', \''.$row["cliente_ruc"].'\') " >'.$row["cliente_nombre"].' - '.$row["cliente_ruc"].'</li>
                        </ul>
                    </div>
                </div>
                <hr class="m-0">';
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);


