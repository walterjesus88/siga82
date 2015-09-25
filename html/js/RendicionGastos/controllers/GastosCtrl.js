app.controller('GastosCtrl', ['$scope','httpFactory', 'gastosFactory', '$modal', '$location',
	function($scope,httpFactory, gastosFactory, $modal, $location) {
		var vg = this;
		var estado_actual = 'B';
		vg.alerts = [];
    	vg.gastos=[];

		var listarGastos = function(estado) {
			estado_actual = estado;
			httpFactory.getGastos(estado)
			.then(function(data) {
				vg.gastos = [];
				data.forEach(function(item) {
					gasto = new gastosFactory.Gasto(item);
					vg.gastos.push(gasto);
				});

			})

			.catch(function(err) {
				vg.gastos= [];
			});
		};


		listarGastos(estado_actual);

		vg.cargarGastos = function(estado) {
			listarGastos(estado);
		};

		vg.AgregarGastoRendicion = function() {

			var modalInstance = $modal.open({
				animation: false,
				controller: 'ModalRendicionCtrl',
				controllerAs: 'mr',
				templateUrl: '/rendiciongastos/index/modalrendicion',
				size: 'md',
			});

	};

	  vg.verInformacion = function(index) {
    $location.path("/rendirgastos/numero/" + vg.gastos[index].numero);
    console.log("GastosCtrl")
  }

}]);