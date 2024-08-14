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
    require_once("./php/main.php");
?>

<div class="container pb-6 pt-6">
	<div class="is-fluid mb-2">
		<h1 class="title">Movimiento Bancario</h1>
		<h2 class="subtitle">Nuevo movimiento bancario</h2>
	</div>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/movimiento_banco_guardar.php" method="POST" class="formularioAjax" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nº Comprobante</label>
				  	<input class="input" type="text" name="comprobante" required autofocus >
				</div>
		  	</div>
			<div class="column is-narrow">
				<span>Origen</span><br>
		    	<div class="select is-rounded">
				  	<select name="origen" >
                        <?php
							$ban = con();
							$ban = $ban->query("SELECT * FROM bancos");
							if($ban->rowCount()>0){
								$ban = $ban->fetchAll();
								foreach($ban as $key){
									echo '<option value="'.$key['banco_id'].'" >'.$key['ban_nombre'].'</option>';
								}
							}
							$ban=null;
                        ?>
				  	</select>
				</div>
		  	</div>
			<div class="column is-narrow">
				<span>Destino</span><br>
		    	<div class="select is-rounded">
				  	<select name="destino" >
                        <?php
							$ban = con();
							$ban = $ban->query("SELECT * FROM bancos");
							if($ban->rowCount()>0){
								$ban = $ban->fetchAll();
								foreach($ban as $key){
									echo '<option value="'.$key['banco_id'].'" >'.$key['ban_nombre'].'</option>';
								}
							}
							$ban=null;
                        ?>
				  	</select>
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Fecha</label>
				  	<input class="input" type="date" name="fecha" required autofocus >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Monto Total</label>
				  	<input class="input" type="text" name="total" autofocus >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>