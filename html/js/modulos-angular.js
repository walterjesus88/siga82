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
	reporte.clientes = [];
	reporte.unidadminera = [];
	reporte.rango_fecha = ['Fecha de inicio', 'Fecha final'];
	reporte.usuarios = [];
	reporte.dias = ['11', '12', '13', '14'];
	reporte.activo = {'Facturable': true, 'No Facturable': true, 'Administración': true};
	reporte.tipo_todo = true;

	reporte.getTareopersona = function () {
		$("#wait").modal();
		$http.get('/reporte/index/tareopersona/uid/denys.parra/dni/08051678')
		.success(function (res) {
			reporte.tareopersona = res;
			$("#wait").modal('hide');
			console.log(reporte.tareopersona);
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

	reporte.getUsuarios = function () {
		$http.get('/reporte/index/usuarios')
		.success(function (res) {
			reporte.usuarios = res;
		})
	}

	reporte.mostrarTodo = function (id) {
		if (reporte.tipo_todo) {
			reporte.activo = {'Facturable': true, 'No Facturable': true, 'Administración': true};
		} else{
			reporte.activo = {'Facturable': false, 'No Facturable': false, 'Administración': false};
		};
	}

	angular.element(document).ready(function () {
		reporte.getClientes();
		reporte.getUsuarios();
        reporte.getTareopersona();
        
    });
	
}])