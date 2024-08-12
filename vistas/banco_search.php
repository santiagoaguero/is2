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
        <h1 class="title">Entidad Bancaria</h1>
        <h2 class="subtitle">Buscar entidad bancaria</h2>
    </div>
<?php 
    require_once("./php/main.php");

    if(isset($_POST["modulo_buscador"])){
        require_once("./php/buscador.php");

    }

    if(!isset($_SESSION["busqueda_banco"]) && empty($_SESSION["busqueda_banco"]) ){


?>
    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="banco">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" maxlength="100" autofocus >
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
            <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="banco"> 
                <input type="hidden" name="eliminar_buscador" value="banco">
                <p>Estas buscando <strong>“<?php echo $_SESSION["busqueda_banco"];?>”</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
            </form>
        </div>
    </div>

    <?php
        //ELIMINAR CATEGORIAS
        if(isset($_GET["family_id_del"])){
            require_once("./php/banco_eliminar.php");
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
        $url= "index.php?vista=family_search&page=";
        $registros=10;//cantidad de registros por pagina
        $busqueda=$_SESSION["busqueda_banco"];//de categorias
        require_once("./php/banco_lista.php");

        }
    ?>
</div>