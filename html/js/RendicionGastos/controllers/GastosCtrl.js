app.controller('GastosCtrl', ['$scope','httpFactory', 'gastosFactory', '$modal', '$location', '$routeParams',
	function($scope,httpFactory, gastosFactory, $modal, $location, $routeParams) {

		var vg = this;
		var estado_actual = 'B';
		vg.alerts = [];
		vg.gastos=[];
//funcion para obtener las rendiciones del servidor
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

		// console.log(this);
	//carga inicial de las rendiciones con estado activo
		listarGastos(estado_actual);

	//metodo para cargar las rendiciones de los diferentes estados
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


	// vrg.showEstadoproyecto = function(proyecto) {
 //    var selected = [];
 //    if(proyecto.estado) {
 //      selected = $filter('filter')(vrg.estadoproyecto, {value: proyecto.estado});
 //    }
 //    return selected.length ? selected[0].text : 'Not set';
 //  };
	}]);