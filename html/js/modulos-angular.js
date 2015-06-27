/**
* reporteApp Module
*
* Description
*/
angular.module('reporteApp', ['datatables', 'ngResource', 'ngTable']).
controller('mainController', ['$http', '$resource', 'ngTableParams', function($http, $resource, ngTableParams){

	//obtener una referencia del scope para el funcionamiento del data binding
	reporte = this;

	/*$resource('/reporte/index/tareopersona').query().$promise.then(function(persons) {
        reporte.tareopersona = persons;
    });*/

	//inicializando las variables necesarias relacionadas a la vista
	reporte.cobrabilidad = [{'id': 'P', 'text': 'Facturable'}, {'id': 'G', 'text': 'No Facturable'}, {'id': 'A', 'text': 'Administración'}];
	reporte.activo = {'Facturable': true, 'No Facturable': true, 'Administración': true};
	reporte.tipo_todo = true;
	reporte.cliente_seleccionado = 'todos';
	reporte.usuario_seleccionado = 'todos';
	reporte.gerente_seleccionado = 'todos';
	reporte.text_proyectos = 'Seleccione un Cliente o Gerente para mostrar sus proyectos activos.';
	reporte.disabled_children = true;
	reporte.dias = ['11', '12', '13', '14'];
	
	//creando las variables que contendran los datos de respuesta del servidor
	reporte.gerentes = [];
	reporte.clientes = [];
	reporte.unidadminera = [];
	reporte.proyectos = [];
	reporte.usuarios = [];
	reporte.tareopersona = [];

	
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

	reporte.getUnidadMinera = function (seleccionado) {
		$http.get('/reporte/index/unidadminera/clienteid/' + seleccionado)
		.success(function (res) {
			reporte.unidadminera = res;
		})
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
		reporte.fecha_from = fechaActual();
		reporte.fecha_to = fechaActual();
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

    function fechaActual () {
		var f = new Date();
		var dd = f.getDate();
		var mm = f.getMonth() + 1;
		var yyyy = f.getFullYear();
		
		if(dd < 10) {
    		dd = '0' + dd
		} 

		if(mm < 10) {
    		mm = '0' + mm
		} 

		return dd + '-' + mm + '-' + yyyy;
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

	agregarTareopersona = function (codigo_prop_proy) {
		$("#wait").modal();

		/*$http.get('/reporte/index/tareopersonajson/codigo_prop_proy/'+ codigo_prop_proy + '/desde/' + reporte.fecha_from + '/hasta/' + reporte.fecha_to)
		.success(function (res) {
			res.forEach(function (item) {
				reporte.tareopersona.push(item);
			})

			$("#wait").modal('hide');

			reporte.tableParams = new ngTableParams({
		        page: 1, count: 10}, 
		        {
		        	total: reporte.tareopersona.length,
		        	getData: function ($defer, params) {	
		            $defer.resolve(reporte.tareopersona.slice((params.page() - 1) * params.count(), params.page() * params.count()));
		        }
		    })
		})*/

		$http.get('/reporte/index/tareopersonahtml/codigo_prop_proy/'+ codigo_prop_proy + '/desde/' + reporte.fecha_from + '/hasta/' + reporte.fecha_to)
		.success(function (res) {
			$('#container-tareopersona-table').html(res);
			$("#wait").modal('hide');
			$('#tareopersona-table').DataTable();
		})
	}

	borrarTareopersona = function (codigo_prop_proy) {
		arrayTemp = reporte.tareopersona;
		reporte.tareopersona = [];
		arrayTemp.forEach(function (item) {
			if (item.codigo_prop_proy != codigo_prop_proy) {
				reporte.tareopersona.push(item);
			}
		})
	}
}])