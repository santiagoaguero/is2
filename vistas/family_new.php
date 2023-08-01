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
<div class="container is-fluid mb-6">
	<h1 class="title">Familia</h1>
	<h2 class="subtitle">Nueva familia</h2>
</div>

<div class="container pb-6 pt-6">

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/familia_guardar.php" method="POST" class="formularioAjax" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="familia_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}" maxlength="50" required autofocus >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>