app.controller('GastosCtrl', ['$scope','httpFactory', 'gastosFactory', '$modal',
	function($scope,httpFactory, gastosFactory, $modal) {
		var vg = this;
		var estado_actual = 'B';
		vg.alerts = [];
    	vg.gasto=[];

		var listarGastos = function(estado) {
			estado_actual = estado;
			httpFactory.getGastos(estado)
			.then(function(data) {
				vg.gastos = [];
				data.forEach(function(item) {
					gasto = new gastosFactory.Gasto(item.numero_completo,item.nombre,item.fecha,item.monto_total,item.estado);
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

}]);