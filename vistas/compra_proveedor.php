<?php // Verificar los permisos del usuario para esta página
	include("./inc/check_rol.php");
	if (isset($_SESSION['rol']) && isset($_GET['vista'])) {
		$vistaSolicitada = $_GET['vista'];
		$rolUsuario = $_SESSION['rol'];
	
		check_rol($vistaSolicitada, $rolUsuario);
		
	} else {
        header("Location: login.php");
        exit();
    }
?>

<div class="container pb-6 pt-6">
    <div class="is-fluid mb-2">
        <h1 class="title">Compras</h1>
        <h2 class="subtitle">Lista de compras por proveedor</h2>
    </div>
<?php 
    require_once("./php/main.php");
?>
    <div class="columns">

        <div class="column is-one-third">
            <h2 class="title has-text-centered">Proveedores</h2>
                <?php
                    $proveedores = con();
                    $proveedores = $proveedores->query("SELECT * FROM proveedor");
                    if($proveedores->rowCount()>0){
                        $proveedores = $proveedores->fetchAll();
                        foreach($proveedores as $key){
                            echo '<a href="index.php?vista=compra_proveedor&provider_id='.$key["prov_id"].'" class="button is-link is-inverted is-fullwidth">'.$key["prov_nombre"].'</a>';
                        }
                    } else {
                        echo '<p class="has-text-centered" >No hay proveedores registrados</p>';
                    }
                    $proveedores=null;
                    ?>
        </div>

        <div class="column">
            <?php 
            $prov_id = (isset($_GET["provider_id"])) ? $_GET["provider_id"] : 0;

            $proveedor = con();
            $proveedor = $proveedor->query("SELECT * FROM proveedor WHERE prov_id = '$prov_id'");
            if($proveedor->rowCount()>0){

                $proveedor = $proveedor->fetch();

                echo '<h2 class="title has-text-centered">'.$proveedor["prov_nombre"].'</h2>';

                //ELIMINAR PRODUCTOS
                if(isset($_GET["id"])){
                    require_once("./php/compra_anular.php");
                }

                if(!isset($_GET["page"])){
                    $pagina = 1;
                } else {
                    $pagina = (int)$_GET["page"];
                    if($pagina<=1){
                        $pagina = 1;//controlar que siempre sea 1
                    }
                }

                $pagina = limpiar_cadena($pagina);
                $url= "index.php?vista=compra_proveedor&provider_id=$prov_id&page=";
                $registros=15;//cantidad de registros por pagina
                $busqueda="";//de categorias
                
                require_once("./php/compra_lista.php");

            } else {
                echo '<h2 class="has-text-centered title" >Seleccione un proveedor</h2>';
            }

            $proveedor=null;
            ?>


        </div>

    </div>
</div>