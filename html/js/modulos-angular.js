/**
* reporteApp Module
*
* Description
*/
angular.module('reporteApp', []).
controller('mainController', ['$http', function($http){

	//obtener una referencia del scope para el funcionamiento del data binding
	reporte = this;
	
	//inicializando las variables necesarias relacionadas a la vista
	reporte.cobrabilidad = [{'id': 'P', 'text': 'Facturable'}, {'id': 'G', 'text': 'No Facturable'}, {'id': 'A', 'text': 'Administración'}];
	reporte.rango_fecha = ['Fecha de inicio', 'Fecha final'];
	reporte.dias = ['11', '12', '13', '14'];
	reporte.activo = {'Facturable': true, 'No Facturable': true, 'Administración': true};
	reporte.tipo_todo = true;
	reporte.cliente_seleccionado = 'todos';
	reporte.usuario_seleccionado = 'todos';
	reporte.gerente_seleccionado = 'todos';
	reporte.text_proyectos = 'Seleccione un Cliente o Gerente para mostrar sus proyectos activos.';
	reporte.select_usuarios = true;
	reporte.not_tareopersona = true;
	
	//creando las variables que contendran los datos de respuesta del servidor
	reporte.gerentes = [];
	reporte.clientes = [];
	reporte.unidadminera = [];
	reporte.proyectos = [];
	reporte.usuarios = [];
	reporte.tareopersona = [];

	
	//funciones que realizaran peticiones al servidor para obtener rellenar los array
	reporte.getTareopersona = function (uid) {
		$("#wait").modal();
		if (uid == 'todos') {
			uid = '';
		};
		dni = obtenerdni(uid);
		reporte.not_tareopersona = false;
		$http.get('/reporte/index/tareopersona/uid/'+ uid +'/dni/' + dni)
		.success(function (res) {
			reporte.tareopersona = res;
			if (reporte.tareopersona.length == 0) {
				reporte.not_tareopersona = true;
			};
			$("#wait").modal('hide');
		})
	}

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

	reporte.getProyectos = function (elementoid, por) {
		reporte.proyectos = [];
		reporte.usuarios = [];
		reporte.select_usuarios = true;
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

	reporte.getUnidadMinera = function (seleccionado) {
		$http.get('/reporte/index/unidadminera/clienteid/' + seleccionado)
		.success(function (res) {
			reporte.unidadminera = res;
		})
	}

	reporte.getUsuarios = function (proyecto, index) {
		if (reporte.proyectos[index]['selected']) {
			agregarUsuarios(proyecto);
		} else {
			borrarUsuarios(proyecto);
		}
	}

	
	//funciones relacionadas a la vista para el manejo de eventos
	reporte.mostrarTodo = function (id) {
		if (reporte.tipo_todo) {
			reporte.activo = {'Facturable': true, 'No Facturable': true, 'Administración': true};
		} else{
			reporte.activo = {'Facturable': false, 'No Facturable': false, 'Administración': false};
		}
	}

	reporte.notTodo = function () {
		if(reporte.activo['Facturable'] == true && reporte.activo['No Facturable']  == true && reporte.activo['Administración']  && true) {
			reporte.tipo_todo = true;
		} else {
			reporte.tipo_todo = false;
		}
	}

	//ejecucion de algunas funciones al cargar la pagina
	angular.element(document).ready(function () {
		reporte.getClientes();
		reporte.getGerentes();
		//reporte.getUsuarios();
        //reporte.getTareopersona('');
        
    });

    
	//funciones diversas para una realizar operaciones comunes
    function obtenerdni (uid) {
    	dni = '';
    	reporte.usuarios.forEach(function (item) {
    		if (item.uid == uid) {
    			dni = item.dni;
    		};
    	})
    	return dni;
    }

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
				reporte.select_usuarios = false;
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
				reporte.select_usuarios = true;
			}
		})
	}
	
}])