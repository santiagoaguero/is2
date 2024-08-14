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
		<h1 class="title">Clientes</h1>
		<h2 class="subtitle">Nuevo cliente</h2>
	</div>

	<div class="form-rest mb-6 mt-6"></div><!--to show notifications -->

	<form action="./php/cliente_guardar.php" method="POST" class="formularioAjax" id="formul" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Nombre</span>
				  	<input class="input" type="text" name="cliente_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ. ]{3,40}" maxlength="40" required autofocus >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<span>RUC</span>
				  	<input class="input" type="text" name="cliente_ruc" pattern="^[0-9a-zA-Z.\-]{4,12}$" maxlength="11" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
		    	<div class="control">
					<span>Teléfono</span>
				  	<input class="input" type="text" name="cliente_telefono" pattern="[0-9 -]{6,30}" maxlength="30" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Email</span>
				  	<input class="input" type="email" name="cliente_email" maxlength="70" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Dirección</span>
				  	<input class="input" type="text" name="cliente_direccion" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ.- ]{3,255}" maxlength="255" >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<span>Contacto</span>
				  	<input class="input" type="text" name="cliente_contacto" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{0,40}" maxlength="40" >
				</div>
		  	</div>
		</div>
		<!-- <div class="columns"> 
		  	<div class="column">
			  <span>Estado</span><br>
					<div class="select">
						<select name="cliente_estado" required>
							<option value="" selected="">-- Selecciona estado --</option>
							<option value="1">Activo</option>
							<option value="0">Inactivo</option>
				  		</select>
					</div>
		  	</div>
		</div>-->
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>