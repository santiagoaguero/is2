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
		<h1 class="title">Usuarios</h1>
		<h2 class="subtitle">Nuevo usuario</h2>
	</div>
<?php 
    require_once("./php/main.php");
?>

	 <div class="form-rest mb-6 mt-6"></div><!--to show notifications -->

	<form action="./php/usuario_guardar.php" method="POST" class="formularioAjax" id="formul" autocomplete="off" >
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Nombres</span>
				  	<input class="input" type="text" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{3,40}" maxlength="40" required autofocus >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Apellidos</span>
				  	<input class="input" type="text" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Usuario</span>
				  	<input class="input" type="text" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
				</div>
		  	</div>
			<div class="column is-narrow">
				<span>Rol</span><br>
		    	<div class="select is-rounded">
				  	<select name="usuario_rol" >
				    	<option value="" selected="" >Seleccione una opción</option>
                        <?php
                        $rol = con();
                        $rol = $rol->query("SELECT * FROM rol");
                        if($rol->rowCount()>0){
                            $rol = $rol->fetchAll();
                            foreach($rol as $cat){
                                echo '<option value="'.$cat['rol_id'].'" >'.$cat['rol_nombre'].'</option>';
                            }
                        }
                        $rol=null;
                        ?>
				  	</select>
				</div>
		  	</div>
			<div class="column is-narrow">
				<span>Punto de Impresión</span><br>
		    	<div class="select is-rounded">
				  	<select name="punto_impresion" >
                        <?php
							$punimp = con();
							$punimp = $punimp->query("SELECT * FROM puntos_impresion");
							if($punimp->rowCount()>0){
								$punimp = $punimp->fetchAll();
								foreach($punimp as $key){
									echo '<option value="'.$key['punto_impresion_id'].'" >'.$key['punimp_nombre'].'</option>';
								}
							}
							$punimp=null;
                        ?>
				  	</select>
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Email</span>
				  	<input class="input" type="email" name="usuario_email" maxlength="70" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Clave</span>
				  	<input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Repetir clave</span>
				  	<input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>