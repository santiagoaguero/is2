<div id="modalClienteNuevo" class="modal modal-fx-slideTop">
	<div class="modal-background"></div>
	<div class="modal-content">
		<div class="box">
	        <div class="form-rest mb-6 mt-6" data-modal="crear"></div><!--to show notifications -->
            <p class="subtitle has-text-centered mb-2">Crear cliente</p>
            <form action="./php/cliente_guardar.php" method="POST" class="formularioAjax" id="formul" autocomplete="off" >
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <span>Nombre</span>
                            <input class="input" type="text" name="cliente_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ. ]{3,40}" maxlength="40" required autofocus >
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <span>RUC</span>
                            <input class="input" type="text" name="cliente_ruc" pattern="^[0-9a-zA-Z.\-]{4,12}$" maxlength="11" required >
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <span>Teléfono</span>
                            <input class="input" type="text" name="cliente_telefono" pattern="[0-9 -]{6,30}" maxlength="30" >
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <span>Email</span>
                            <input class="input" type="email" name="cliente_email" maxlength="70" >
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="column">
                        <div class="control">
                            <span>Dirección</span>
                            <input class="input" type="text" name="cliente_direccion" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ.- ]{3,255}" maxlength="255" >
                        </div>
                    </div>
                    <div class="column">
                        <div class="control">
                            <span>Contacto</span>
                            <input class="input" type="text" name="cliente_contacto" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ ]{0,40}" maxlength="40" >
                        </div>
                    </div>
                </div>
                <p class="has-text-centered">
                    <button type="submit" class="button is-info is-rounded">Guardar</button>
                    <input type="hidden" name="modal" value="crear">
                </p>
            </form>
        </div>
	</div>
</div>
