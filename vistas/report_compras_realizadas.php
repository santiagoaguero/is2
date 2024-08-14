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
		<h1 class="title">Reporte</h1>
		<h2 class="subtitle">Compras Realizadas</h2>
	</div>
    <?php 
    require_once("./php/main.php");
    ?>

	<div class="form-rest mb-6 mt-6"></div>

	<form target="_blank" action="./php/reporte_compras_realizadas.php" method="POST" autocomplete="off" enctype="multipart/form-data" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Compras desde</span>
				  	<input class="input" type="date" name="filtro_desde" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70" value="1999-01-01" required autofocus>
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Compras hasta</span>
				  	<input class="input" type="date" name="filtro_hasta" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70" value="<?php echo date("Y-m-d") ?>" required autofocus>
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Generar Reporte</button>
		</p>
	</form>
</div>