Object.prototype.i = function i(i){
	return this[i]
}
var g = {
	// array_push(a,b)
	// substr(s,c)
	// json_encode(x)
	// strlen(datos)
	// implode(delimitador,array)
	// explode(delimitador,url)
	// count(array)
	// array_slice(array,i,j)
	// json_decode(s)
	array_push: function array_push(a,b){
		if( b.es_constructor(a,Array) ){
			a.push(b)
		}else{
			b.apilar(`${arguments.calle.name} Error: El valor '${a}' no es un array.`)
		}
		return a
	}
	, substr: function substr(s,i,f){
		var d = null
		if( b.es_constructor(s,String) ){
			d = s.slice(i,f)
		}else{
			b.apilar(`esu Error: El parámetro '${s}' no es un string.`)
		}
		return d
	}
	, json_encode: function json_encode(x){
		return JSON.stringify(x)
	}
	, strlen: function strlen(datos){
		return datos && datos.length || -1
	}
	, implode: function implode(delimitador,array){
		return array && array.join(delimitador) || ""
	}
	, explode: function explode(delimitador,url){
		return url && url.split(delimitador) || []
	}
	, count: function count(array){
		return array && array.length || -1
	}
	, array_slice: function array_slice(array,i,j){
		return array && array.slice(i,j) || array
	}
	, json_decode: function json_decode(s){
		var d = null
		try{
			d = JSON.parse(s)
		}catch(e){
			b.apilar(`jos Error: El JSON. '${s}' no se pudo analizar.`)
		}
		return d
	}
}

var b = {
	// oi(o,i)
	// agregar_favicon()
	// agregar_nombre_documento(nombre)
	// tiene_php()
	// es_constructor(a,c)
	// apilar(pila)
	// desapilar()
	// debug_backtrace()
	// obtener_opciones()
	oi: function oi(o,i){
		return o[i]
	}
	, agregar_favicon: function agregar_favicon(){
		var link = document.createElement("link")
		link.rel = "icon"
		link.href = `lib/favicon.png?${Date.now()}`
		document.head.appendChild(link)
	}
	, agregar_nombre_documento: function agregar_nombre_documento(nombre){
		var nombre_documento = document.createElement("title")
		nombre_documento.innerHTML = nombre
		document.head.appendChild(nombre_documento)
	}
	, tiene_php: function tiene_php(rel){
		return fetch("lib/tiene_php.php")
			.then(x=>x.text())
			.then(x=>{
				console.log(x)
			})
	}
	, es_constructor: function es_constructor(a,c){
		return a && a.constructor==c
	}
	, desapilar: function desapilar(){
		var pre = document.querySelector("pre")
		pre.innerHTML = "\n"
	}
	, apilar: function apilar(pila){
		var log_final = null, div = null, ndivs = null, j = null
		var pre = document.querySelector("pre")
		var ite = [...pre.querySelectorAll(".n1")].slice(-1)[0]
		var d2 = [...pre.querySelectorAll("div")].slice(-1)[0]
		var i = ite && +ite.innerHTML || 0
		if(window.log==null){
			window.log = [[]]
		}
		ndivs = window.log.length
		j = ndivs
		if(/^(\s|\t)+$/.test(pre.innerHTML)){
			pre.innerHTML = ""
		}
		if(pila===""){
			window.log.push([])
			pila = [pila]
			div = document.createElement("div")
			pre.appendChild(div)
		}else{
			if(ite){
				div = d2
			}else{
				div = document.createElement("div")
				pre.appendChild(div)
			}
		}
		if( b.es_constructor(pila,String) ){
			pila = [pila]
		}
		log_final = window.log.slice(-1)[0]
		log_final.push(...pila)
		if(ndivs%2){
			div.className = "d0"
			
		}else{
			div.className = "d1"
		}
		pila.filter(x=>x).map(x=>{
			var a = document.createElement("a")
			var b = document.createElement("a")
			var c = document.createElement("a")
			a.className = "n1"
			b.className = "n2"
			a.innerHTML = `${++i}`
			b.innerHTML = ` ${j} `
			c.innerHTML = x + "\n"
			div.appendChild(a)
			div.appendChild(b)
			div.appendChild(c)
		})
		return pre
	}
	, debug_backtrace: function debug_backtrace(){
		console.trace()
	}
	, obtener_opciones: function obtener_opciones(){
		
	}
}

