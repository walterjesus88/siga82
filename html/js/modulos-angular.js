/**
* reporteApp Module
*
* Description
*/
angular.module('reporteApp', []).
controller('mainController', ['$http', function($http){

	Array.prototype.unique = function(a){
  		return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
	});

	reporte = this;
	reporte.datos = [];
	reporte.tareopersona = [];
	reporte.cobrabilidad = ['Cobrable al cliente', 'No cobrable al cliente', 'Todo'];
	reporte.rango_fecha = ['Fecha de inicio', 'Fecha final'];
	reporte.formato_proyecto = ['Por nombre', 'Por cliente', 'Unidad minera - Proyecto', 'Todos'];
	reporte.usuarios = [];
	reporte.dias = ['11', '12', '13', '14'];

	reporte.getTareopersona = function () {
		$http.get('/reporte/index/tareopersona')
		.success(function (data) {
			//estoy cortando el array de respuesta porque tiene > de 6000 registro y lo pone lento
			reporte.datos = data.slice(0, 20);
			reporte.datos.forEach(function (item) {
				reporte.usuarios.push(item['uid']);
				reporte.tareopersona.push(item);
			})	
		})
		console.log(reporte.tareopersona);
			
	}

	angular.element(document).ready(function () {
        reporte.getTareopersona();
    });
	
}])