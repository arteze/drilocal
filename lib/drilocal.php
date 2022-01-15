<?php

ini_set("display_errors","1");
ini_set("display_startup_errors","1");
error_reporting(E_ALL);

function depurar(){
	var_dump(debug_backtrace());
}
function oi($o,$propiedad){
	return $o->$propiedad;
}
function declarar_get($array){
	foreach( $array as $variable ){
		define($variable, $_GET[$variable]??null);
	}
}
function es_directorio_sin_contenido($url,$o){
	$d = false;
	$bin_es_carpeta = is_dir($url);
	if($bin_es_carpeta){
		$ls = scandir($url);
		$es_legible = is_readable($url);
		if($es_legible){
			$d = count($ls)==2;
		}
	}else{
		array_push($o->registro,"eda Advertencia: La ruta '$url' es un archivo en vez de una carpeta.");
	}
	return $d;
}

function crear_carpeta($url,$o){
	$bin_existe_url = file_exists($url);
	if(!$bin_existe_url){
		$bin_crear_carpeta = mkdir($url, 0777, true);
		if($bin_crear_carpeta){
			array_push($o->registro,"de3 Carpeta '$url' creada correctamente.");
		}else{
			array_push($o->registro,"ade2 Advertencia: La carpeta '$url' no se pudo crear.");
		}
	}else{
		array_push($o->registro,"ade0 Advertencia: La carpeta '$url' existía.");
	}
}
function crear_subcarpetas($url,$o){
	$partes = explode("/",$url);
	$t = count($partes);
	for($i=1;$i<$t;++$i){
		$sector = array_slice($partes,0,$i);
		$subcarpeta = implode("/", $sector);
		crear_carpeta($subcarpeta,$o);
	}
	return $t;
}
function crear_archivo($url,$datos,$o){
	$var_subcarpetas = crear_subcarpetas($url,$o);
	$var_crear_archivo = file_put_contents($url,$datos);
	if(!$datos){
		$datos = "";
	}
	if($var_crear_archivo===strlen($datos)){
		array_push($o->registro,"co1 Archivo '$url' creado correctamente.");
	}else{
		if($var_crear_archivo===false){
			array_push($o->registro,"aco3 Advertencia: El archivo '$url' no se pudo crear.");
		}else{
			array_push($o->registro,"aco2 Advertencia: El archivo '$url' se pudo crear, pero su contenido es parcial.");
		}
	}
}
function crear_carpeta_si_no_existe($url,$o){
	$retorno = 0;
	$bin_existe_url = file_exists($url);
	if($bin_existe_url){
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			array_push($o->registro,"cas La carpeta '$url' existía.");
			$retorno = 3;
		}else{
			borrar_url($url,$o);
			crear_carpeta($url,$o);
			$retorno = 2;
		}
	}else{
		crear_carpeta($url,$o);
		$retorno = 1;
	}
	return $retorno;
}
function crear_archivo_si_no_existe($url,$datos,$o){
	$retorno = 0;
	$bin_existe_url = file_exists($url);
	if($bin_existe_url){
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			borrar_url($url,$o);
			crear_archivo($url,$datos,$o);
			$retorno = 3;
		}else{
			array_push($o->registro,"dus El archivo '$url' existía.");
			$retorno = 2;
		}
	}else{
		crear_archivo($url,$datos,$o);
		$retorno = 1;
	}
	return $retorno;
}
function crear_bibliomatriz_carpeta_contenido($o){
	crear_archivo($o->url."/info.txt",obtener_info($o->nombre),$o);
	crear_archivo($o->url."/bibma.json",$o->datos,$o);
}
function borrar_archivo($url,$o){
	borrar_directorio_sin_contenido($url,$o);
	$bin_url_inicio_barra = substr($url,0,1)=="/";
	if($bin_url_inicio_barra){
		array_push($o->registro,"abia1 Advertencia: El archivo '$url' es del sistema, por eso no se va a borrar.");
	}else{
		$bin_existe_archivo = file_exists($url);
		if($bin_existe_archivo){
			$bin_borrar_archivo = unlink($url);
			if($bin_borrar_archivo){
				array_push($o->registro,"bia3 Archivo '$url' borrado correctamente.");
			}else{
				array_push($o->registro,"ebia2 El archivo o carpeta '$url' existe, pero no se pudo borrar.");
			}
		}else{
			array_push($o->registro,"abia4 Advertencia: La ruta '$url' no contiene información, por eso no se va a borrar su contenido.");
		}
	}
}
function borrar_carpeta($url,$o){
	$bin_url_inicio_barra = substr($url,0,1)== "/";
	if($bin_url_inicio_barra){
		array_push($o->registro,"abca1 Advertencia: La carpeta '$url' es del sistema, por eso no se va a borrar.");
	}else{
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			$bin_borrar_carpeta = rmdir($url);
			if($bin_borrar_carpeta){
				array_push($o->registro,"bca3 Carpeta '$url' borrada correctamente.");
			}
		}
	}
}
function borrar_directorio_sin_contenido($url,$o){
	$o = (object)array();
	$o->registro = array();
	$partes = explode("/",$url);
	$c = count($partes);
	$i = 1;
	for(;$i<$c;++$i){
		$sector = array_slice($partes,0,$i);
		$subcarpeta = implode("/", $sector);
		$d = es_directorio_sin_contenido($subcarpeta,$o);
		if($d){
			borrar_carpeta($subcarpeta,$o);
		}
	}
	for(;$i>0;--$i){
		$sector = array_slice($partes,0,$i);
		$subcarpeta = implode("/", $sector);
		$d = es_directorio_sin_contenido($subcarpeta,$o);
		if($d){
			borrar_carpeta($subcarpeta,$o);
		}
	}
	return $c;
}

function borrar_url($url,$o){
	$a = __FUNCTION__;
	if($url){
		$rutas = glob( $url ."/*", GLOB_MARK );
		foreach( $rutas as $ruta ){
			if(substr($ruta,-1)=="/"){
				$a($ruta,$o);
			}else{
				borrar_archivo($ruta,$o);
			}
		}
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			borrar_carpeta($url,$o);
		}else{
			borrar_archivo($url,$o);
		}
	}else{
		array_push($o->registro,"abo Advertencia: La ruta '$url' es nula, por eso no se va a intentar borrar ningún archivo");
	}
	return $o;
}

function generar_url($url,$o){
	$asub = file_get_contents("subcarpeta.json");
	$subcarpeta = json_decode($asub)[0];
	$o->unificado = es_unificado($url);
	$o->ext = $o->unificado?"json":"";
	$o->url = "$subcarpeta/$url$o->ext";
}

function programa(){
	declarar_get(array("f","r","n","t"));
	if(f=="c"){
	}
	if(f=="b"){
	}
}
programa();

?>
