angular.module('angularSuma',['ngSanitize'])
.controller('sumaController', function ($scope, $http) {
	

	 $scope.mostraraviso = function(){
	 	var dateObj = new Date();
	    var dateFrom = $.datepicker.formatDate('mm/dd/yy', dateObj);
	    var weekfrom = $.datepicker.iso8601Week(new Date(dateFrom));
	    var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
	    var day_select = diasSemana[dateObj.getDay()];
	    for (i = 0; i < diasSemana.length; i++) { 
	        if (diasSemana[i] == day_select) {
	            if (i == 0) {
	                var num_day = 6;
	                
	            } else {
	                var num_day = i - 1;
	            };
	        };
	    };
	    var f = new Date(); 
	    var fech=f.getFullYear()+"-"+(f.getMonth()+1) +"-"+f.getDate() ;
	         $("#fechasemana").text( fech );
	      
	    fecha_inicio = mostrarFecha(dateObj,-num_day);

	    $http.get("/timesheet/index/registro/fecha/" + fecha_inicio) 
	    	.success(function(data){
	    		$scope.registro=data;
	    		console.log(data);
	    	})
	    	.error(function(data){
	    		console.log('Error'+data);
	    	});
	     
	 }


	
		
		scope = this;
		scope.elementos = [];
		scope.resultado = 0;
		
		scope.agregar = function() {
			scope.elementos.push(0);
		}

		scope.sumar = function() {
			scope.resultado = 0;
			for (var i = 0; i<scope.elementos.length; i++) {
				scope.resultado = scope.resultado + parseInt(scope.elementos[i]);
			};
		}
	


});