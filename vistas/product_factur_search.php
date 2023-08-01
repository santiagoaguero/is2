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
<div class="container pb-6 pt-1">
<?php 
    require_once("./php/main.php");

    if(isset($_POST["modulo_buscador"])){
        require_once("./php/buscador.php");

    }

    if(!isset($_SESSION["busqueda_factura_producto"]) && empty($_SESSION["busqueda_factura_producto"]) ){


?>
    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="factura_producto">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
                    </p>
                    <p class="control">
                        <button class="button is-info" type="submit" >Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php   
        } else { 
    ?>
    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-1 mb-1" action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="factura_producto"> 
                <input type="hidden" name="eliminar_buscador" value="factura_producto">
                <p>Estas buscando <strong>"<?php echo $_SESSION["busqueda_factura_producto"];?>"</strong></p>
                <br>
                <button type="submit" class="button is-small is-danger is-rounded">Eliminar busqueda</button>
            </form>
        </div>
    </div>
    <?php
        if(!isset($_GET["page"])){
            $pagina = 1;
        } else {
            $pagina = (int)$_GET["page"];
            if($pagina<=1){
                $pagina = 1;//controlar que siempre sea 1
            }
        }
        //ELIMINAR CATEGORIAS
        if(isset($_GET["product_id_del"])){
            require_once("./php/producto_eliminar.php");
        }

        $categoria_id = (isset($_GET["category_id"])) ? $_GET["category_id"] : 0;
        $pagina = limpiar_cadena($pagina);
        $url= "index.php?vista=factur_new&page=";
        $registros=15;//cantidad de registros por pagina
        $busqueda=$_SESSION["busqueda_factura_producto"];//de productos
        require_once("./php/producto_factura_lista.php");

        }
    ?>
</div>