var ls = {
	leer_url: function(url){ // Por hacer
		console.log("leer_url",url)
	}
	, glob: function glob(url,bandera){ // Por hacer
		var todo = []
		console.log("glob_mark",url,bandera)
		if(bandera=="glob_mark"){
			;
		}
		return todo;
	}
	, is_dir: function is_dir(url){ // Por hacer
		var d = false
		return d
	}
	, unlink: function unlink(url){ // Por hacer
	}
	, mkdir: function mkdir(url,permisos,recursivo){ // Por hacer
	}
	, rmdir: function rmdir(url){ // Por hacer
		
	}
	, file_get_contents: function file_get_contents(url,retrollamada){ // Por hacer
		console.log("file_get_contents",url,retrollamada)
		fetch(url).then(x=>x.text()).then(x=>retrollamada(x))
	}
	, file_put_contents: function file_put_contents(url,datos){ // Por hacer
		
	}
	, ls_hacia_matriz: function ls_hacia_matriz(){
		var a = []
		Object.keys(localStorage).map(x=>{
			var v = localStorage[x]
			try{
				a.push([x,JSON.parse(v)])
			}catch(e){
				a.push([x,v])
			}
		})
		return a
	}
	, dir: function dir(o){
		var o = o && o || {registro: []}
		var d = [], pila = ls.ls_hacia_matriz()
		while(pila.length>0){
			var x = pila[0], c = x && x[0], v = x && x[1]
			if(x && x.length==2 && b.es_constructor(c,String) ){
				d.push(c)
				if( b.es_constructor(v,Array) ){
					if(v.length==2 && b.es_constructor(v[0],String) ){
						v = [v]
					}
					v.map(x=>{
						x[0] = `${c}/${x[0]}`
						pila.push(x)
					})
				}
			}
			pila.shift()
		}
		return d
	}
	, file_exists: function file_exists(url){
		var d = false
		var partes = null
		var puntero = localStorage
		if( b.es_constructor(puntero,String) ){
			partes =  url.split("/") 
		}else{
			partes = []
			console.error(url)
			console.trace()
		}
		for(var i=0;i<partes.length;++i){
			var v = null
			var a = partes[i]
			if(i==0){
				v = puntero[a]
				puntero = v && JSON.parse(v) || null
			}else{
				if( b.es_constructor(puntero,Array) ){
					if( b.es_constructor(puntero[0],String) ){
						puntero = puntero[0]==a?puntero[1]:null
					}else{
						puntero = puntero.filter(x=>x[0]==a)[0]
						puntero = puntero && puntero[1]
					}
				}else{
					puntero = null
				}
			}
			if(!puntero){break}
		}
		return !!puntero
	}
	, borrar_archivo: function borrar_archivo(url){
		var bin_url_inicio_barra = ls.substr(url,0,1)=="/"
		if(bin_url_inicio_barra){
			b.apilar(`abia1 Advertencia: El archivo '${url}' es del sistema, por eso no se va a borrar.`)
		}else{
			var bin_existe_archivo = ls.file_exists(url)
			if(bin_existe_archivo){
				var bin_borrar_archivo = ls.unlink(url)
				if(bin_borrar_archivo){
					b.apilar(`bia3 Archivo '${url}' borrado correctamente.`)
				}else{
					b.apilar(`ebia5 Error: El archivo o carpeta '${url}' existe, pero no se pudo borrar.`)
				}
			}else{
				b.apilar(`abia4 Advertencia: La ruta '${url}' no contiene información, por eso no se va a borrar su contenido.`)
			}
		}
	}
	, borrar_carpeta: function borrar_carpeta(url){
		var bin_url_inicio_barra = ls.substr(url,0,1)== "/"
		if(bin_url_inicio_barra){
			b.apilar(`ebca1 Error: La carpeta '${url}' es del sistema, por eso no se va a borrar.`)
		}else{
			var bin_borrar_carpeta = ls.rmdir(url)
			if(bin_borrar_carpeta){
				b.apilar(`bca3 Carpeta '${url}' borrada correctamente.`)
			}else{
				b.apilar(`abca2 Advertencia: La carpeta '${url}' no se pudo borrar.`)
			}
		}
	}
	, borrar_url: function borrar_url(url){
		if( b.es_constructor(url,String) ){
			var glob_mark = "glob_mark"
			var urls = ls.glob(`${url}/*`,glob_mark)
			urls.map(x=>{
				if(substr(x,-1)=="/"){
					arguments.callee(x)
				}else{
					ls.borrar_archivo(x)
				}
			})
			var bin_es_carpeta = ls.is_dir(url)
			if(bin_es_carpeta){
				ls.borrar_carpeta(url)
			}else{
				ls.borrar_archivo(url)
			}
		}else{
			var e = x=>`abo Advertencia: La ruta '${url}' ${x}, por eso no se va a intentar borrar ningún archivo`
			if(!url){
				b.apilar(e("es nula"))
			}
			if( !b.es_constructor(url,String) ){
				b.apilar(e("no es un string"))
			}
		}
		return o
	}
	, crear_carpeta: function crear_carpeta(url){
		var bin_existe_url = null
		var es_ruta_nula = false
		if(!url){
			b.apilar(`ede1 Error: La ruta '${url}' es nula.`)
		}else{
			bin_existe_url = ls.file_exists(url)
			if(!bin_existe_url){
				var bin_crear_carpeta = ls.mkdir(url, 0777, true)
				if(bin_crear_carpeta){
					b.apilar(`de Carpeta '${url}' creada correctamente.`)
				}else{
					b.apilar(`ade2 Advertencia: La carpeta '${url}' no se pudo crear.`)
				}
			}else{
				b.apilar(`ade0 Advertencia: La carpeta '${url}' existía.`)
			}
		}
	}
	, crear_subcarpetas: function crear_subcarpetas(url){
		var partes = ls.explode("/",url)
		var t = ls.count(partes)
		for(var i=1;i<=t;++i){
			var sector = ls.array_slice(partes,0,i)
			var subcarpeta = ls.implode("/",sector)
			ls.crear_carpeta(subcarpeta)
		}
		return t
	}
	, crear_archivo: function crear_archivo(url,datos){
		var var_subcarpetas = ls.crear_subcarpetas(url)
		var var_crear_archivo = ls.file_put_contents(url,datos)
		if(!datos){
			datos = ""
		}
		if(var_crear_archivo===ls.strlen(datos)){
			b.apilar(`co1 Archivo '${url}' creado correctamente.`)
		}else{
			if(var_crear_archivo===false){
				b.apilar(`aco3 Advertencia: El archivo '${url}' no se pudo crear.`)
			}else{
				b.apilar(`aco2 Advertencia: El archivo '${url}' se pudo crear, pero su contenido es parcial.`)
			}
		}
	}
	, crear_carpeta_si_no_existe: function crear_carpeta_si_no_existe(url){
		var d = 0
		var bin_existe_url = null
		if( !b.es_constructor(url,String) ){
			console.log("crear_carpeta_si_no_existe",url)
			console.trace()
		}
		bin_existe_url = ls.file_exists(url)
		if(bin_existe_url){
			var bin_es_carpeta = ls.is_dir(url)
			if(bin_es_carpeta){
				b.apilar(`cas La carpeta '${url}' existía.`)
				d = 3
			}else{
				ls.borrar_url(url)
				ls.crear_carpeta(url)
				d = 2
			}
		}else{
			ls.crear_carpeta(url)
			d = 1
		}
		return d
	}
	, crear_archivo_si_no_existe: function crear_archivo_si_no_existe(url,datos){
		var d = 0
		var bin_existe_url = null
		if( !b.es_constructor(url,String) ){
			console.log("crear_archivo_si_no_existe",url)
			console.trace()
		}
		bin_existe_url = ls.file_exists(url)
		if(bin_existe_url){
			var bin_es_carpeta = ls.is_dir(url)
			if(bin_es_carpeta){
				ls.borrar_url(url)
				ls.crear_archivo(url,datos)
				d = 3
			}else{
				b.apilar(`dus El archivo '${url}' existía.`)
				d = 2
			}
		}else{
			ls.crear_archivo(url,datos)
			d = 1
		}
		return d
	}
}

