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
		<h2 class="subtitle">Nueva entidad bancaria</h2>
	</div>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/banco_guardar.php" method="POST" class="formularioAjax" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="nombre" required autofocus >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>RUC</label>
					<input class="input" type="text" name="ruc" autofocus >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Dirección</label>
					<input class="input" type="text" name="direccion" autofocus >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Teléfono</label>
					<input class="input" type="text" name="telefono" autofocus >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Mail</label>
					<input class="input" type="text" name="mail" autofocus >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>