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

	//obtener los datos de usuario y tareopersona por proyecto
	reporte.getData = function (proyecto, index) {
		if (reporte.proyectos[index]['selected']) {
			reporte.agregarUsuarios(proyecto)
			reporte.agregarTareopersona(proyecto)
		} else {
			reporte.borrarUsuarios(proyecto)
			reporte.borrarTareopersona(proyecto)
		}
	}
    
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
				tareo = new Tareopersona(item.codigo_prop_proy, item.codigo_actividad, item.dni, item.uid, item.rate_proyecto, item.proyectoid, item.tipo_actividad, item.nombre, item.nombre_proyecto, item.estado, item.h_real_total, item.data_horas)
				tareo.uid = tareo.uid.changeFormat()
				tareo.setHoras()
				reporte.tareopersona.push(tareo)
			})
			reporte.sumarVerticales()
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
				reporte.tareopersona.push(tareopersona)
			}
		})
		reporte.sumarVerticales()
	}

	//funcion para cambio de fechas y recalcular los dias visibles
	reporte.cambiarFecha = function () {
		reporte.fecha_from.date = reporte.fecha_from.cadena.toDate()
		reporte.fecha_to.date = reporte.fecha_to.cadena.toDate()
		reporte.rellenarFechas()
		arrayTemp = []
		reporte.tareopersona.forEach(function (tareopersona) {
			tareopersona.setHoras()
			arrayTemp.push(tareopersona)
		})
		reporte.tareopersona = arrayTemp
		reporte.sumarVerticales()
		reporte.dtInstance.rerender()
	}

	//funcion para la suma de columnas segun dia, semana y mes
	reporte.sumarVerticales = function () {
		var suma_dias = []
		var suma_semanas = []
		var suma_meses = []
		var i = 0

		reporte.dias.forEach(function (dia) {
			var suma = 0
			reporte.tareopersona.forEach(function (tareopersona) {
				if (tareopersona.horasxdias[i] != '--') {
					suma = suma + parseFloat(tareopersona.horasxdias[i])
				}
			})
			i = i + 1
			suma_dias.push(suma)
		})
				
		i = 0
		reporte.semanas.forEach(function (semana) {
			var suma = 0
			reporte.tareopersona.forEach(function (tareopersona) {
				if (tareopersona.horasxsemanas[i] != '--') {
					suma = suma + parseInt(tareopersona.horasxsemanas[i])
				}
			})
			i = i + 1
			suma_semanas.push(suma)
		})
		
		i = 0
		reporte.meses.forEach(function (mes) {
			var suma = 0
			reporte.tareopersona.forEach(function (tareopersona) {
				if (tareopersona.horasxmeses[i] != '--') {
					suma = suma + parseInt(tareopersona.horasxmeses[i])
				}
			})
			i = i + 1
			suma_meses.push(suma)
		})

		reporte.dias_suma = suma_dias
		reporte.semanas_suma = suma_semanas
		reporte.meses_suma = suma_meses
	}

	//funcion que permite la exportacion a excel de la tabla
	reporte.exportarXls = function () {
		$('#tareopersona-table').tableExport({type:'excel', escape:'false'})
	}

	reporte.exportarPdf = function (argument) {
		var doc = new jsPDF('landscape')
		doc.text(5, 10, 'Reporte Tareopersona')
		doc.setFontSize(7)

		doc.text(5, 15, 'CODIGO')
		doc.text(20, 15, 'USUARIO')
		doc.text(40, 15, 'RATE')
		doc.text(50, 15, 'CODIGO PROYECTO')
		doc.text(80, 15, 'TIPO')
		doc.text(100, 15, 'NOMBRE PROYECTO')
		doc.text(150, 15, 'ESTADO')
		
		if (reporte.dias_visible) {
			var j = 1
			reporte.dias.forEach(function (dia) {
				doc.text((j * 5) + 160, 15, dia.cadena.toString())
				j = j + 1
			})
		}

		if (reporte.semanas_visible) {
			var j = 1
			reporte.semanas.forEach(function (semana) {
				doc.text((j * 20) + 160, 15, semana.toString())
				j = j + 1
			})
		}

		if (reporte.meses_visible) {
			var j = 1
			reporte.meses.forEach(function (mes) {
				doc.text((j * 20) + 160, 15, mes.toString())
				j = j + 1
			})
		}

		doc.text(280, 10, 'TOTAL')

		for (var i = 0; i < reporte.tareopersona.length; i++) {
			var vertical = ((i + 1) * 5) + 15

			doc.text(5, vertical, reporte.tareopersona[i].dni)
			doc.text(20, vertical, reporte.tareopersona[i].uid)
			doc.text(40, vertical, reporte.tareopersona[i].rate_proyecto)
			doc.text(50, vertical, reporte.tareopersona[i].codigo_prop_proy)
			doc.text(80, vertical, reporte.tareopersona[i].tipo_actividad)
			doc.text(100, vertical, (reporte.tareopersona[i].um_nombre + '/' + reporte.tareopersona[i].nombre_proyecto).slice(0, 40))
			doc.text(150, vertical, reporte.tareopersona[i].estado)
			
			if (reporte.dias_visible) {
				var j = 1
				reporte.tareopersona[i].horasxdias.forEach(function (dia) {
					doc.text((j * 5) + 160, vertical, dia.toString())
					j = j + 1
				})
			}

			if (reporte.semanas_visible) {
				var j = 1
				reporte.tareopersona[i].horasxsemanas.forEach(function (semana) {
					doc.text((j * 20) + 160, vertical, semana.toString())
					j = j + 1
				})
			}

			if (reporte.meses_visible) {
				var j = 1
				reporte.tareopersona[i].horasxmeses.forEach(function (mes) {
					doc.text((j * 20) + 160, vertical, mes.toString())
					j = j + 1
				})
			}

			doc.text(280, vertical, reporte.tareopersona[i].horas_total.toString())
		}

		var archivo = 'Reporte-' + new Date().Ddmmyyyy() + '.pdf'
		doc.save(archivo)
		//$('#tareopersona-table').tableExport({type:'pdf', pdfFontSize:'7', escape:'false'})
	}

	//ejecucion de algunas funciones al cargar la pagina
	angular.element(document).ready(function () {
		//solicitud de clientes y gerentes al cargar la pagina
		reporte.getClientes()
		reporte.getGerentes()
		//rellenar los array de dias, semanas y meses deacuerdo a las fechas por defecto
		reporte.rellenarFechas()
    })
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
//de cadena uid a usuario respectivamente
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
var Tareopersona = function (codigo_prop_proy, codigo_actividad, dni, uid, rate_proyecto, proyectoid, tipo_actividad, um_nombre, nombre_proyecto, estado, h_real_total, horas) {
	if (rate_proyecto == null || rate_proyecto == '' || rate_proyecto == undefined) {
		rate_proyecto = '--'
	}

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
	this.horas = horas
	this.horasxdias = []
	this.horasxsemanas = []
	this.horasxmeses = []
	this.horas_total = 0
}

//Metodo para rellenar las horas de tareopersona segun los dias escogidos
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
				} else if (item.h_real.indexOf(".00") != -1) {
					horaxdia = item.h_real.slice(0, item.h_real.indexOf(".00"))
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

	this.horasxdias = horasxdia
	this.horasxsemanas = horasxsemana
	this.horasxmeses = horasxmes
	this.horas_total = suma
}