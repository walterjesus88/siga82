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
      gastoid:'',
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

        // console.log("verInformacion");
        // console.log(item);
        // console.log(this.numero);
        // console.log(this.fecha);
        // console.log(this.estado);
        // $location.path("/rendir/numero/" + item.numero);
        $location.path("/rendir/fecha/" + item.fecha +"/numero/"+item.numero);

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


    setGuardarGastos: function(codigo_prop_proy,proyectoid,revision,descripcion,gastoid,bill_cliente,reembolsable,fecha_factura,num_factura,moneda,proveedor,monto_igv,otro_impuesto,igv,monto_total,numero,fecha) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setGuardarGastos(codigo_prop_proy,proyectoid,revision,descripcion,gastoid,bill_cliente,reembolsable,fecha_factura,num_factura,moneda,proveedor,monto_igv,otro_impuesto,igv,monto_total,numero,fecha)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
        // console.log("guardado " + proyectoid);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },


    setCliente: function(listaclientes) {
      datos.listaclientes = listaclientes;
      // console.log("gastosFactory " + listagastos)
    },

    setProyecto: function(listaproyectos) {
      datos.listaproyectos = listaproyectos;
      // console.log("gastosFactory " + listagastos)
    },

    setTipoGasto: function(listagastos) {
      datos.listagastos = listagastos;
      // console.log("gastosFactory " + listagastos)
    },


    formatoFechas : function(fecha_factura)
    {
      fecha_factura = new Date(fecha_factura);
      day=fecha_factura.getDate();
      month=fecha_factura.getMonth()+1;
      year=fecha_factura.getFullYear();

      if (month.toString().length < 2)
      {
        month = '0' + month;
      }
      if (day.toString().length < 2)
      {
        day = '1' + day;
      }

      fecha_factura=year+"-"+month+"-"+day;
      return fecha_factura;
    },

  }
  return publico;
}]);