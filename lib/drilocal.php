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
// bcsc  borrar_carpeta_sin_contenido($url)
// bc    borrar_carpeta($url)
// ba    borrar_archivo($url)
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
	header('Content-Type: application/json; charset=utf-8');
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
		if(!$datos){$datos = "";}
		$var_crear_archivo = file_put_contents($url,$datos);
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
	$d = 4;
	$se_puede_leer = is_readable($url);
	if($se_puede_leer){
		$bin_es_carpeta = is_dir($url);
		if($bin_es_carpeta){
			registrar("ec La ruta '$url' es una carpeta.");
			$d = 1;
		}else{
			registrar("ec2 La ruta '$url' es un archivo.");
			$d = 3;
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
	if($bin_es_carpeta==3){$d = 1;}
	if($bin_es_carpeta==0){$d = 5;}
	if($bin_es_carpeta==1){$d = 6;}
	if($bin_es_carpeta==4){$d = 7;}
	if($d==2){$d=$bin_es_carpeta;}
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
function borrar_carpeta_sin_contenido($url){
	$e = 2;
	$partes = explode("/",$url);
	$c = count($partes);
	$i = 2;
	$max = 0;
	while($i>0){
		$sector = array_slice($partes,0,$i);
		$subcarpeta = implode("/", $sector);
		$d = es_carpeta_sin_contenido($subcarpeta);
		if($d==1){
			registrar($subcarpeta);
			$e = rmdir($subcarpeta);
			if($e){
				registrar("bcsc Carpeta '$subcarpeta' borrada correctamente.");
			}else{
				registrar("abcsc Advertencia: La carpeta '$subcarpeta' no se pudo borrar.");
			}
		}
		if($d==0){$e=3;break;} // La ruta es un archivo.
		if($d==4){$e=4;break;} // La carpeta no se puede leer.
		if($max==0){++$i;}else{--$i;}
		if($i>=$c){$max=1;}
		if($i<2){break;}
	}
	return $e;
}
function borrar_carpeta($url){
	$d = 0;
	$bin_url_inicio_barra = substr($url,0,1)== "/";
	if($bin_url_inicio_barra){
		registrar("abca1 Advertencia: La carpeta '$url' es del sistema, por eso no se va a borrar.");
		$d = 2;
	}else{
		$bin_es_carpeta = es_carpeta($url);
		if($bin_es_carpeta==1){
			$e = borrar_carpeta_sin_contenido($url);
			if($e==2){$d = 4;} // No ocurre nada en bcsc.
			if($e==0){$d = 3;} // Carpeta borrada correctamente.
			if($e==1){$d = 4;} // La carpeta no se pudo borrar.
			if($e==3){$d = 5;} // La ruta es un archivo.
			if($e==4){$d = 6;} // La carpeta no se puede leer.
		}
	}
	return $d;
}
function borrar_archivo($url){
	$d = 0;
	borrar_carpeta_sin_contenido($url);
	$bin_url_inicio_barra = substr($url,0,1)=="/";
	if($bin_url_inicio_barra){
		registrar("abia1 Advertencia: El archivo '$url' es del sistema, por eso no se va a borrar.");
		$d = 5;
	}else{
		$bin_se_puede_leer = is_readable($url);
		$bin_se_puede_escribir = is_writable($url);
		if($bin_se_puede_leer && $bin_se_puede_escribir){
			$bin_borrar_archivo = unlink($url);
			if($bin_borrar_archivo){
				registrar("bia Archivo '$url' borrado correctamente.");
				$d = 1;
			}else{
				registrar("ebia0 Error: El archivo o carpeta '$url' existe, pero no se pudo borrar.");
				$d = 2;
			}
		}else{
			if(!$bin_se_puede_leer){
				registrar("abia1 Advertencia: La ruta '$url' no tiene permisos de lectura.");
				$d = 3;
			}
			if(!$bin_se_puede_escribir){
				registrar("abia2 Advertencia: La ruta '$url' no tiene permisos de escritura.");
				$d = 3;
			}
		}
	}
	return $d;
}
function borrar_url($url){
	$d = 0;
	$a = __FUNCTION__;
	if($url){
		$rutas = glob( $url ."/*", GLOB_MARK );
		foreach( $rutas as $ruta ){
			if(substr($ruta,-1)=="/"){
				 // La variable $a contiene una función recursiva
				$a($ruta);
			}else{
				$e = borrar_archivo($ruta);
				if($e==1){$d=1;}
			}
		}
		$bin_es_carpeta = es_carpeta($url);
		$bin_es_archivo = es_archivo($url);
		if($bin_es_carpeta==1){
			$e = borrar_carpeta($url);
			if($e==1){$d=2;}
		}
		if($bin_es_archivo==1){
			$e = borrar_archivo($url);
			if($e==1){$d=3;}
		}
	}else{
		registrar("abo Advertencia: La ruta '$url' es nula, por eso no se va a intentar borrar ningún archivo");
		$d = 4;
	}
	return $d;
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
	if(a=="bu"){
		borrar_url($url);
		ver_pila();
	}
}
programa();

?>
