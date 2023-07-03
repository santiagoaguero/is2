<?php 
    require("./inc/session_start.php");
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <?php 
        include("./inc/head.php");
    ?>
</head>
<body>
    <?php
        if(!isset($_GET["vista"]) || $_GET["vista"] == ""){
            $_GET["vista"]= "login";
        }
        if (is_file("./vistas/".$_GET["vista"].".php") && $_GET["vista"] != "login" && $_GET["vista"] != "404"){

            //cierra sesion no autorizada
            if((!isset($_SESSION["id"]) || $_SESSION["id"]=="") || 
            (!isset($_SESSION["usuario"]) || $_SESSION["id"]=="")){
                //redirecciona al login
                include("./vistas/logout.php");
                exit();//para no cargar ningun archivo mas
            }

            include("./inc/navbar.php");
            include("./vistas/".$_GET["vista"].".php");
            include("./inc/script.php");
        } else {
            if($_GET["vista"]=== "login"){
                include("./vistas/login.php");
            } else {
                include("./vistas/404.php");
            }
        }
    ?>
    <footer><a rel="me" href="https://terere.social/@santiago" style="color: white;">,</a></footer>
</body>
</html>