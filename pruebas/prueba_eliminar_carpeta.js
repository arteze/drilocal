fetch("lib/drilocal.php?a=bu&b=a&c=dataos").then(x=>x.text()).then(x=>{
	var res = null
	try{
		JSON.parse(x).registro.join("\n")
	}catch(e){
		console.log(e)
		console.log(x)
	}
})
