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
        <h1 class="title">Movimientos Bancarios</h1>
        <h2 class="subtitle">Lista de movimientos bancarios</h2>
    </div>
<?php 
    require_once("./php/main.php");

    //ELIMINAR CATEGORIAS
    if(isset($_GET["movimiento_banco_id_del"])){
        require_once("./php/movimiento_banco_eliminar.php");
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
    $url= "index.php?vista=movimiento_banco_list&page=";
    $registros=10;//cantidad de registros por pagina
    $busqueda="";//de categorias
    require_once("./php/movimiento_banco_lista.php");

?>

</div>