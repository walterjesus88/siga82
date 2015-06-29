/**
* reporteApp Module
*
* Description
* Modulo sobre el manejo de los datos de reporte relacionados a tareopersona
*/
/*creacion de modulo con inyeccion de dependencias:
datatables: una extension para manejar los datatables de jquery.
ngtable: un extension alternativa a datatables*/
angular.module('reporteApp', ['datatables', 'ngTable']).
controller('mainController', ['$http', 'ngTableParams', function($http, ngTableParams){

	fecha_inicial_cad = '10-06-2015'
	fecha_final_cad = '02-07-2015'
	fecha_inicial_date = cadenaToFecha(fecha_inicial_cad)
	fecha_final_date = cadenaToFecha(fecha_final_cad)

	//obtener una referencia del scope para el funcionamiento del data binding
	reporte = this

	//inicializando las variables necesarias relacionadas a la vista
	reporte.tipo_actividad = [{'id': 'P', 'text': 'Facturable'}, {'id': 'G', 'text': 'No Facturable'}, {'id': 'A', 'text': 'Administración'}]
	reporte.tipo_activo = {'Todo': true, 'Facturable': true, 'No Facturable': true, 'Administración': true}
	reporte.cliente_seleccionado = 'todos'
	reporte.usuario_seleccionado = 'todos'
	reporte.gerente_seleccionado = 'todos'
	reporte.tareopersona_void = true
	reporte.text_proyectos = 'Seleccione un Cliente o Gerente para mostrar sus proyectos activos.'
	reporte.disabled_children = true
	reporte.fecha_from = {'cadena': fecha_inicial_cad, 'date': fecha_inicial_date}
	reporte.fecha_to = {'cadena': fecha_final_cad, 'date': fecha_final_date}
	reporte.dias = []
		
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
			reporte.gerentes = res;
		})
	}

	reporte.getClientes = function () {
		$http.get('/reporte/index/clientes')
		.success(function (res) {
			reporte.clientes = res;
		})
	}

	reporte.getUnidadMinera = function (seleccionado) {
		$http.get('/reporte/index/unidadminera/clienteid/' + seleccionado)
		.success(function (res) {
			reporte.unidadminera = res;
		})
	}

	reporte.getProyectos = function (elementoid, por) {
		reporte.proyectos = [];
		reporte.usuarios = [];
		reporte.disabled_children = true;
		if (por == 'byCliente') {
			reporte.gerente_seleccionado = 'todos';
			$http.get('/reporte/index/proyectos/clienteid/' + elementoid)
			.success(function (res) {
				if (res.length == 0) {
					reporte.text_proyectos = 'El Cliente seleccionado no tiene proyectos actualmente';
				} else {
					res.forEach(function (proyecto) {
						reporte.text_proyectos = '';
						proyecto['selected'] = false;
						reporte.proyectos.push(proyecto);
					})
				}
			})
		} else if (por == 'byGerente') {
			reporte.cliente_seleccionado = 'todos';
			$http.get('/reporte/index/proyectos/gerenteid/' + elementoid)
			.success(function (res) {
				if (res.length == 0) {
					reporte.text_proyectos = 'El Gerente seleccionado no tiene proyectos actualmente';
				} else {
					res.forEach(function (proyecto) {
						reporte.text_proyectos = '';
						proyecto['selected'] = false;
						reporte.proyectos.push(proyecto);
					})
				}
			})
		}		
	}

	reporte.getData = function (proyecto, index) {
		if (reporte.proyectos[index]['selected']) {
			agregarUsuarios(proyecto);
			agregarTareopersona(proyecto);
		} else {
			borrarUsuarios(proyecto);
			borrarTareopersona(proyecto);
		}
	}

	
	//funciones relacionadas a la vista para el manejo de eventos
	//funciones para los marcar los checkbox de tipo_actividad
	reporte.tipoActivoTodo = function (id) {
		if (reporte.tipo_activo.Todo) {
			reporte.tipo_activo = {'Todo': true, 'Facturable': true, 'No Facturable': true, 'Administración': true};
		} else{
			reporte.tipo_activo = {'Todo': false, 'Facturable': false, 'No Facturable': false, 'Administración': false};
		}
	}

	reporte.tipoActivoHijo = function () {
		if(reporte.tipo_activo['Facturable'] == true && reporte.tipo_activo['No Facturable']  == true && reporte.tipo_activo['Administración'] == true) {
			reporte.tipo_activo.Todo = true;
		} else {
			reporte.tipo_activo.Todo = false;
		}
	}

	//ejecucion de algunas funciones al cargar la pagina
	angular.element(document).ready(function () {
		reporte.getClientes()
		reporte.getGerentes()
		reporte.dias = rellenarDias()       
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
		var dd = fecha.getDate();
		var mm = fecha.getMonth() + 1;
		var yyyy = fecha.getFullYear();
		
		if(dd < 10) {
    		dd = '0' + dd
		} 

		if(mm < 10) {
    		mm = '0' + mm
		} 

		return dd + '-' + mm + '-' + yyyy;
	}

	function cadenaFechaInv (fecha) {
		var dd = fecha.getDate();
		var mm = fecha.getMonth() + 1;
		var yyyy = fecha.getFullYear();
		
		if(dd < 10) {
    		dd = '0' + dd
		} 

		if(mm < 10) {
    		mm = '0' + mm
		} 

		return yyyy + '-' + mm + '-' + dd;
	}

	function cadenaToFecha (cadena) {
		var fechas = cadena.split("-")
		var f = new Date()
		f.setDate(fechas[0])
		f.setMonth(fechas[1] - 1)
		f.setFullYear(fechas[2])
		return f
	}

	//funciones para agregar o quitar usuarios de la lista segun los proyectos visibles
    function agregarUsuarios (proyecto) {
		$http.get('/reporte/index/usuarios/codigo_prop_proy/' + proyecto)
		.success(function (res) {
			res.forEach(function (item) {
				//inicializar la varible existe
				existe = 0;
				//verificar si usuario existe en la lista
				for (var i = 0; i < reporte.usuarios.length; i++) {
					if (item.uid == reporte.usuarios[i].uid) {
						existe = 1;
					}
				}
				//si existe agregar el numero de proyectos en 1, sino aumentarlo al array
				if (existe == 1) {
					for (var i = 0; i < reporte.usuarios.length; i++) {
						if (item.uid == reporte.usuarios[i].uid) {
							reporte.usuarios[i]['num_pro'] = reporte.usuarios[i]['num_pro'] + 1;
						}
					}
				} else {
					item['num_pro'] = 1;
					reporte.usuarios.push(item);
				}
			})
			if (reporte.usuarios.length != 0) {
				reporte.disabled_children = false;
			}
		})
	}

	function borrarUsuarios (proyecto) {
		$http.get('/reporte/index/usuarios/codigo_prop_proy/' + proyecto)
		.success(function (res) {
			res.forEach(function (item) {
				//disminuir en 1 el num_pro de los elementos que aparecen en la lista
				for (var i = 0; i < reporte.usuarios.length; i++) {
					if (item.uid == reporte.usuarios[i].uid) {
						reporte.usuarios[i]['num_pro'] = reporte.usuarios[i]['num_pro'] - 1;
					}
				}
			})
			//eliminar a todos los usuarios que tengan 0 proyectos
			arrayTemp = reporte.usuarios;
			reporte.usuarios = [];
			for (var i = 0; i < arrayTemp.length; i++) {
				if (parseInt(arrayTemp[i]['num_pro']) != 0) {
					reporte.usuarios.push(arrayTemp[i]);
				}
			};
			if (reporte.usuarios.length == 0) {
				reporte.disabled_children = true;
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
				reporte.tareopersona.push(item)
			})
			$("#wait").modal('hide')

			if (reporte.tareopersona.length == 0) {
				reporte.tareopersona_void = true
			} else{
				reporte.tareopersona_void = false
			}
		})

		//solicitud al servidor de una pagina web que contenga la tabla de tareopersona
		/*$http.get('/reporte/index/tareopersonahtml/codigo_prop_proy/'+ codigo_prop_proy + '/desde/' + reporte.fecha_from + '/hasta/' + reporte.fecha_to)
		.success(function (res) {
			$('#container-tareopersona-table').html(res);
			$("#wait").modal('hide');
			$('#tareopersona-table').DataTable();
		})*/
	}

	function borrarTareopersona (codigo_prop_proy) {
		arrayTemp = reporte.tareopersona;
		reporte.tareopersona = [];
		arrayTemp.forEach(function (item) {
			if (item.codigo_prop_proy != codigo_prop_proy) {
				reporte.tareopersona.push(item);
			}
		})
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
			hora = '0'
			tareopersona.data_horas.forEach(function (item) {
				if (reporte.dias[i].fecha == item.fecha_tarea) {
					hora = item.h_real
				}
			})
			horas.push(hora)
		}
		return horas	
	}

	reporte.cambiarFecha = function () {
		reporte.fecha_from.date = cadenaToFecha(reporte.fecha_from.cadena)
		reporte.fecha_to.date = cadenaToFecha(reporte.fecha_to.cadena)
		reporte.dias = rellenarDias()
		arrayTemp = []
		reporte.tareopersona.forEach(function (item) {
			item.horas = rellenarDiasTareopersona(item)
			arrayTemp.push(item)
		})
		reporte.tareopersona = arrayTemp
	}
}])