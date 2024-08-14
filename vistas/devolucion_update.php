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
		<h1 class="title">Devolución por fallos, averías o vto.</h1>
		<h2 class="subtitle">Actualizar devolución</h2>
	</div>
<?php
    include("./inc/btn_back.php");

	require_once("./php/main.php");

    $id=(isset($_GET["devolucion_id_upd"])) ? $_GET["devolucion_id_upd"] : 0;
    $id=limpiar_cadena($id);

    $check_devolucion = con();
    $check_devolucion = $check_devolucion->query("SELECT * FROM devoluciones WHERE devolucion_id = '$id'");

    if($check_devolucion->rowCount()>0){
        $datos=$check_devolucion->fetch();
?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/devolucion_actualizar.php" method="POST" class="formularioAjax" autocomplete="off" >

		<input type="hidden" name="devolucion_id" value="<?php echo $datos["devolucion_id"];?>" required >

        <div class="columns">
            <div class="column is-narrow">
                <span>Factura (anulada)</span><br>
                <div class="select is-rounded">
                    <select name="factura" >
                        <?php
                            $fac = con();
                            $fac = $fac->query("SELECT * FROM facturas WHERE factura_estado = 0 ORDER BY factura_id DESC");
                            if($fac->rowCount()>0){
                                $fac = $fac->fetchAll();
                                foreach($fac as $key){
                                    if($datos["factura_id"] == $key['factura_id']){
                                        echo '<option value="'.$key['factura_id'].'" selected="" >'.$key['factura_numero'].' (Actual)</option>';
                                    } else {
                                        echo '<option value="'.$key['factura_id'].'" >'.$key['factura_numero'].'</option>';
                                    }
                                }
                            }
                            $fac=null;
                        ?>
                    </select>
                </div>
            </div>
            <div class="column is-narrow">
                <span>Producto</span><br>
                <div class="select is-rounded">
                    <select name="producto" >
                        <option value="" selected="" >Seleccione una opción</option>
                        <?php
                            $prd = con();
                            $prd = $prd->query("SELECT * FROM producto ORDER BY producto_nombre ASC");
                            if($prd->rowCount()>0){
                                $prd = $prd->fetchAll();
                                foreach($prd as $key){
                                    if($datos["producto_id"] == $key['producto_id']){
                                        echo '<option value="'.$key['producto_id'].'" selected="" >'.$key['producto_nombre'].' (Actual)</option>';
                                    } else {
                                        echo '<option value="'.$key['producto_id'].'" >'.$key['producto_nombre'].'</option>';
                                    }
                                }
                            }
                            $prd=null;
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Cantidad</label>
                    <input class="input" type="text" name="cantidad" required autofocus value="<?php echo $datos["dev_cantidad"];?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Motivo</label>
                    <input class="input" type="text" name="motivo" required autofocus value="<?php echo $datos["dev_motivo"];?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Fecha</label>
                    <input class="input" type="date" name="fecha" autofocus value="<?php echo substr($datos["dev_fecha"],0,10);?>">
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
    $check_devolucion=null;
?>
</div>