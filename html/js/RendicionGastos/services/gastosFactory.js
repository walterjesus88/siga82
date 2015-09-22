app.factory('gastosFactory', ['httpFactory', '$location', '$q',
  function(httpFactory, $location, $q) {

    //     var datos = {
    //     uid: '',
    //     dni: '',
    //     numero_completo: '',
    //     nombre: '',
    //     fecha: '',
    //     monto_total: '',
    //     estado: '',
    // };

    var publico = {

      Gasto: function(numero_completo,nombre,fecha,monto_total,estado) {
        var estados = {
          'B': 'Pendiente',
          'E': 'Enviado',
          'A': 'Aprobado',
          'R': 'Rechazado'
        }
        this.numero_completo = numero_completo;
        this.nombre = nombre;
        this.fecha = fecha;
        this.monto_total = monto_total;
        this.estado = estados[estado];

            // console.log(this.estado);
            this.cambiarEstadoGasto = function() {
              httpFactory.setGasto(this.numero_completo,this.nombre,this.fecha,this.monto_total,this.estado)
              .then(function(data) {
                alert('Estado del gastos cambiado');
              })
              .catch(function(err) {
                alert('No se pudo cambiar el estado');
              })
            }
          },


          setGuardarRendicion: function(numero_completo,nombre,fecha,monto_total,estado) {
            var defered = $q.defer();
            var promise = defered.promise;

            httpFactory.setGuardarRendicion(numero_completo,nombre,fecha,monto_total,estado)
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
        }

        return publico;
      }]);