app.factory('gastosFactory', ['httpFactory', '$q',
  function(httpFactory, $q) {

/*    var datos = {
      uid: '',
      dni: '',
      numero_completo: '',
      numero:'',
      nombre: '',
      fecha: '',
      monto_total: '',
      estado: '',
    };*/

    var publico = {

      Gasto: function(item) {
        var estados = {
          'B': 'Pendiente',
          'E': 'Enviado',
          'A': 'Aprobado',
          'R': 'Rechazado'
        }
        this.numero = item.numero;
        this.numero_completo = item.numero_completo;
        this.nombre = item.nombre;
        this.fecha = item.fecha;
        this.monto_total = item.monto_total;
        this.estado = estados[item.estado];

            // console.log(this.estado);
            this.cambiarEstadoGasto = function() {
              httpFactory.setGasto(this.numero,this.numero_completo,this.nombre,this.fecha,this.monto_total,this.estado)
              .then(function(data) {
                alert('Estado del gastos cambiado');
              })
              .catch(function(err) {
                alert('No se pudo cambiar el estado');
              })
            }
    },


    setGuardarRendicion: function(numero_completo,nombre,fecha,estado) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setGuardarRendicion(numero_completo,nombre,fecha,estado)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
        // console.log(data);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },


    getDatosRendicion: function(numero) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.getGastosById(numero)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
        console.log("gastosFactory");
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },






  }

  return publico;
}]);