/**
* reporteApp Module
*
* Description
*/
angular.module('reporteApp', []).
controller('mainController', ['$http', function($http){

	reporte = this;
	reporte.tareopersona = [];
	reporte.cobrabilidad = [{'id': 'P', 'text': 'Facturable'}, {'id': 'G', 'text': 'No Facturable'}, {'id': 'A', 'text': 'Administración'}];
	reporte.rango_fecha = ['Fecha de inicio', 'Fecha final'];
	reporte.formato_proyecto = ['Por nombre', 'Por cliente', 'Unidad minera - Proyecto', 'Todos'];
	reporte.usuarios = [];
	reporte.dias = ['11', '12', '13', '14'];
	reporte.activo = {'Facturable': true, 'No Facturable': true, 'Administración': true};

	reporte.getTareopersona = function () {
		$http.get('/reporte/index/tareopersona')
		.success(function (res) {
			//estoy cortando el array de respuesta porque tiene > de 6000 registro y necesito menos para
			//las pruebas
			reporte.tareopersona = res;	
		})
	}

	reporte.getUsuarios = function () {
		$http.get('/reporte/index/usuarios')
		.success(function (res) {
			reporte.usuarios = res.slice(0, 20);
		})
	}

	reporte.tipo_todo = true;

	reporte.mostrarTodo = function (id) {
		if (reporte.tipo_todo) {
			reporte.activo = {'Facturable': true, 'No Facturable': true, 'Administración': true};
		} else{
			reporte.activo = {'Facturable': false, 'No Facturable': false, 'Administración': false};
		};
	}

	angular.element(document).ready(function () {
        reporte.getTareopersona();
        reporte.getUsuarios();
    });
	
}])