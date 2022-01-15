<?php

ini_set("display_errors","1");
ini_set("display_startup_errors","1");
error_reporting(E_ALL);

// GETa  Nombre
// dg    declarar_get($array)
// d     depurar()
// r     registrar($o,$registro)
// oi    oi($o,$propiedad)
// oa    oa($o,$propiedad,$valor)
// cc    crear_carpeta($url)
// cs    crear_subcarpetas($url)
// ca    crear_archivo($url,$datos)
// ccsne crear_carpeta_si_no_existe($url)
// casne crear_archivo_si_no_existe($url,$datos)
// edsc  es_directorio_sin_contenido($url)
// ba    borrar_archivo($url)
// bc    borrar_carpeta($url)
// bdsc  borrar_directorio_sin_contenido($url)
// bu    borrar_url($url)

// programa()

function declarar_get($array){
	foreach( $array as $variable ){
		define($variable, $_GET[$variable]??null);
	}
}
function depurar(){
	var_dump(debug_backtrace());
}
function registrar($registro){
	$o = $GLOBALS["o"];
	array_push($o->registro,$registro);
	return $o;
}
function oi($o,$propiedad){
	return $o->$propiedad;
}
function oa($o,$propiedad,$valor){
	$o->$propiedad = $valor;
	return $o->$propiedad;
}
function crear_carpeta($url){
	$ecod = 0;
	$bin_existe_url = file_exists($url);
	if(!$bin_existe_url){
		$bin_crear_carpeta = mkdir($url, 0777, true);
		if($bin_crear_carpeta){
			registrar("de Carpeta '$url' creada correctamente.");
			$ecod = 1;
			
		}else{
			registrar("ade2 Advertencia: La carpeta '$url' no se pudo crear.");
			$ecod = 2;
		}
	}else{
		registrar("ade0 Advertencia: La carpeta '$url' existía.");
		$ecod = 3;
	}
	return $ecod;
}
function crear_archivo($url,$datos){
	$var_carpeta = crear_carpeta($url);
	if($var_carpeta==1){
		$var_crear_archivo = file_put_contents($url,$datos);
		if(!$datos){
			$datos = "";
		}
		if($var_crear_archivo===strlen($datos)){
			registrar("co Archivo '$url' creado correctamente.");
		}else{
			if($var_crear_archivo===false){
				registrar("aco3 Advertencia: El archivo '$url' no se pudo crear.");
			}else{
				registrar("aco2 Advertencia: El archivo '$url' se pudo crear, pero su contenido es parcial.");
			}
		}
	}else{
		registrar("aco0 Advertencia: No se pudo crear la carpeta '$url', donde va el archivo.");
	}
}
function crear_carpeta_si_no_existe($url){
	$retorno = 0;
	$bin_existe_url = file_exists($url);
	if($bin_existe_url){
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			registrar("cas La carpeta '$url' existía.");
			$retorno = 3;
		}else{
			borrar_url($url);
			crear_carpeta($url);
			$retorno = 2;
		}
	}else{
		crear_carpeta($url);
		$retorno = 1;
	}
	return $retorno;
}
function crear_archivo_si_no_existe($url,$datos){
	$retorno = 0;
	$bin_existe_url = file_exists($url);
	if($bin_existe_url){
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			borrar_url($url);
			crear_archivo($url,$datos);
			$retorno = 3;
		}else{
			registrar("dus El archivo '$url' existía.");
			$retorno = 2;
		}
	}else{
		crear_archivo($url,$datos);
		$retorno = 1;
	}
	return $retorno;
}
function es_directorio_sin_contenido($url){
	$d = false;
	$bin_es_carpeta = is_dir($url);
	if($bin_es_carpeta){
		$ls = scandir($url);
		$es_legible = is_readable($url);
		if($es_legible){
			$d = count($ls)==2;
			registrar("eda3 La ruta '$url' existe y es una carpeta.");
		}
	}else{
		registrar("eda0 Advertencia: La ruta '$url' es un archivo en vez de una carpeta.");
	}
	return $d;
}
function borrar_archivo($url){
	borrar_directorio_sin_contenido($url);
	$bin_url_inicio_barra = substr($url,0,1)=="/";
	if($bin_url_inicio_barra){
		registrar("abia1 Advertencia: El archivo '$url' es del sistema, por eso no se va a borrar.");
	}else{
		$bin_existe_archivo = file_exists($url);
		if($bin_existe_archivo){
			$bin_borrar_archivo = unlink($url);
			if($bin_borrar_archivo){
				registrar("bia3 Archivo '$url' borrado correctamente.");
			}else{
				registrar("ebia2 El archivo o carpeta '$url' existe, pero no se pudo borrar.");
			}
		}else{
			registrar("abia4 Advertencia: La ruta '$url' no contiene información, por eso no se va a borrar su contenido.");
		}
	}
}
function borrar_carpeta($url){
	$bin_url_inicio_barra = substr($url,0,1)== "/";
	if($bin_url_inicio_barra){
		registrar("abca1 Advertencia: La carpeta '$url' es del sistema, por eso no se va a borrar.");
	}else{
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			$bin_borrar_carpeta = rmdir($url);
			if($bin_borrar_carpeta){
				registrar("bca3 Carpeta '$url' borrada correctamente.");
			}
		}
	}
}
function borrar_directorio_sin_contenido($url){
	$partes = explode("/",$url);
	$c = count($partes);
	$i = 1;
	for(;$i<$c;++$i){
		$sector = array_slice($partes,0,$i);
		$subcarpeta = implode("/", $sector);
		$d = es_directorio_sin_contenido($subcarpeta);
		if($d){
			borrar_carpeta($subcarpeta);
		}
	}
	for(;$i>0;--$i){
		$sector = array_slice($partes,0,$i);
		$subcarpeta = implode("/", $sector);
		$d = es_directorio_sin_contenido($subcarpeta);
		if($d){
			borrar_carpeta($subcarpeta);
		}
	}
	return $c;
}
function borrar_url($url){
	$a = __FUNCTION__;
	if($url){
		$rutas = glob( $url ."/*", GLOB_MARK );
		foreach( $rutas as $ruta ){
			if(substr($ruta,-1)=="/"){
				$a($ruta);
			}else{
				borrar_archivo($ruta);
			}
		}
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			borrar_carpeta($url);
		}else{
			borrar_archivo($url);
		}
	}else{
		registrar("abo Advertencia: La ruta '$url' es nula, por eso no se va a intentar borrar ningún archivo");
	}
	return $o;
}

function programa(){
	declarar_get(array("a","b","c"));

	$GLOBALS["o"] = (object)array();
	$GLOBALS["url"] = "../guardado/".b;
	$url = $GLOBALS["url"];
	$o = $GLOBALS["o"];
	$o->registro = array();
	if(a=="d"){
		depurar();
	}
	if(a=="r"){
		registrar(b);
		var_dump($o);
	}
	if(a=="oa"){
		$b = (object)array();
		oa($b,b,c);
		var_dump($b);
	}
	if(a=="cc"){
		crear_carpeta($url);
		var_dump($o);
	}
	if(a=="edsc"){
		es_directorio_sin_contenido($url);
		var_dump($o);
	}
}
programa();

?>
