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
		<h1 class="title">Gestión de Bienes y Activos</h1>
		<h2 class="subtitle">Actualizar bien o activo</h2>
	</div>
<?php
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["bien_id_upd"])) ? $_GET["bien_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_bien = con();
    $check_bien = $check_bien->query("SELECT * FROM bienes WHERE bien_id = '$id'");

    if($check_bien->rowCount()>0){
        $datos=$check_bien->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/bien_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="bien_id" value="<?php echo $datos["bien_id"];?>" required >

        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombre</label>
                    <input class="input" type="text" name="nombre" required autofocus value="<?php echo $datos["bien_nombre"];?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Cantidad</label>
                    <input class="input" type="number" name="cantidad" required autofocus value="<?php echo $datos["bien_cantidad"];?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Marca</label>
                    <input class="input" type="text" name="marca" autofocus value="<?php echo $datos["bien_marca"];?>">
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Modelo</label>
                    <input class="input" type="text" name="modelo" autofocus value="<?php echo $datos["bien_modelo"];?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Fecha de Adquisición</label>
                    <input class="input" type="date" name="fecha_adquisicion" required autofocus value="<?php echo substr($datos["bien_fecha_adquisicion"],0,10);?>">
                </div>
            </div>
            <div class="column is-narrow">
                <span>Proveedor</span><br>
                <div class="select is-rounded">
                    <select name="proveedor" >
                        <?php
                            $prov = con();
                            $prov = $prov->query("SELECT * FROM proveedor");
                            if ($prov->rowCount() > 0) {
                                $prov = $prov->fetchAll();
                                foreach ($prov as $key) {
                                    if ($datos["prov_id"] == $key['prov_id']) {
                                        echo '<option value="'.$key['prov_id'].'" selected="" >'.$key['prov_nombre'].' (Actual)</option>';
                                    } else {
                                        echo '<option value="'.$key['prov_id'].'" >'.$key['prov_nombre'].'</option>';
                                    }
                                }
                            }
                            $prov = null;
                        ?>
                    </select>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Ubicación</label>
                    <input class="input" type="text" name="ubicacion" autofocus value="<?php echo $datos["bien_ubicacion"];?>">
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
    $check_bien=null;
?>
</div>