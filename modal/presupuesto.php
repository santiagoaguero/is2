<div id="modalPresupuesto" class="modal modal-fx-slideTop">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div class="box">
            <h2 class="subtitle has-text-centered mb-1"><strong>Presupuesto</strong></h2>
            <h2 class=" subtitle has-text-centered">Datos del Cliente</h2>
            <div class="buttons is-centered has-addons">
                <p class="control">
                    <button class="button modal-button is-info is-rounded is-outlined is-small" type="button" data-target="modalClienteNuevo"><!-- button type button doesn't send form -->
                        <span class="icon is-small">
                            <i class="fas fa-add"></i>
                        </span>
                        <span>Nuevo Cliente</span>
                    </button>
                </p>
            </div>
            <form id="cliente-form" onsubmit="guardarPresupuesto(event)" action="./php/guardar_presupuesto.php" method="POST" class="is-centered">
                <div class="control">
                    <input class="input is-rounded mb-2" type="text" name="busca_clienteP" id="busca_clienteP" placeholder="Razón Social o RUC" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" autofocus autocomplete="off">
                    <span id="lista_clientesP"></span>
                </div>

                <div class="field mt-5">
                <span for="cliente_nombre">Nombre:</span>
                    <p class="control has-icons-left">
                        <input class="input" id="cliente_nombreP" name="nombre" autocomplete="off" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-user"></i>
                        </span>
                    </p>
                </div>
                <div class="field">
                <span for="cliente_ruc">RUC:</span>
                    <p class="control has-icons-left">
                        <input class="input" id="cliente_rucP" name="ruc"  autocomplete="off" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-check"></i>
                        </span>
                    </p>
                </div>
                <div class="field is-grouped is-grouped-centered">
                    <button type="submit" class="button is-small is-success is-outlined is-rounded">Imprimir Presupuesto</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    include ("./modal/cliente_nuevo.php")
?>