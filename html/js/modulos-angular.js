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

	//obtener una referencia del scope para el funcionamiento del data binding
	reporte = this

	reporte.dtInstance = {}

	//inicializando variables para el rango de fecha
	var fecha_inicial_cad = '10-06-2015'
	var fecha_inicial_date = cadenaToFecha(fecha_inicial_cad)
	var fecha_final_date = new Date()
	var fecha_final_cad = cadenaFecha(fecha_final_date)


	//inicializando las variables necesarias relacionadas a la vista
	reporte.tipo_actividad = [{'id': 'P', 'text': 'Facturable'}, {'id': 'G', 'text': 'No Facturable'}, {'id': 'A', 'text': 'Administración'}]
	reporte.tipo_activo = {'Todo': true, 'Facturable': true, 'No Facturable': true, 'Administración': true}
	reporte.agrupado = [{'text': 'por Días', 'value': 'xdias'}, {'text': 'por Semanas', 'value': 'xsemanas'}, {'text': 'por Meses', 'value': 'xmeses'}]
	reporte.cliente_seleccionado = 'todos'
	reporte.usuario_seleccionado = '.'
	reporte.gerente_seleccionado = 'todos'
	reporte.agrupado_seleccionado = 'xdias'
	reporte.tareopersona_void = true
	reporte.text_proyectos = 'Seleccione un Cliente o Gerente para mostrar sus proyectos activos.'
	reporte.disabled_children = true
	reporte.fecha_from = {'cadena': fecha_inicial_cad, 'date': fecha_inicial_date}
	reporte.fecha_to = {'cadena': fecha_final_cad, 'date': fecha_final_date}
	reporte.dias = []
	reporte.semanas = []
	reporte.meses = []
	reporte.dias_suma = []
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

	reporte.getProyectos = function (elementoid, por) {
		reporte.proyectos = []
		reporte.usuarios = []
		reporte.tareopersona = []
		reporte.disabled_children = true
		if (por == 'byCliente') {
			reporte.gerente_seleccionado = 'todos'
			$http.get('/reporte/index/proyectos/clienteid/' + elementoid)
			.success(function (res) {
				if (res.length == 0) {
					reporte.text_proyectos = 'El Cliente seleccionado no tiene proyectos actualmente'
				} else {
					res.forEach(function (proyecto) {
						reporte.text_proyectos = ''
						proyecto['selected'] = false
						reporte.proyectos.push(proyecto)
					})
				}
			})
		} else if (por == 'byGerente') {
			reporte.cliente_seleccionado = 'todos'
			$http.get('/reporte/index/proyectos/gerenteid/' + elementoid)
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
	}

	reporte.getData = function (proyecto, index) {
		if (reporte.proyectos[index]['selected']) {
			agregarUsuarios(proyecto)
			agregarTareopersona(proyecto)
		} else {
			borrarUsuarios(proyecto)
			borrarTareopersona(proyecto)
		}
	}

	
	//funciones relacionadas a la vista para el manejo de eventos
	//funciones para los marcar los checkbox de tipo_actividad
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

	//ejecucion de algunas funciones al cargar la pagina
	angular.element(document).ready(function () {
		reporte.getClientes()
		reporte.getGerentes()
		reporte.dias = rellenarDias()  
		reporte.meses = rellenarMeses() 
		reporte.semanas = rellenarSemanas()   
    })

    
	//funciones diversas para una realizar operaciones comunes
    //funcion para obtener el dni de los usuarios de acuerdo a su uid
    function obtenerdni (uid) {
    	dni = ''
    	reporte.usuarios.forEach(function (item) {
    		if (item.uid == uid) {
    			dni = item.dni
    		}
    	})
    	return dni
    }

    //funciones para convertir en cadena uan fecha con formato dd-mm-yyyy y yyyy-mm-dd respectivamente
    function cadenaFecha (fecha) {
		var dd = fecha.getDate()
		var mm = fecha.getMonth() + 1
		var yyyy = fecha.getFullYear()
		
		if(dd < 10) {
    		dd = '0' + dd
		} 

		if(mm < 10) {
    		mm = '0' + mm
		} 

		return dd + '-' + mm + '-' + yyyy
	}

	function cadenaFechaInv (fecha) {
		var dd = fecha.getDate()
		var mm = fecha.getMonth() + 1
		var yyyy = fecha.getFullYear()
		
		if(dd < 10) {
    		dd = '0' + dd
		} 

		if(mm < 10) {
    		mm = '0' + mm
		} 

		return yyyy + '-' + mm + '-' + dd
	}

	function cadenaToFecha (cadena) {
		var fechas = cadena.split("-")
		var f = new Date()
		f.setDate(fechas[0])
		f.setMonth(fechas[1] - 1)
		f.setFullYear(fechas[2])
		return f
	}

	function cadenaToFechaInv (cadena) {
		var fechas = cadena.split("-")
		var f = new Date()
		f.setDate(fechas[2])
		f.setMonth(fechas[1] - 1)
		f.setFullYear(fechas[0])
		return f
	}

	//funciones para agregar o quitar usuarios de la lista segun los proyectos visibles
    function agregarUsuarios (proyecto) {
		$http.get('/reporte/index/usuarios/codigo_prop_proy/' + proyecto)
		.success(function (res) {
			res.forEach(function (item) {
				//inicializar la varible existe
				existe = 0
				item.uid = cambiarFormatoUsuario(item.uid)
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
			if (reporte.usuarios.length != 0) {
				reporte.disabled_children = false
			}
		})
	}

	function borrarUsuarios (proyecto) {
		$http.get('/reporte/index/usuarios/codigo_prop_proy/' + proyecto)
		.success(function (res) {
			res.forEach(function (item) {
				item.uid = cambiarFormatoUsuario(item.uid)
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
			if (reporte.usuarios.length == 0) {
				reporte.disabled_children = true
			}
		})
	}

	//funciones para agregar o eliminar tareopersonas de la tabla segun los proyectos seleccionados
	function agregarTareopersona (codigo_prop_proy) {
		$("#wait").modal()

		//solicitud al servidor por un objeto json que contenga tareopersona
		$http.get('/reporte/index/tareopersonajson/codigo_prop_proy/'+ codigo_prop_proy)
		.success(function (res) {
			res.forEach(function (item) {
				item.horas = rellenarDiasTareopersona(item)
				item.horasxmeses = rellenarMesesTareopersona(item)
				item.horasxsemanas = rellenarSemanasTareopersona(item)
				item.horas_total = sumarHorizontal(item)
				item.uid = cambiarFormatoUsuario(item.uid)
				reporte.tareopersona.push(item)	
			})
			$("#wait").modal('hide')
			reporte.dias_suma = sumarVerticalDias()
			reporte.semanas_suma = sumarVerticalSemanas()
			reporte.meses_suma = sumarVerticalMeses()
			if (reporte.tareopersona.length == 0) {
				reporte.tareopersona_void = true
			} else{
				reporte.tareopersona_void = false
			}
		})
	}

	function borrarTareopersona (codigo_prop_proy) {
		arrayTemp = reporte.tareopersona
		reporte.tareopersona = []
		arrayTemp.forEach(function (item) {
			if (item.codigo_prop_proy != codigo_prop_proy) {
				reporte.tareopersona.push(item)
			}
		})
		reporte.dias_suma = sumarVerticalDias()
		reporte.semanas_suma = sumarVerticalSemanas()
		reporte.meses_suma = sumarVerticalMeses()
	}

	//funciones para el manejo de los dias visibles en la tabla tareopersona
	function rellenarDias () {
		var dias = []
		var inicial = reporte.fecha_from.date
		var ultimo = reporte.fecha_to.date
		var dias_sem = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab']
		var dias_trans = Math.floor((ultimo.getTime() - inicial.getTime()) / (1000 * 60 * 60 * 24)) + 1
		var fecha = inicial
		for (var i = 0; i < dias_trans; i++) {
			var dia = {'cadena': '', 'fecha': new Date()}
			dia.cadena = dias_sem[fecha.getDay()].toString() + ' ' + fecha.getDate()
			dia.fecha = cadenaFechaInv(fecha)
			dias[i] = dia
			fecha.setDate(fecha.getDate() + 1)
		}
		return dias
	}

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

	function rellenarMeses () {
		var meses = []
		var nombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Noviembre', 'Diciembre']
		var mes = ''
		reporte.dias.forEach(function (dia) {
			if (cadenaToFechaInv(dia.fecha).getMonth() != mes) {
				mes = cadenaToFechaInv(dia.fecha).getMonth()
				meses.push(nombres[mes])
			}
		})
		return meses
	}

	function rellenarMesesTareopersona (tareopersona) {
		var horasxmeses = []
		var nombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octobre', 'Noviembre', 'Diciembre']
		for (var i = 0; i < reporte.meses.length; i++) {
			var hora = 0
			var mes1 = reporte.meses[i]
			var inicial = cadenaToFecha(reporte.fecha_from.cadena)
			var ultimo = cadenaToFecha(reporte.fecha_to.cadena)
			tareopersona.data_horas.forEach(function (item) {
				var mes2 = nombres[cadenaToFechaInv(item.fecha_tarea).getMonth()]
				var dia = cadenaToFechaInv(item.fecha_tarea)
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

	function rellenarSemanas () {
		var semanas = []
		var semana = ''
		reporte.dias.forEach(function (dia) {
			if (obtenerSemana(cadenaToFechaInv(dia.fecha)) != semana) {
				semana = obtenerSemana(cadenaToFechaInv(dia.fecha))
				semanas.push('Semana ' + semana)
			}
		})
		return semanas
	}

	function rellenarSemanasTareopersona (tareopersona) {
		var horasxsemanas = []
		for (var i = 0; i < reporte.semanas.length; i++) {
			var hora = 0
			var semana1 = parseInt(reporte.semanas[i].slice(7))
			var inicial = cadenaToFecha(reporte.fecha_from.cadena)
			var ultimo = cadenaToFecha(reporte.fecha_to.cadena)
			tareopersona.data_horas.forEach(function (item) {
				var semana2 = obtenerSemana(cadenaToFechaInv(item.fecha_tarea))
				var dia = cadenaToFechaInv(item.fecha_tarea)
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

	function obtenerSemana (fecha) {
		var consta = [2, 1, 7, 6, 5, 4, 3]
		var dia = fecha.getDate()
		var mes = fecha.getMonth()
		var ano = fecha.getFullYear()
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

	function cambiarFormatoUsuario (uid) {
		var cont = uid.indexOf(".")
		if (cont != -1) {
			var datos = uid.split(".")
			var nombre = datos[0].charAt(0).toUpperCase() + datos[0].slice(1)
			var apellido = datos[1].charAt(0).toUpperCase() + datos[1].slice(1)
			return nombre + ' ' + apellido
		} else {
			return uid.charAt(0).toUpperCase() + uid.slice(1)
		}	
	}

	reporte.cambiarFecha = function () {
		reporte.fecha_from.date = cadenaToFecha(reporte.fecha_from.cadena)
		reporte.fecha_to.date = cadenaToFecha(reporte.fecha_to.cadena)
		reporte.dias = []
		reporte.dias = rellenarDias()
		reporte.meses = []
		reporte.meses = rellenarMeses()
		reporte.semanas = []
		reporte.semanas = rellenarSemanas()
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
}])