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
		<h1 class="title">Timbrado</h1>
		<h2 class="subtitle">Nuevo timbrado</h2>
	</div>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/timbrado_guardar.php" method="POST" class="formularioAjax" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Número</label>
				  	<input class="input" type="text" name="timbrado_numero" required autofocus >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Fecha de Emisión</label>
				  	<input class="input" type="date" name="timbrado_fecha_emision" required autofocus >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Fecha de Vencimiento</label>
				  	<input class="input" type="date" name="timbrado_fecha_vencimiento" required autofocus >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Estado</label><br/>
					<div class="select is-rounded">
						<select name="timbrado_estado">
							<option value="1">Activo</option>
							<option value="0">Inactivo</option>
						</select>
					</div>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Descripción</label>
				  	<input class="input" type="text" name="timbrado_descripcion" autofocus placeholder="(Opcional)">
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>