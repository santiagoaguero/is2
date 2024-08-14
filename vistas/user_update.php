<?php 
    require_once("./php/main.php");

    $id=(isset($_GET["user_id_upd"])) ? $_GET["user_id_upd"] : 0;
    $id=limpiar_cadena($id);
	// Verificar los permisos del usuario para esta página
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
		<?php
			if($id == $_SESSION["id"]){///usuario que inició sesión
		?> 
				<h1 class="title">Mi Cuenta</h1>
				<h2 class="subtitle">Actualizar datos de cuenta</h2>
		<?php
			} else { 
		?>
				<h1 class="title">Usuarios</h1>
				<h2 class="subtitle">Actualizar usuario</h2>
		<?php
			}
		?> 
	</div>

<?php 
    include("./inc/btn_back.php");

    $check_usuario = con();
    $check_usuario = $check_usuario->query("SELECT * FROM usuario WHERE usuario_id = '$id'");

    if($check_usuario->rowCount()>0){
        $datos=$check_usuario->fetch();
?>
	<div class="form-rest mb-6 mt-6"></div>
	<form action="./php/usuario_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >
		<input type="hidden" value="<?php echo $datos["usuario_id"];?>" name="usuario_id" required >
		
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Nombres</span>
				  	<input class="input" type="text" value="<?php echo $datos["usuario_nombre"];?>" name="usuario_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Apellidos</span>
				  	<input class="input" type="text" value="<?php echo $datos["usuario_apellido"];?>" name="usuario_apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Usuario</span>
				  	<input class="input" type="text" value="<?php echo $datos["usuario_usuario"];?>" name="usuario_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
				</div>
		  	</div>
			<div class="column is-narrow">
				<span>Rol</span><br>
		    	<div class="select is-rounded">
				  	<select name="usuario_rol" >   
                        <?php
                        $rol = con();
                        $rol = $rol->query("SELECT * FROM rol");
                        if($rol->rowCount()>0){
                            $rol = $rol->fetchAll();
                            foreach($rol as $cat){
                                if($datos["rol_id"] == $cat['rol_id']){
                                    echo '
                                    <option value="'.$cat['rol_id'].'" selected="" >'.$cat['rol_nombre'].' (Actual)</option>
                                    ';
                                } else {
                                    echo '<option value="'.$cat['rol_id'].'" >'.$cat['rol_nombre'].'</option>';
                                }
                            }
                        }
                        $categorias=null;
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
                                if($datos["punto_impresion_id"] == $key['punto_impresion_id']){
                                    echo '
                                    	<option value="'.$key['punto_impresion_id'].'" selected="" >'.$key['punimp_nombre'].' (Actual)</option>
                                    ';
                                } else {
                                    echo '<option value="'.$key['punto_impresion_id'].'" >'.$key['punimp_nombre'].'</option>';
                                }
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
				  	<input class="input" type="email" value="<?php echo $datos["usuario_email"];?>" name="usuario_email" maxlength="70" >
				</div>
		  	</div>
		</div>
		<br><br>
		<p class="has-text-centered">
			SI desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave deje los campos vacíos.
		</p>
		<br>
		<div class="columns">
			<div class="column">
		    	<div class="control">
					<span>Clave</span>
				  	<input class="input" type="password" name="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Repetir clave</span>
				  	<input class="input" type="password" name="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
				</div>
		  	</div>
		</div>
		<br><br><br>
		<p class="has-text-centered">
			Para poder actualizar los datos de este usuario por favor ingrese su USUARIO y CLAVE con la que ha iniciado sesión
		</p>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<span>Usuario</span>
				  	<input class="input" type="text" name="administrador_usuario" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<span>Clave</span>
				  	<input class="input" type="password" name="administrador_clave" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required >
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
    $check_usuario=null;
?>	
</div>

