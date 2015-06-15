/**
* reporteApp Module
*
* Description
*/
angular.module('reporteApp', []).
controller('mainController', ['$http', function($http){
	reporte = this;
	reporte.cobrabilidad = ['Cobrable al cliente', 'No cobrable al cliente', 'Todo'];
	reporte.rango_fecha = ['Fecha de inicio', 'Fecha final'];
	reporte.formato_proyecto = ['Por nombre', 'Por cliente', 'Unidad minera - Proyecto', 'Todos'];
	reporte.usuarios = ['sdfcxdcv', 'ads', 'qwrsdf'];
}])