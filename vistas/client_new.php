
	<form action="./php/cliente_guardar.php" method="POST" class="formularioAjax" id="formul" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombres</label>
				  	<input class="input" type="text" name="cliente_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ. ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Apellidos</label>
				  	<input class="input" type="text" name="cliente_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{0,40}" maxlength="40" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>RUC</label>
				  	<input class="input" type="text" name="cliente_ruc" pattern="[a-zA-Z0-9.-]{4,12}" maxlength="11" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Email</label>
				  	<input class="input" type="email" name="cliente_email" maxlength="70" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Teléfono</label>
				  	<input class="input" type="text" name="cliente_telefono" pattern="[0-9- ]{6,30}" maxlength="30" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Dirección</label>
				  	<input class="input" type="text" name="cliente_direccion" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{3,255}" maxlength="255" >
				</div>
		  	</div>
		</div>
		<!-- <div class="columns"> 
		  	<div class="column">
			  <label>Estado</label><br>
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