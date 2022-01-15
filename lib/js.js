var w = {
	capitalizar: function capitalizar(){
		return `${
			window.nombre[0].toUpperCase()
		}${
			window.nombre.slice(1)
		}`
	}
	, agregar: {
		script: function script(nombre){
			var s = document.createElement("script")
			window.nombre = "drilocal"
			window.nombre_capitalizado = w.capitalizar(window.nombre)
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
		window.nombre = "drilocal"
		window.nombre_capitalizado = w.capitalizar(window.nombre)
		w.agregar.script(window.nombre)
		w.agregar.estilo("estilo")
	}
}
w.programa()
