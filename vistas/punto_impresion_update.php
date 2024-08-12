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
		<h1 class="title">Punto de Impresión</h1>
		<h2 class="subtitle">Actualizar punto de impresión</h2>
	</div>
<?php 
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["punto_impresion_id_upd"])) ? $_GET["punto_impresion_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_punto_impresion = con();
    $check_punto_impresion = $check_punto_impresion->query("SELECT * FROM puntos_impresion WHERE punto_impresion_id = '$id'");

    if($check_punto_impresion->rowCount()>0){
        $datos=$check_punto_impresion->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/punto_impresion_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="punto_impresion_id" value="<?php echo $datos["punto_impresion_id"];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="nombre" required autofocus value="<?php echo $datos["punimp_nombre"];?>">
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Próximo Número a Facturar</label>
				  	<input class="input" type="text" name="proximo_nro" required autofocus value="<?php echo $datos["punimp_prox_nro_fac"];?>">
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Timbrado</label><br/>
		    	<div class="select is-rounded">
				  	<select name="timbrado" >
                        <?php
							$tim = con();
							$tim = $tim->query("SELECT * FROM timbrados");
							if($tim->rowCount()>0){
								$tim = $tim->fetchAll();
								foreach($tim as $key){
									if($datos["timbrado_id"] == $key['timbrado_id']){
										echo '
										<option value="'.$key['timbrado_id'].'" selected="" >'.$key['numero'].' (Actual)</option>
										';
									} else {
										echo '<option value="'.$key['timbrado_id'].'" >'.$key['numero'].'</option>';
									}
								}
							}
							$tim=null;
                        ?>
				  	</select>
				</div>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Sucursal</label><br/>
					<div class="select is-rounded">
						<select name="sucursal" >
                        <?php
							$suc = con();
							$suc = $suc->query("SELECT * FROM sucursales");
							if($suc->rowCount()>0){
								$suc = $suc->fetchAll();
								foreach($suc as $key){
									if($datos["sucursal_id"] == $key['sucursal_id']){
										echo '
										<option value="'.$key['sucursal_id'].'" selected="" >'.$key['suc_nombre'].' (Actual)</option>
										';
									} else {
										echo '<option value="'.$key['sucursal_id'].'" >'.$key['suc_nombre'].'</option>';
									}
								}
							}
							$suc=null;
                        ?>
						</select>
					</div>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Estado</label><br/>
					<div class="select is-rounded">
						<select name="estado">
							<option value="1" <?php echo ($datos['punimp_estado'] == '1') ? 'selected' : '' ?>>Activo</option>
							<option value="0" <?php echo ($datos['punimp_estado'] == '0') ? 'selected' : '' ?>>Inactivo</option>
						</select>
					</div>
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
    $check_punto_impresion=null;
?>
</div>