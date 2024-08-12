<?php
    $modulo_buscador = limpiar_cadena($_POST["modulo_buscador"]);

    $modulos=["producto", "categoria", "proveedor", "cliente", "usuario", "factura_producto", "factura", "familia", "timbrado", "compra", "forma_pago", "punto_impresion", "sucursal", "banco", "deposito"];

    if(in_array($modulo_buscador, $modulos)){

        $modulos_url=[
            "producto"=>"product_search",
            "categoria"=>"category_search",
            "proveedor"=>"provee_search",
            "cliente"=>"client_search",
            "usuario"=>"user_search",
            "factura_producto"=>"factur_new",
            "factura"=>"factur_search",
            "familia"=>"family_search",
            "timbrado"=>"timbrado_search",
            "forma_pago"=>"forma_pago_search",
            "punto_impresion"=>"punto_impresion_search",
            "sucursal"=>"sucursal_search",
            "banco"=>"banco_search",
            "deposito"=>"deposito_search",
            "compra"=>"compra_search"
        ];

        $modulos_url=$modulos_url[$modulo_buscador];

        $modulo_buscador="busqueda_".$modulo_buscador;


        //iniciar busqueda
        if(isset($_POST["txt_buscador"])){

            $txt=limpiar_cadena($_POST["txt_buscador"]);
            
            if($txt == ""){
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                    Introduzca un termino de búsqueda.
                </div>
                ';
            } else {
                if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ -]{1,30}", $txt)){//true encontró errores
                    echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrió un error inesperado!</strong><br>
                        Término de búsqueda no coincide con el formato esperado.
                    </div>
                    ';
                } else {
                    $_SESSION[$modulo_buscador]=$txt;
                    
                    echo'
                    <script>
                        window.location="index.php?vista='.$modulos_url.'"
                    </script>
                    ';
                    exit();
                }
            }
        }

        //eliminar busqueda
        if(isset($_POST["eliminar_buscador"])){
            unset($_SESSION[$modulo_buscador]);
            echo'
            <script>
                window.location="index.php?vista='.$modulos_url.'"
            </script>
            ';
            exit();
        }

    } else {
        echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo procesar la operación.
        </div>';
    }