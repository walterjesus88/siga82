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
		reporte.usuarios = [];
		reporte.select_usuarios = true;
		if (reporte.proyectos[index]['selected']) {
			$http.get('/reporte/index/usuarios/codigo_prop_proy/' + proyecto)
			.success(function (res) {
				reporte.usuarios = res;
				if (reporte.usuarios.length != 0) {
					reporte.select_usuarios = false;
				}
			})
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
	
}])