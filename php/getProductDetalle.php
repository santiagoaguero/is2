<?php
require_once("main.php");

$buscarProd = con();

$campo = limpiar_cadena($_POST["campo"]);

$sql = "SELECT producto_id, producto_nombre, producto_codigo, producto_precio, producto_iva from producto WHERE producto_nombre LIKE :producto OR producto_codigo LIKE :producto ORDER BY producto_nombre ASC LIMIT 0,10";

$buscarProd = $buscarProd->prepare($sql);

$marcadores=[
    ":producto"=>'%'.$campo.'%'];

$buscarProd->execute($marcadores);

$html="";
$contador=1;

while($row = $buscarProd->fetch(PDO::FETCH_ASSOC)){
    $precio_entero = number_format($row["producto_precio"], 0, ',', '.');
    $html .='
                <div class="media-content">
                    <div class="content mb-0">
                        <p>
                            <strong>'.$contador.' - '.$row["producto_nombre"].'</strong><br>
                            <strong>COD. BARRA:</strong> '.$row["producto_codigo"].' - 
                            <strong>PRECIO:</strong> ₲s '.$precio_entero.' -
                        </p>
                    </div>
                    
                    <div class="form-rest mb-2 mt-2"></div>

                    <div class="columna-visible">
                        <table class="table">
                            <tbody>
                                <tr>

                                <td><input class="input is-rounded" type="number" name="cantidad_producto'.$row["producto_id"].'" min="1" max="999" placeholder="Cantidad"></td>
                                
                                <td><input name="agregar" type="button" value="Agregar" onclick="seleccionarProducto(\''.$row["producto_id"].'\', \''.$row["producto_nombre"].'\', \''.$row["producto_precio"].'\', \''.$row["producto_iva"].'\') " class="button is-success is-rounded"></input></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
        

                    </div>
                <hr>';
    $contador++;
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);


