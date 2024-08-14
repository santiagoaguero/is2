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
		<h2 class="subtitle">Actualizar movimiento bancario</h2>
	</div>
<?php 
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["movimiento_banco_id_upd"])) ? $_GET["movimiento_banco_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_movimiento = con();
    $check_movimiento = $check_movimiento->query("SELECT * FROM movimientos_bancos WHERE movimiento_banco_id = '$id'");

    if($check_movimiento->rowCount()>0){
        $datos=$check_movimiento->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/movimiento_banco_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="movimiento_banco_id" value="<?php echo $datos["movimiento_banco_id"];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nº Comprobante</label>
				  	<input class="input" type="text" name="comprobante" required autofocus value="<?php echo $datos["movban_comprobante_nro"];?>">
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
									if($datos["banco_id"] == $key['banco_id']){
										echo '
											<option value="'.$key['banco_id'].'" selected="" >'.$key['ban_nombre'].' (Actual)</option>
										';
									} else {
										echo '<option value="'.$key['banco_id'].'" >'.$key['ban_nombre'].'</option>';
									}
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
									if($datos["movban_destino"] == $key['banco_id']){
										echo '
											<option value="'.$key['banco_id'].'" selected="" >'.$key['ban_nombre'].' (Actual)</option>
										';
									} else {
										echo '<option value="'.$key['banco_id'].'" >'.$key['ban_nombre'].'</option>';
									}
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
				  	<input class="input" type="date" name="fecha" required autofocus value="<?php echo substr($datos["movban_fecha"],0,10);?>">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Monto Total</label>
				  	<input class="input" type="text" name="total" autofocus value="<?php echo $datos["movban_monto"];?>">
				</div>
		  	</div>
		</div>
		
		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded">Actualizar</button>
		</p>
	</form>
	<?php 
    } else {
        include("./inc/error_alert.php");
    }
    $check_movimiento=null;
?>
</div>