var prioridades = {
	localstorage: {
		procesar_subcarpeta: function(o,ret){ // Por hacer
			var subcarpeta = localStorage[`subcarpeta_${w.nombre}`]
			if(!subcarpeta){
				b.apilar("ipiu Advertencia: El nombre de la subcarpeta no estaba en localStorage.")
				// pendiente
			}else{
				ret(subcarpeta)
			}
		}
		, crear: function(opciones){ // Por hacer
			var o = {
				nivel: opciones.nivel==null?0:opciones.nivel
			}
			var carpeta = opciones.url
			var nombre = opciones.nombre
			var url = carpeta && `${carpeta}/${nombre}`
			++o.nivel
			if(o.nivel<2){
				var ret = function(subcarpeta){ // Por hacer
					console.log("ret",[...arguments])
					console.log("ret url",url)
				}
				prioridades.localstorage.procesar_subcarpeta(o,ret)
			}
		}
	}
	, fetch: {
		crear: function(opciones){ // Por hacer
		}
	}
}

var dri = {
	obtener_opciones: function obtener_opciones(){
		var prioridad = ([...document.querySelectorAll("[type=radio]")]
			.filter(x=>x.checked)[0].id
		)
		var crear_solo_si_no_existe = +(
			document.querySelector("#si_no_existe").checked
		)
		var vaciar_al_pulsar = +(
			document.querySelector("#vaciar_log_al_pulsar").checked
		)
		var url = document.querySelector("#url").value
		var nombre = document.querySelector("#nombre").value
		var contenido = document.querySelector("#contenido").value
		return {
			prioridad: prioridad
			, crear_solo_si_no_existe: crear_solo_si_no_existe
			, vaciar_al_pulsar: vaciar_al_pulsar
			, url: url
			, nombre: nombre
			, contenido: contenido
		}
	}
	, html: {
		crear: function crear(){ // Por hacer
			var opciones = dri.obtener_opciones()
			opciones.vaciar_al_pulsar && b.desapilar()
			if(opciones.prioridad=="localstorage"){
				delete opciones.prioridad
				prioridades.localstorage.crear(opciones)
			}
			if(opciones.prioridad=="fetch"){
				delete opciones.prioridad
				prioridades.fetch.crear(opciones)
			}
		}
		, borrar: function borrar(){ // Por hacer
			var opciones = dri.obtener_opciones()
			opciones.vaciar_al_pulsar && b.desapilar()
			if(opciones.prioridad=="localstorage"){
				delete opciones.prioridad
				//
			}
			if(opciones.prioridad=="fetch"){
				delete opciones.prioridad
				//
			}
		}
		, ver: function ver(){ // Por hacer
			var opciones = dri.obtener_opciones()
			opciones.vaciar_al_pulsar && b.desapilar()
			if(opciones.prioridad=="localstorage"){
				delete opciones.prioridad
				//
			}
			if(opciones.prioridad=="fetch"){
				delete opciones.prioridad
				//
			}
		}
		, cambiar: function cambiar(){ // Por hacer
			var opciones = dri.obtener_opciones()
			opciones.vaciar_al_pulsar && b.desapilar()
			if(opciones.prioridad=="localstorage"){
				delete opciones.prioridad
				//
			}
			if(opciones.prioridad=="fetch"){
				delete opciones.prioridad
				//
			}
		}
	}
}

