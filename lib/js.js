var w = {
	capitalizar: function capitalizar(){
		return `${
			w.nombre[0].toUpperCase()
		}${
			w.nombre.slice(1)
		}`
	}
	, agregar: {
		script: function script(nombre){
			var s = document.createElement("script")
			w.nombre = "drilocal"
			w.nombre_capitalizado = w.capitalizar(w.nombre)
			s.src = `lib/${nombre}.js?${Date.now()}`
			document.head.appendChild(s)
		}
		, estilo: function estilo(nombre){
			var s = document.createElement("link")
			s.rel = "stylesheet"
			s.href = `lib/${nombre}.css?${Date.now()}`
			document.head.appendChild(s)
		}
	}
	, programa: function programa(){
		w.nombre = "drilocal"
		w.nombre_capitalizado = w.capitalizar(w.nombre)
		w.agregar.script(w.nombre)
		w.agregar.estilo("estilo")
	}
}
w.programa()
