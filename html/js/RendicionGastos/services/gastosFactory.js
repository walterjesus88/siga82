app.factory('gastosFactory', ['httpFactory', '$location', '$q',
  function(httpFactory, $location, $q) {

    var datos = {
      uid: '',
      dni: '',
      numero_completo: '',
      numero:'',
      nombre: '',
      fecha: '',
      monto_total: '',
      estado: '',
    };

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

    this.verInformacion = function() {

        console.log("verInformacion");
        console.log(item);
        console.log(this.numero);
        $location.path("/rendir/numero/" + item.numero);

      }
    },


    setGuardarRendicion: function(numero_completo,nombre,fecha,estado) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setGuardarRendicion(numero_completo,nombre,fecha,estado)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
        console.log(data);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },


    getDatosGastos: function(numero) {
      // console.log("gastosFactory "+numero);
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.getGastosById(numero)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },


  }

  return publico;
}]);