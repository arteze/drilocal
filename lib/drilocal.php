<?php

ini_set("display_errors","1");
ini_set("display_startup_errors","1");
error_reporting(E_ALL);

// GETa  Nombre
// dg    declarar_get($array)
// d     depurar()
// r     registrar($o,$registro)
// vp    ver_pila()
// oi    oi($o,$propiedad)
// oa    oa($o,$propiedad,$valor)
// cc    crear_carpeta($url)
// cs    crear_subcarpeta($url)
// ca    crear_archivo($url,$datos)
// ccsne crear_carpeta_si_no_existe($url)
// casne crear_archivo_si_no_existe($url,$datos)
// ed    es_carpeta($url)
// ea    es_archivo($url)
// ecsc  es_carpeta_sin_contenido($url)
// ba    borrar_archivo($url)
// bc    borrar_carpeta($url)
// bcsc  borrar_carpeta_sin_contenido($url)
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
function ver_pila(){
	$o = $GLOBALS["o"];
	echo json_encode($o);
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
		registrar("de0 La ruta '$url' existía.");
		$ecod = 3;
	}
	return $ecod;
}
function crear_subcarpeta($url){
	$ecod = 0;
	$partes = explode("/",$url);
	$t = count($partes);
	$sector = array_slice($partes,0,$t-1);
	$subcarpeta = implode("/", $sector);
	$var_crear_carpeta = crear_carpeta($subcarpeta);
	$ecod = $var_crear_carpeta;
	return $ecod;
}
function crear_archivo($url,$datos){
	$var_crear_subcarpeta = crear_subcarpeta($url);
	if($var_crear_subcarpeta==1||$var_crear_subcarpeta==3){
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
		if(!$bin_es_carpeta){
			borrar_url($url);
			crear_carpeta($url);
			$retorno = 1;
		}else{
			registrar("cas La carpeta '$url' existía.");
			$retorno = 0;
		}
	}else{
		crear_carpeta($url);
		$retorno = 3;
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
function es_carpeta($url){
	$d = 2;
	$se_puede_leer = is_readable($url);
	if($se_puede_leer){
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			registrar("ec La ruta '$url' es una carpeta.");
			$d = 3;
		}else{
			registrar("ec2 La ruta '$url' es un archivo.");
			$d = 2;
		}
	}else{
		registrar("ec0: La ruta '$url' no se puede leer.");
		$d = 0;
	}
	return $d;
}
function es_archivo($url){
	$d = 2;
	$bin_es_carpeta = es_carpeta($url);
	if($bin_es_carpeta==2){
		$d = 1;
	}else{
		if($bin_es_carpeta==3){
			$d = 3;
		}else{
			if($bin_es_carpeta==0){
				$d = 0;
			}
		}
	}
	return $d;
}
function es_carpeta_sin_contenido($url){
	$d = 3;
	$se_puede_leer = is_readable($url);
	if($se_puede_leer){
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			$ls = scandir($url);
			if(count($ls)==2){
				registrar("ecsc La carpeta '$url' está vacía.");
				$d = 1;
			}else{
				registrar("ecsc2 La carpeta '$url' tiene cosas dentro.");
				$d = 2;
			}
		}else{
			registrar("ecsc1 La ruta '$url' es un archivo.");
			$d = 0;
		}
	}else{
		registrar("ecsc0 La carpeta '$url' no se puede leer.");
		$d = 4;
	}	
	return $d;
}
function borrar_archivo($url){
	borrar_carpeta_sin_contenido($url);
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
	$d = 0;
	$bin_url_inicio_barra = substr($url,0,1)== "/";
	if($bin_url_inicio_barra){
		registrar("abca1 Advertencia: La carpeta '$url' es del sistema, por eso no se va a borrar.");
		$d = 2;
	}else{
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			$bin_borrar_carpeta = rmdir($url);
			if($bin_borrar_carpeta){
				registrar("bca3 Carpeta '$url' borrada correctamente.");
				$d = 1;
			}else{
				registrar("bca3 La carpeta '$url' no se pudo borrar.");
				$d = 3;
			}
		}
	}
	return $d;
}
function borrar_carpeta_sin_contenido($url){
	$e = 0;
	$partes = explode("/",$url);
	$c = count($partes);
	$i = 1;
	$max = 0;
	while($i>0){
		$sector = array_slice($partes,0,$i);
		$subcarpeta = implode("/", $sector);
		$d = es_carpeta_sin_contenido($subcarpeta);
		if($d==1){
			$e = borrar_carpeta($subcarpeta);
			if($e==1){
				break;
			}
		}
		if($d==0||$d==4){
			break;
		}
		if($max==0){++$i;}else{--$i;}
		if($i>=$c){$max=1;}
	}
	return $e;
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

	$subcarpeta = json_decode(file_get_contents("subcarpeta.json"))[0];

	$GLOBALS["o"] = (object)array();
	
	$GLOBALS["url"] = $subcarpeta.b;
	$url = $GLOBALS["url"];
	$o = $GLOBALS["o"];
	$o->registro = array();
	if(a=="d"){
		depurar();
	}
	if(a=="r"){
		registrar(b);
		ver_pila();
	}
	if(a=="oa"){
		$b = (object)array();
		oa($b,b,c);
		ver_pila();
	}
	if(a=="cc"){
		crear_carpeta($url);
		ver_pila();
	}
	if(a=="cs"){
		crear_subcarpeta($url);
		ver_pila();
	}
	if(a=="ca"){
		crear_archivo($url,c);
		ver_pila();
	}
	if(a=="ea"){
		es_archivo($url,c);
		ver_pila();
	}
	if(a=="bcsc"){
		borrar_carpeta_sin_contenido($url);
		ver_pila();
	}
}
programa();

?>
