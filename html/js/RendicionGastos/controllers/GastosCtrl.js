app.controller('GastosCtrl', ['$scope','httpFactory', 'gastosFactory', '$modal',
	function($scope,httpFactory, gastosFactory,$modal) {
		var vg = this;
		var estado_actual = 'B';

		var listarGastos = function(estado) {
			estado_actual = estado;
			httpFactory.getGastos(estado)
			.then(function(data) {
				vg.gastos = [];
				data.forEach(function(item) {
					gasto = new gastosFactory.Gasto(item.numero_completo,item.nombre,item.fecha,item.monto_total,item.estado);
					vg.gastos.push(gasto);
				});
			console.log(data);
			})

			.catch(function(err) {
				vg.gastos= [];
			});
		};

		// vg.estadogasto = [
		// {value: 'B', text: 'Pendiente'},
		// {value: 'E', text: 'Enviado'},
		// {value: 'A', text: 'Aprobado'},
		// {value: 'R', text: 'Rechazado'},
		// ];

		// vg.showEstadoGasto = function(gasto) {
		// 	var selected = [];
		// 	if(gasto.estado) {
		// 		selected = $filter('filter')(vg.estadogasto, {value: gasto.estado});
		// 	}
		// 	return selected.length ? selected[0].text : 'Not set';
		// };

		listarGastos(estado_actual);

		vg.cargarGastos = function(estado) {
			listarGastos(estado);
		};


		vg.AgregarGastoRendicion = function() {
      vg.inserted = {
      //codigo_prop_proy:vg.proyectop.codigo_prop_proy,
      //proyectoid:vg.proyectop.codigo,   
        id: vg.rendiciongastos.length+1,
        numero_completo: null,
        nombre: null,
        fecha: null ,
        monto_total: null ,
        estado: 'E' ,
      };
    vg.rendiciongastos.push(vg.inserted);
};



	}]);