//Agregando funciones a la clase Date para convertir a cadena con formato yyyy-mm-dd y
//dd-mm-yyyy y para obtener semana del año respectivamente
Date.prototype.Yyyymmdd = function () {
	var yyyy = this.getFullYear()
	var mm = this.getMonth() + 1
	var dd = this.getDate()
	if (dd < 10) {
		dd = '0' + dd
	}
	if (mm < 10) {
		mm = '0' + mm
	}
	return yyyy + '-' + mm + '-' + dd
}

Date.prototype.Ddmmyyyy = function () {
	var dd = this.getDate()
	var mm = this.getMonth() + 1
	var yyyy = this.getFullYear()
	if (dd < 10) {
		dd = '0' + dd
	}
	if (mm < 10) {
		mm = '0' + mm
	}
	return dd + '-' + mm + '-' + yyyy
}

Date.prototype.getWeek = function () {
	var semana = $.datepicker.iso8601Week(this)
	return semana
}

//Agregando funciones a la clase String para convertir a fecha ,obtener mes y cambiar formato
//de cadena uid a usuario respectivamente
String.prototype.toDate = function () {
	cadena = this.valueOf()
	if (cadena.match(/\//)){
    	cadena = cadena.replace(/\//g,"-",cadena)
  	}
	var datos = []
	datos = cadena.split("-")
	if (datos[0].length == 4) {
		var dia = datos[2]
		datos[2] = datos[0]
		datos[0] = dia
	}
	var dd = parseInt(datos[0])
	var mm = parseInt(datos[1]) - 1
	var yyyy = parseInt(datos[2])
	var f = new Date()
	f.setDate(dd)
	f.setMonth(mm)
	f.setFullYear(yyyy)
	return f
}

String.prototype.toMonth = function () {
	var cadena = this.valueOf()
	var nombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre']
	var x = nombres.indexOf(cadena)
	return x
}

String.prototype.changeFormat = function () {
	cadena = this.valueOf()
	var cont = cadena.indexOf(".")
	if (cont != -1) {
		var datos = cadena.split(".")
		var nombre = datos[0].charAt(0).toUpperCase() + datos[0].slice(1)
		var apellido = datos[1].charAt(0).toUpperCase() + datos[1].slice(1)
		return nombre + ' ' + apellido
	} else {
		return cadena.charAt(0).toUpperCase() + cadena.slice(1)
	}
}
