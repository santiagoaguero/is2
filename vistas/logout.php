<?php
session_destroy();


if(headers_sent()){//si ya se enviaron headers se redirecciona con js porque con php da errores. 
    echo '
    <script>
        window.location.href="index.php?vista=login"
    </script>
    ';

} else {
    header("Location: index.php?vista=login");
}