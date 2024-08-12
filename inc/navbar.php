<nav>
    <div>
        <a href="index.php?vista=factur_new">
            <img src="./img/logo.png" width="80" alt="logo"><!-- height-> img-max-height class -->
        </a>
    </div>
    <div>
        <div class="navbar_item_container">
            <?php if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 ): ?>

            <div class="navbar_item">
                <a><i class="fa-solid fa-house"></i>Facturas</a>
                <div>
                    <a href="index.php?vista=factur_list">Lista</a>
                    <a href="index.php?vista=factur_new">Nuevo</a>
                    <a href="index.php?vista=factur_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-users"></i>Clientes</a>
                <div>
                    <a href="index.php?vista=client_list">Lista</a>
                    <a href="index.php?vista=client_new">Nuevo</a>
                    <a href="index.php?vista=client_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-cloud"></i>Productos</a>
                <div>
                    <a href="index.php?vista=product_list">Lista</a>
                    <a href="index.php?vista=product_new">Nuevo</a>
                    <a href="index.php?vista=product_category">Por Categoría</a>
                    <a href="index.php?vista=product_family">Por Familia</a>
                    <a href="index.php?vista=product_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-cart-shopping"></i>Compras</a>
                <div>
                    <a href="index.php?vista=compra_list">Lista</a>
                    <a href="index.php?vista=compra_proveedor">Por Proveedor</a>
                    <a href="index.php?vista=compra_new">Nuevo</a>
                    <a href="index.php?vista=compra_search">Buscar</a>
                    <!-- <a href="index.php?vista=compra_report">Solicitar</a> -->
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-truck"></i>Proveedores</a>
                <div>
                    <a href="index.php?vista=provee_list">Lista</a>
                    <a href="index.php?vista=provee_new">Nuevo</a>
                    <a href="index.php?vista=provee_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-chart-simple"></i>Familia de Productos</a>
                <div>
                    <a href="index.php?vista=family_list">Lista</a>
                    <a href="index.php?vista=family_new">Nuevo</a>
                    <a href="index.php?vista=family_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-layer-group"></i>Categorías de Productos</a>
                <div>
                    <a href="index.php?vista=category_list">Lista</a>
                    <a href="index.php?vista=category_new">Nuevo</a>
                    <a href="index.php?vista=category_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-user"></i>Usuarios</a>
                <div>
                    <a href="index.php?vista=user_list">Lista</a>
                    <a href="index.php?vista=user_new">Nuevo</a>
                    <a href="index.php?vista=user_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-print"></i>Reportes</a>
                <div>
                    <a href="index.php?vista=report_stock_income">Stock a Ingresar <i><sup
                                style="font-size: 12px">[AI]</sup></i></a>
                    <a href="index.php?vista=report_compras_realizadas">Compras Realizadas</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-clipboard"></i>Timbrados</a>
                <div>
                    <a href="index.php?vista=timbrado_list">Lista</a>
                    <a href="index.php?vista=timbrado_new">Nuevo</a>
                    <a href="index.php?vista=timbrado_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-gear"></i>Formas de Pago</a>
                <div>
                    <a href="index.php?vista=forma_pago_list">Lista</a>
                    <a href="index.php?vista=forma_pago_new">Nuevo</a>
                    <a href="index.php?vista=forma_pago_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-clipboard"></i>Puntos de Impresión</a>
                <div>
                    <a href="index.php?vista=punto_impresion_list">Lista</a>
                    <a href="index.php?vista=punto_impresion_new">Nuevo</a>
                    <a href="index.php?vista=punto_impresion_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-shop"></i>Sucursales</a>
                <div>
                    <a href="index.php?vista=sucursal_list">Lista</a>
                    <a href="index.php?vista=sucursal_new">Nuevo</a>
                    <a href="index.php?vista=sucursal_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-building-columns"></i>Entidades Bancarias</a>
                <div>
                    <a href="index.php?vista=banco_list">Lista</a>
                    <a href="index.php?vista=banco_new">Nuevo</a>
                    <a href="index.php?vista=banco_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-warehouse"></i>Depósitos de Productos</a>
                <div>
                    <a href="index.php?vista=deposito_list">Lista</a>
                    <a href="index.php?vista=deposito_new">Nuevo</a>
                    <a href="index.php?vista=deposito_search">Buscar</a>
                </div>
            </div>


            <?php elseif ($_SESSION['rol'] === 3): ?>

            <div class="navbar_item">
                <a><i class="fa-solid fa-house"></i>Facturas</a>
                <div>
                    <a href="index.php?vista=factur_list">Lista</a>
                    <a href="index.php?vista=factur_new">Nuevo</a>
                    <a href="index.php?vista=factur_search">Buscar</a>
                </div>
            </div>

            <div class="navbar_item">
                <a><i class="fa-solid fa-users"></i>Clientes</a>
                <div>
                    <a href="index.php?vista=client_list">Lista</a>
                    <a href="index.php?vista=client_new">Nuevo</a>
                    <a href="index.php?vista=client_search">Buscar</a>
                </div>
            </div>

            <?php endif; ?>

            <div class="navbar_item">
                <a href="index.php?vista=user_update&user_id_upd=<?php echo $_SESSION['id']?>"><i
                        class="fa-solid fa-gears"></i>Mi Cuenta</a>
            </div>
            <div class="navbar_item">
                <a href="index.php?vista=logout"><i class="fa-solid fa-right-from-bracket"></i>Salir</a>
            </div>

        </div>
    </div>
</nav>