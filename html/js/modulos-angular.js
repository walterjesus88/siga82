/**
* reporteApp Module
*
* Description
* Modulo sobre el manejo de los datos de reporte relacionados a tareopersona
*/
/*creacion de modulo con inyeccion de dependencias:
datatables: una extension para manejar los datatables de jquery.
ngtable: un extension alternativa a datatables*/
angular.module('reporteApp', ['datatables']).
controller('mainController', ['$http', function($http){

	//obtener una referencia del scope para el funcionamiento del data binding con la vista
	reporte = this

	//linea para poder redibujar el datatable de angular
	reporte.dtInstance = {}

	//inicializando variables para el rango de fecha
	var fecha_inicial_cad = '10-06-2015'
	var fecha_inicial_date = fecha_inicial_cad.toDate()
	var fecha_final_date = new Date()
	var fecha_final_cad = fecha_final_date.Ddmmyyyy()


	//inicializando las variables necesarias relacionadas a la vista
	reporte.tipo_actividad = [{'id': 'P', 'text': 'Facturable'}, {'id': 'G', 'text': 'No Facturable'}, {'id': 'A', 'text': 'Administración'}]
	reporte.tipo_activo = {'Todo': true, 'Facturable': true, 'No Facturable': true, 'Administración': true}
	reporte.agrupado = [{'text': 'por Días', 'value': 'xdias'}, {'text': 'por Semanas', 'value': 'xsemanas'}, {'text': 'por Meses', 'value': 'xmeses'}]
	reporte.tareopersona_void = true
	reporte.text_proyectos = 'Seleccione un Cliente o Gerente para mostrar sus proyectos activos.'
	reporte.disabled_children = true
	
	//elementos seleccionados por defecto en los combobox
	reporte.cliente_seleccionado = 'todos'
	reporte.usuario_seleccionado = '.'
	reporte.gerente_seleccionado = 'todos'
	reporte.agrupado_seleccionado = 'xdias'
	
	//elementos por defecto de fecha y campos visibles por defecto
	reporte.fecha_from = {'cadena': fecha_inicial_cad, 'date': fecha_inicial_date}
	reporte.fecha_to = {'cadena': fecha_final_cad, 'date': fecha_final_date}
	reporte.dias = []
	reporte.semanas = []
	reporte.meses = []
	reporte.dias_suma = []
	reporte.semanas_suma = []
	reporte.meses_suma = []
	reporte.dias_visible = true
	reporte.semanas_visible = false
	reporte.meses_visible = false	
		
	//creando las variables que contendran los datos de respuesta del servidor
	reporte.gerentes = []
	reporte.clientes = []
	reporte.unidadminera = []
	reporte.proyectos = []
	reporte.usuarios = []
	reporte.tareopersona = []
	
	//funciones que realizaran peticiones al servidor para obtener rellenar los array

	reporte.getGerentes = function () {
		$http.get('/reporte/index/gerentes')
		.success(function (res) {
			reporte.gerentes = res
		})
	}

	reporte.getClientes = function () {
		$http.get('/reporte/index/clientes')
		.success(function (res) {
			reporte.clientes = res
		})
	}

	reporte.getUnidadMinera = function (seleccionado) {
		$http.get('/reporte/index/unidadminera/clienteid/' + seleccionado)
		.success(function (res) {
			reporte.unidadminera = res
		})
	}

	//solicitud de proyectos por cliente o por gerente
	reporte.getProyectosByCliente = function (clienteid) {
		reporte.proyectos = []
		reporte.usuarios = []
		reporte.tareopersona = []
		reporte.disabled_children = true
		reporte.gerente_seleccionado = 'todos'
		$http.get('/reporte/index/proyectos/clienteid/' + clienteid)
		.success(function (res) {
			if (res.length == 0) {
				reporte.text_proyectos = 'El Cliente seleccionado no tiene proyectos actualmente.'
			} else {
				res.forEach(function (proyecto) {
					reporte.text_proyectos = ''
					proyecto['selected'] = false
					reporte.proyectos.push(proyecto)
				})
			}
		})
	}
	reporte.getProyectosByGerente = function (gerenteid) {
		reporte.proyectos = []
		reporte.usuarios = []
		reporte.tareopersona = []
		reporte.disabled_children = true
		reporte.cliente_seleccionado = 'todos'
		$http.get('/reporte/index/proyectos/gerenteid/' + gerenteid)
		.success(function (res) {
			if (res.length == 0) {
				reporte.text_proyectos = 'El Gerente seleccionado no tiene proyectos actualmente'
			} else {
				res.forEach(function (proyecto) {
					reporte.text_proyectos = ''
					proyecto['selected'] = false
					reporte.proyectos.push(proyecto)
				})
			}
		})	
	}

	reporte.getData = function (proyecto, index) {
		if (reporte.proyectos[index]['selected']) {
			reporte.agregarUsuarios(proyecto)
			reporte.agregarTareopersona(proyecto)
		} else {
			reporte.borrarUsuarios(proyecto)
			reporte.borrarTareopersona(proyecto)
		}
	}

	
	//funciones relacionadas a la vista para el manejo de eventos
	//funciones para los marcar los checkbox de tipo_actividad y agrupamiento visible
	reporte.tipoActivoTodo = function (id) {
		if (reporte.tipo_activo.Todo) {
			reporte.tipo_activo = {'Todo': true, 'Facturable': true, 'No Facturable': true, 'Administración': true}
		} else{
			reporte.tipo_activo = {'Todo': false, 'Facturable': false, 'No Facturable': false, 'Administración': false}
		}
	}

	reporte.tipoActivoHijo = function () {
		if(reporte.tipo_activo['Facturable'] == true && reporte.tipo_activo['No Facturable']  == true && reporte.tipo_activo['Administración'] == true) {
			reporte.tipo_activo.Todo = true
		} else {
			reporte.tipo_activo.Todo = false
		}
	}

	reporte.cambiarAgrupamiento = function () {
		if (reporte.agrupado_seleccionado == 'xdias') {
			reporte.dias_visible = true
			reporte.semanas_visible = false
			reporte.meses_visible = false
		} else if (reporte.agrupado_seleccionado == 'xsemanas') {
			reporte.dias_visible = false
			reporte.semanas_visible = true
			reporte.meses_visible = false
		} else if (reporte.agrupado_seleccionado == 'xmeses') {
			reporte.dias_visible = false
			reporte.semanas_visible = false
			reporte.meses_visible = true
		}
	}

	//funcion para rellenar los array de dias, meses y semanas deacuerdo a las fechas selecccionadas
	reporte.rellenarFechas = function () {
		var dias = []
		var meses = []
		var semanas = []
		var inicial = reporte.fecha_from.date
		var ultimo = reporte.fecha_to.date
		var dias_sem = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab']
		var nombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Noviembre', 'Diciembre']
		var dias_trans = Math.floor((ultimo.getTime() - inicial.getTime()) / (1000 * 60 * 60 * 24)) + 1
		var fecha = inicial
		var mes = ''
		var semana = ''

		for (var i = 0; i < dias_trans; i++) {
			var dia = {'cadena': '', 'fecha': new Date()}
			dia.cadena = dias_sem[fecha.getDay()].toString() + ' ' + fecha.getDate()
			dia.fecha = fecha.Yyyymmdd()
			dias[i] = dia
			fecha.setDate(fecha.getDate() + 1)
		}
				
		dias.forEach(function (dia) {
			if (dia.fecha.toDate().getMonth() != mes) {
				mes = dia.fecha.toDate().getMonth()
				meses.push(nombres[mes])
			}
			if (dia.fecha.toDate().getWeek() != semana) {
				semana = dia.fecha.toDate().getWeek()
				semanas.push('Semana ' + semana)
			}
		})

		reporte.dias = dias
		reporte.meses = meses
		reporte.semanas = semanas
	}

	//ejecucion de algunas funciones al cargar la pagina
	angular.element(document).ready(function () {
		//solicitud de clientes y gerentes al cargar la pagina
		reporte.getClientes()
		reporte.getGerentes()
		//rellenar los array de dias, semanas y meses deacuerdo a las fechas por defecto
		reporte.rellenarFechas()
    })

    
	//funciones para agregar o quitar usuarios de la lista segun los proyectos visibles
    reporte.agregarUsuarios = function (proyecto) {
		$http.get('/reporte/index/usuarios/codigo_prop_proy/' + proyecto)
		.success(function (res) {
			res.forEach(function (item) {
				//inicializar la varible existe
				existe = 0
				item.uid = item.uid.changeFormat()
				//verificar si usuario existe en la lista
				for (var i = 0; i < reporte.usuarios.length; i++) {
					if ((item.uid) == reporte.usuarios[i].uid) {
						existe = 1
					}
				}
				//si existe agregar el numero de proyectos en 1, sino aumentarlo al array
				if (existe == 1) {
					for (var i = 0; i < reporte.usuarios.length; i++) {
						if (item.uid == reporte.usuarios[i].uid) {
							reporte.usuarios[i]['num_pro'] = reporte.usuarios[i]['num_pro'] + 1
						}
					}
				} else {
					item['num_pro'] = 1
					reporte.usuarios.push(item)
				}
			})
			//verificar si array no esta vacio para habilitar su combobox
			if (reporte.usuarios.length != 0) {
				reporte.disabled_children = false
			}
		})
	}

	reporte.borrarUsuarios = function (proyecto) {
		$http.get('/reporte/index/usuarios/codigo_prop_proy/' + proyecto)
		.success(function (res) {
			res.forEach(function (item) {
				item.uid = item.uid.changeFormat()
				//disminuir en 1 el num_pro de los elementos que aparecen en la lista
				for (var i = 0; i < reporte.usuarios.length; i++) {
					if (item.uid == reporte.usuarios[i].uid) {
						reporte.usuarios[i]['num_pro'] = reporte.usuarios[i]['num_pro'] - 1
					}
				}
			})
			//eliminar a todos los usuarios que tengan 0 proyectos
			arrayTemp = reporte.usuarios
			reporte.usuarios = []
			for (var i = 0; i < arrayTemp.length; i++) {
				if (parseInt(arrayTemp[i]['num_pro']) != 0) {
					reporte.usuarios.push(arrayTemp[i])
				}
			}
			//verificar si el array esta vacio para deshabilitar su combobox
			if (reporte.usuarios.length == 0) {
				reporte.disabled_children = true
			}
		})
	}

	//funciones para agregar o eliminar tareopersonas de la tabla segun los proyectos seleccionados
	reporte.agregarTareopersona = function (proyecto) {
		//cargando modal de espera
		$("#wait").modal()

		//solicitud al servidor por un objeto json que contenga tareopersona
		$http.get('/reporte/index/tareopersonajson/codigo_prop_proy/'+ proyecto)
		.success(function (res) {
			res.forEach(function (item) {
				item.horas = rellenarDiasTareopersona(item)
				item.horasxmeses = rellenarMesesTareopersona(item)
				item.horasxsemanas = rellenarSemanasTareopersona(item)
				item.horas_total = sumarHorizontal(item)
				item.uid = item.uid.changeFormat()
				reporte.tareopersona.push(item)
			})
			reporte.dias_suma = sumarVerticalDias()
			reporte.semanas_suma = sumarVerticalSemanas()
			reporte.meses_suma = sumarVerticalMeses()
			//verificar tamaño del array para habilitar o deshabilitar los elementos del DOM asociados
			if (reporte.tareopersona.length == 0) {
				reporte.tareopersona_void = true
			} else{
				reporte.tareopersona_void = false
			}
			//ocultar modal cuando se terminan de cargar los datos
			$("#wait").modal('hide')
		})
	}

	reporte.borrarTareopersona = function (proyecto) {
		arrayTemp = reporte.tareopersona
		reporte.tareopersona = []
		arrayTemp.forEach(function (tareopersona) {
			if (tareopersona.codigo_prop_proy != proyecto) {
				reporte.tareopersona.push(item)
			}
		})
		reporte.dias_suma = sumarVerticalDias()
		reporte.semanas_suma = sumarVerticalSemanas()
		reporte.meses_suma = sumarVerticalMeses()
	}

	//funciones para el manejo de los dias visibles en la tabla tareopersona
	function rellenarDiasTareopersona (tareopersona) {
		var horas = []
		for (var i = 0; i < reporte.dias.length; i++) {
			hora = '--'
			tareopersona.data_horas.forEach(function (item) {
				if (reporte.dias[i].fecha == item.fecha_tarea) {
					if (item.h_real == '' || item.h_real == undefined) {
						hora = '--'
					} else {
						hora = item.h_real
					}
				}
			})
			horas.push(hora)
		}
		return horas	
	}

	function rellenarMesesTareopersona (tareopersona) {
		var horasxmeses = []
		var nombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octobre', 'Noviembre', 'Diciembre']
		for (var i = 0; i < reporte.meses.length; i++) {
			var hora = 0
			var mes1 = reporte.meses[i]
			var inicial = reporte.fecha_from.cadena.toDate()
			var ultimo = reporte.fecha_to.cadena.toDate()
			tareopersona.data_horas.forEach(function (item) {
				var mes2 = nombres[item.fecha_tarea.toDate().getMonth()]
				var dia = item.fecha_tarea.toDate()
				if (mes1 == mes2 && inicial <= dia && dia <= ultimo) {
					if (isNaN(parseFloat(item.h_real)) || item.h_real == '' || item.h_real == null || item.h_real == undefined) {
						adicional = 0
					} else {
						adicional = parseFloat(item.h_real)
					}
					hora = hora + adicional
				}
			})
			if (hora == 0) {
				hora = '--'
			}
			horasxmeses.push(hora)
		}
		return horasxmeses
	}

	function rellenarSemanasTareopersona (tareopersona) {
		var horasxsemanas = []
		for (var i = 0; i < reporte.semanas.length; i++) {
			var hora = 0
			var semana1 = parseInt(reporte.semanas[i].slice(7))
			var inicial = reporte.fecha_from.cadena.toDate()
			var ultimo = reporte.fecha_to.cadena.toDate()
			tareopersona.data_horas.forEach(function (item) {
				var semana2 = item.fecha_tarea.toDate().getWeek()
				var dia = item.fecha_tarea.toDate()
				if (semana1 == semana2 && inicial <= dia && dia <= ultimo) {
					if (isNaN(parseFloat(item.h_real)) || item.h_real == '' || item.h_real == null || item.h_real == undefined) {
						adicional = 0
					} else {
						adicional = parseFloat(item.h_real)
					}
					hora = hora + adicional
				}
			})
			if (hora == 0) {
				hora = '--'
			}
			horasxsemanas.push(hora)
		}
		return horasxsemanas
	}

	reporte.cambiarFecha = function () {
		reporte.fecha_from.date = reporte.fecha_from.cadena.toDate()
		reporte.fecha_to.date = reporte.fecha_to.cadena.toDate()
		reporte.rellenarFechas()
		arrayTemp = []
		reporte.tareopersona.forEach(function (item) {
			item.horas = rellenarDiasTareopersona(item)
			item.horasxmeses = rellenarMesesTareopersona(item)
			item.horasxsemanas = rellenarSemanasTareopersona(item)
			item.horas_total = sumarHorizontal(item)
			arrayTemp.push(item)
		})
		reporte.dias_suma = sumarVerticalDias()
		reporte.semanas_suma = sumarVerticalSemanas()
		reporte.meses_suma = sumarVerticalMeses()
		reporte.tareopersona = arrayTemp
		reporte.dtInstance.rerender()
	}

	function sumarHorizontal (tareopersona, xfecha) {
		var suma = 0
		tareopersona.horas.forEach(function (item) {
			if (item != '--') {
				suma = suma + parseFloat(item)
			}
		})
		return suma
	}

	function sumarVerticalDias () {
		var suma_ver = []
		var i = 0
		reporte.dias.forEach(function (dia) {
			var suma = 0
			reporte.tareopersona.forEach(function (tareopersona) {
				if (tareopersona.horas[i] != '--') {
					suma = suma + parseInt(tareopersona.horas[i])
				}
			})
			i = i + 1
			suma_ver.push(suma)
		})
		return suma_ver
	}

	function sumarVerticalSemanas () {
		var suma_ver = []
		var i = 0
		reporte.semanas.forEach(function (semana) {
			var suma = 0
			reporte.tareopersona.forEach(function (tareopersona) {
				if (tareopersona.horasxsemanas[i] != '--') {
					suma = suma + parseInt(tareopersona.horasxsemanas[i])
				}
			})
			i = i + 1
			suma_ver.push(suma)
		})
		return suma_ver
	}

	function sumarVerticalMeses () {
		var suma_ver = []
		var i = 0
		reporte.meses.forEach(function (mes) {
			var suma = 0
			reporte.tareopersona.forEach(function (tareopersona) {
				if (tareopersona.horasxmeses[i] != '--') {
					suma = suma + parseInt(tareopersona.horasxmeses[i])
				}
			})
			i = i + 1
			suma_ver.push(suma)
		})
		return suma_ver
	}

	reporte.exportarPdf = function () {
		$('#tareopersona-table').tableExport({type:'pdf', htmlContent:'false', pdfFontSize:6, pdfLeftMargin:10, escape:'false'})
	}
}])


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
	var consta = [2, 1, 7, 6, 5, 4, 3]
	var dia = this.getDate()
	var mes = this.getMonth()
	var ano = this.getFullYear()
	var dia_pri = new Date(ano, 0, 1)
	dia_pri = dia_pri.getDay()
	dia_pri = consta[parseInt(dia_pri)]
	var tiempo0 = new Date(ano, 0, dia_pri)
	dia = dia + dia_pri
	var tiempo1 = new Date(ano, mes, dia)
	var lapso = tiempo1 - tiempo0
	var semanas = Math.floor(lapso / 1000 / 60 / 60 / 24 / 7)
	if (dia_pri == 1) {
		semanas = semanas + 1
	}
	if (semanas == 0) {
		semanas = 52
	}
	return semanas
}

