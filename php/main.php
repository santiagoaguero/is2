<?php
//conexion a la bd
function con (){
    $pdo = new PDO("mysql: host=localhost;dbname=inventario", "root", "");//dbtype: host,dbname,user,pas
    return $pdo;
}


//verificar datos
function verificar_datos($filtro, $cadena){
    if(preg_match("/^".$filtro."$/", $cadena)){
        return false;
    } else {
        return true;
    }
}


//limpiar cadenas de texto y evitar inyecciones no deseadas-xss
function limpiar_cadena($cadena){

    //busca y elimina posibles scripts de inyecciones
	$cadena=str_ireplace("<script>", "", $cadena);
	$cadena=str_ireplace("</script>", "", $cadena);
	$cadena=str_ireplace("<script src", "", $cadena);
	$cadena=str_ireplace("<script type=", "", $cadena);
	$cadena=str_ireplace("SELECT * FROM", "", $cadena);
	$cadena=str_ireplace("DELETE FROM", "", $cadena);
	$cadena=str_ireplace("INSERT INTO", "", $cadena);
	$cadena=str_ireplace("DROP TABLE", "", $cadena);
	$cadena=str_ireplace("DROP DATABASE", "", $cadena);
	$cadena=str_ireplace("TRUNCATE TABLE", "", $cadena);
	$cadena=str_ireplace("SHOW TABLES;", "", $cadena);
	$cadena=str_ireplace("SHOW DATABASES;", "", $cadena);
	$cadena=str_ireplace("<?php", "", $cadena);
	$cadena=str_ireplace("?>", "", $cadena);
	$cadena=str_ireplace("--", "", $cadena);
	$cadena=str_ireplace("^", "", $cadena);
	$cadena=str_ireplace("<", "", $cadena);
	$cadena=str_ireplace("[", "", $cadena);
	$cadena=str_ireplace("]", "", $cadena);
	$cadena=str_ireplace("==", "", $cadena);
	$cadena=str_ireplace(";", "", $cadena);
	$cadena=str_ireplace("::", "", $cadena);

    $cadena = trim($cadena);//elimina espacios en blanco al inicio y/o fin
    $cadena = stripslashes($cadena);//elimina barras

	return $cadena;
	}


//renombrar fotos
function renombrar_fotos($nombre){
    $nombre = str_ireplace(" ", "_", $nombre);
    $nombre = str_ireplace("/", "_", $nombre);
    $nombre = str_ireplace("#", "_", $nombre);
    $nombre = str_ireplace("-", "_", $nombre);
    $nombre = str_ireplace("$", "_", $nombre);
    $nombre = str_ireplace(".", "_", $nombre);
    $nombre = str_ireplace(",", "_", $nombre);
    $nombre = $nombre."_".rand(0,1000);

    return $nombre;
}


//paginacion
function paginador($pagina, $Npaginas, $url, $botones){
	$tabla='<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

	if($pagina<=1){//primera pagina
		$tabla.='
		<a class="pagination-previous is-disabled" disabled >Anterior</a>
		<ul class="pagination-list">';
	}else{
		$tabla.='
		<a class="pagination-previous" href="'.$url.($pagina-1).'" >Anterior</a>
		<ul class="pagination-list">
			<li><a class="pagination-link" href="'.$url.'1">1</a></li>
			<li><span class="pagination-ellipsis">&hellip;</span></li>
		';
	}

	$ci=0;//contador de paginas
	for($i=$pagina; $i<=$Npaginas; $i++){
		if($ci>=$botones){
			break;
		}
		if($pagina==$i){
			$tabla.='<li><a class="pagination-link is-current" href="'.$url.$i.'">'.$i.'</a></li>';
		}else{
			$tabla.='<li><a class="pagination-link" href="'.$url.$i.'">'.$i.'</a></li>';
		}
		$ci++;
	}

	if($pagina==$Npaginas){//ultima pagina
		$tabla.='
		</ul>
		<a class="pagination-next is-disabled" disabled >Siguiente</a>
		';
	}else{
		$tabla.='
			<li><span class="pagination-ellipsis">&hellip;</span></li>
			<li><a class="pagination-link" href="'.$url.$Npaginas.'">'.$Npaginas.'</a></li>
		</ul>
		<a class="pagination-next" href="'.$url.($pagina+1).'" >Siguiente</a>
		';
	}

	$tabla.='</nav>';
	return $tabla;
}

function getFecha(){
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

 	return date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;

}