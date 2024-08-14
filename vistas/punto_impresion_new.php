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
		<h1 class="title">Punto de Impresión</h1>
		<h2 class="subtitle">Nuevo punto de impresión</h2>
	</div>
	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/punto_impresion_guardar.php" method="POST" class="formularioAjax" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="nombre" required autofocus >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Próximo Número a Facturar</label>
				  	<input class="input" type="text" name="proximo_nro" required autofocus value="0">
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
                                echo '<option value="'.$key['timbrado_id'].'" >'.$key['numero'].'</option>';
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
									echo '<option value="'.$key['sucursal_id'].'" >'.$key['suc_nombre'].'</option>';
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
							<option value="1">Activo</option>
							<option value="0">Inactivo</option>
						</select>
					</div>
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>