document.onreadystatechange = function(x){
	if(!window.t){
		var d = null, a = null, div = null

		var nombre_documento = w.nombre_capitalizado
		b.agregar_favicon()
		b.agregar_nombre_documento(nombre_documento)

		div = document.querySelector("div")
		a = [/* x[2]==1 --> checkbox
			x[3]==1  -->  button
			x[3]==2  -->  Separador div
			x[3]==3  -->  Select */
[""                                     ,""                    ,0,2],
["prioridad",[                          //                     //  //
    "LocalStorage",                     //                     //  //
    "Fetch"                             //                     //  //
],0,3],                                 //                     //  //
[" "                                    ,""                    ,0,4],
["Crear solo si no existe"              ,"si_no_existe"        ,1,0],
[""                                     ,""                    ,0,2],
["Vaciar el registro al pulsar botones" ,"vaciar_log_al_pulsar",1,0],
[""                                     ,""                    ,0,2],
["Ruta"                                 ,"url"                 ,0,0],
["Nombre"                               ,"nombre"              ,0,0],
["Contenido"                            ,"contenido"           ,0,0],
[""                                     ,""                    ,0,2],
["Vaciar el registro"                   ,"b.desapilar"         ,0,1],
[" Archivo: "                           ,""                    ,0,4],
["Crear"                                ,"dri.html.crear"      ,0,1],
["Borrar"                               ,"dri.html.borrar"     ,0,1],
["Ver"                                  ,"dri.html.ver"        ,0,1],
["Cambiar"                              ,"dri.html.cambiar"    ,0,1],
		].map(x=>{
			if(x[3]==2){
				d = document.createElement("div")
			}
			if(x[3]==4){
				var a = document.createElement("a")
				a.innerHTML = `${x[0]}`
				d.appendChild(a)
			}
			if(x[3]==0){
				var a = ["label","input","a","a"].map(x=>document.createElement(x))
				a[3].innerHTML = " "
				a[0].className = "ina"
				a[0].innerHTML = `${x[0]}: `
				a[1].id = x[1]
				a[1].className = "re0"
				x[2]==1 && (a[1].type = "checkbox")
				a[2].innerHTML = " "
				a[0].appendChild(a[1])
				d.appendChild(a[3])
				d.appendChild(a[0])
				d.appendChild(a[2])
				div.appendChild(d)
			}
			if(x[3]==1){
				var u = ["button","a"].map(x=>document.createElement(x))
				var c = u[0]
				var a = u[1]
				c.innerHTML = x[0]
				c.addEventListener("click",Function(`${x[1]}()`))
				c.className = "tin"
				a.innerHTML = " "
				d.appendChild(c)
				d.appendChild(a)
				div.appendChild(d)
			}
			if(x[3]==3){
				var a = ["a","a","a",
					...x[1].map(x=>{
						return ["input",x]
					})
				].map(x=>{
					return b.es_constructor(x,Array) && (
						[document.createElement(x[0]),...x])
						|| document.createElement(x)
				})
				var nombre = x[0]
				a[1].className = "tin"
				a[0].innerHTML = " "
				a[1].innerHTML = `${
					nombre[0].toUpperCase()
				}${
					nombre.slice(1).toLowerCase()
				}: `
				a.slice(3).map(x=>{
					var r = x[0]
					var t = x[2]
					var n = a[1]
					var u = t.toLowerCase()
					var i = document.createElement("label")
					var i2 = document.createElement("a")
					var e = document.createElement("a")
					i2.innerHTML = " "
					i.className = "ina"
					r.type = "radio"
					r.id = u
					r.name = nombre
					e.innerHTML = t
					i.appendChild(r)
					i.appendChild(e)
					n.appendChild(i)
					n.appendChild(i2)
				})
				a[0].appendChild(a[1])
				d.appendChild(a[0])
				div.appendChild(d)
			}
		})
		
		document.querySelector("input").checked = 1

		var nota = document.querySelector(".nota")
		nota.innerHTML = ""

		var registro = document.querySelector(".registro")
		var r_a = registro.querySelector("a")
		var pre = document.querySelector("pre")
		registro.className = "re0"
		r_a.innerHTML = "Registro"
		pre.className = "re1"

		window.t = 1
	}
}