//Agregando funciones a la clase String para convertir a fecha ,obtener mes y cambiar formato
//respectivamente
String.prototype.toDate = function () {
	cadena = this.valueOf()
	var datos = []
	datos = cadena.split("-")
	if (datos[0].length == 4) {
		var dia = datos[2]
		datos[2] = datos[0]
		datos[0] = dia 
	}
	var dd = parseInt(datos[0])
	var mm = parseInt(datos[1])
	var yyyy = parseInt(datos[2])
	var f = new Date()
	f.setDate(dd)
	f.setMonth(mm - 1)
	f.setFullYear(yyyy)
	return f
}

String.prototype.toMonth = function () {
	var cadena = this.valueOf()
	var nombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Noviembre', 'Diciembre']
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

//definiendo objeto tareopersona
var Tareopersona = function (codigo_prop_proy, codigo_actividad, dni, uid, rate_proyecto, proyectoid, tipo_actividad, um_nombre, nombre_proyecto, estado, h_real_total) {
	this.codigo_prop_proy = codigo_prop_proy
	this.codigo_actividad = codigo_actividad
	this.dni = dni
	this.uid = uid
	this.rate_proyecto = rate_proyecto
	this.proyectoid = proyectoid
	this.tipo_actividad = tipo_actividad
	this.um_nombre = um_nombre
	this.nombre_proyecto = nombre_proyecto
	this.estado = estado
	this.h_real_total = h_real_total
	this.horas = []
	this.horasxdia = []
	this.horasxsemanas = []
	this.horasxmeses = []
	this.horas_total = 0
}

Tareopersona.prototype.setHoras = function () {
	var horasxdia = []
	var horasxsemana = []
	var horasxmes = []
	var horaxdia = ''
	var inicial = reporte.fecha_from.cadena.toDate()
	var ultimo = reporte.fecha_to.cadena.toDate()
	
	//rellenar las horas por dia de tareopersona
	for (var i = 0; i < reporte.dias.length; i++) {
		horaxdia = '--'
		this.horas.forEach(function (item) {
			if (reporte.dias[i].fecha == item.fecha_tarea) {
				if (item.h_real == '' || item.h_real == undefined || item.h_real == '0') {
					horaxdia = '--'
				} else {
					horaxdia = item.h_real
				}
			}
		})
		horasxdia.push(horaxdia)
	}
	
	//rellenar las horas por semana de tareopersona
	for (var i = 0; i < reporte.semanas.length; i++) {
		var horaxsemana = 0
		var semana1 = parseInt(reporte.semanas[i].slice(7))
		this.horas.forEach(function (item) {
			var semana2 = item.fecha_tarea.toDate().getWeek()
			var dia = item.fecha_tarea.toDate()
			if (semana1 == semana2 && inicial <= dia && dia <= ultimo) {
				if (isNaN(parseFloat(item.h_real)) || item.h_real == '' || item.h_real == null || item.h_real == undefined) {
					adicional = 0
				} else {
					adicional = parseFloat(item.h_real)
				}
				horaxsemana = horaxsemana + adicional
			}
		})
		if (horaxsemana == 0) {
			horaxsemana = '--'
		}
		horasxsemana.push(horaxsemana)
	}

	//rellenar las horas por mes de tareopersona
	for (var i = 0; i < reporte.meses.length; i++) {
		var horaxmes = 0
		var mes1 = reporte.meses[i].toMonth()
		this.horas.forEach(function (item) {
			var mes2 = item.fecha_tarea.toDate().getMonth()
			var dia = item.fecha_tarea.toDate()
			if (mes1 == mes2 && inicial <= dia && dia <= ultimo) {
				if (isNaN(parseFloat(item.h_real)) || item.h_real == '' || item.h_real == null || item.h_real == undefined) {
					adicional = 0
				} else {
					adicional = parseFloat(item.h_real)
				}
				horaxmes = horaxmes + adicional
			}
		})
		if (horaxmes == 0) {
			horaxmes = '--'
		}
		horasxmes.push(horaxmes)
	}

	//suma total de horas de tareopersona
	var suma = 0
	horasxdia.forEach(function (item) {
		if (item != '--') {
			suma = suma + parseFloat(item)
		}
	})

	this.horasxdia = horasxdia
	this.horasxsemanas = horasxsemanas
	this.horasxmeses = horasxmes
	this.horas_total = suma
}