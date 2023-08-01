<div id="modalCrearProveedor" class="modal modal-fx-slideTop">
	<div class="modal-background"></div>
	<div class="modal-content">
		<div class="box">
			<p class="subtitle has-text-centered mb-2">Crear Proveedor</p>
            <form action="./php/provee_guardar.php" method="POST" class="formularioAjax" id="formul" autocomplete="off" >
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <span>Nombre</span>
                            <input class="input" type="text" name="provee_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ&. ]{3,40}" maxlength="40" required autofocus >
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <span>RUC</span>
                            <input class="input" type="text" name="provee_ruc" pattern="[a-zA-Z0-9.-]{4,12}" maxlength="11" required >
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <span>Teléfono</span>
                            <input class="input" type="text" name="provee_telefono" pattern="/^[0-9\- ]{6,30}$/" maxlength="30" required>
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <span>Dirección</span>
                            <input class="input" type="text" name="provee_direccion" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ.- ]{3,255}" maxlength="255" >
                        </div>
                    </div>
                </div>
                <!-- <div class="columns"> 
                    <div class="column">
                    <span>Estado</span><br>
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
	</div>
</div>