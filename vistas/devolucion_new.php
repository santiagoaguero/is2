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
    <h1 class="title">Devolución por fallos, averías o vto.</h1>
		<h2 class="subtitle">Cargar devolución y dar de baja producto</h2>
	</div>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/devolucion_guardar.php" method="POST" class="formularioAjax" autocomplete="off" >
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
                                    echo '<option value="'.$key['factura_id'].'" >'.$key['factura_numero'].'</option>';
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
                                    echo '<option value="'.$key['producto_id'].'" >'.$key['producto_nombre'].'</option>';
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
                    <input class="input" type="text" name="cantidad" required autofocus >
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Motivo</label>
                    <input class="input" type="text" name="motivo" required autofocus >
                </div>
            </div>
		  	<div class="column">
		    	<div class="control">
					<label>Fecha</label>
				  	<input class="input" type="date" name="fecha" autofocus >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
	</form>
</div>