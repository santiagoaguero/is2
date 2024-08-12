<div id="modalFactura" class="modal modal-fx-slideTop">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div class="box">
            <h2 class="subtitle has-text-centered mb-1"><strong>Factura</strong></h2>
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
            <form id="cliente-form" onsubmit="guardarFactura(event)" action="./php/guardar_factura.php" method="POST" class="is-centered">
                <div class="control">
                    <input class="input is-rounded mb-2" type="text" name="busca_clienteF" id="busca_clienteF" placeholder="Buscar Razón Social o RUC" maxlength="30" autofocus autocomplete="off">
                    <span id="lista_clientesF"></span>
                </div>

                <div class="field mt-5">
                    <span for="cliente_nombre">Nombre:</span>
                    <p class="control has-icons-left">
                        <input class="input" id="cliente_nombreF" name="nombre" autocomplete="off" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-user"></i>
                        </span>
                    </p>
                </div>

                <div class="field">
                    <span for="cliente_ruc">RUC:</span>
                    <p class="control has-icons-left">
                        <input class="input" id="cliente_rucF" name="ruc"  autocomplete="off" required>
                        <span class="icon is-small is-left">
                            <i class="fas fa-check"></i>
                        </span>
                    </p>
                </div>

                <div class="field">
                    <span for="cliente_ruc">Forma de Pago:</span>
                    <div class="select is-rounded">
                        <select name="forma_pago" id="forma_pago">
                            <?php
                            $formas_pago = con();
                            $formas_pago = $formas_pago->query("SELECT * FROM formas_pago");
                            if($formas_pago->rowCount()>0){
                                $formas_pago = $formas_pago->fetchAll();
                                foreach($formas_pago as $forpag){
                                    echo '<option value="'.$forpag['forma_pago_id'].'" >'.$forpag['descripcion'].'</option>';
                                }
                            }
                            $formas_pago=null;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="field is-grouped is-grouped-centered">
                    <button type="submit" class="button is-small is-success is-outlined is-rounded">Imprimir Factura</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    include ("./modal/cliente_nuevo.php")
?>