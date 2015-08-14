/*servicio Factory para el manejo de solicitudes http get, post, update y
delete al servidor*/

app.factory('httpFactory', ['$http', '$q', function($http, $q) {

  var url_json = '/controldocumentario/json/';
  var url_print = '/controldocumentario/print/';

  var publico = {
    getIntegrantes: function(){
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'integrantes')
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getProyectos: function(estado) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'listaproyectos/estado/' + estado)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getClientes: function() {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'clientes')
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getTiposProyecto: function() {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'tipoproyecto')
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getContactosByCliente: function(clienteid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'contactos/clienteid/' + clienteid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(data);
      });
      return promise;
    },
    getTiposEnvio: function() {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'tipoenvio/')
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getEmisionesByTipo: function(tipo) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'emisiones/tipo/' + tipo)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getProyectoById: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'proyecto/proyectoid/' + proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getCorrelativoTransmittal: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'correlativotransmittal/proyectoid/' + proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err){
        defered.reject(err);
      });
      return promise;
    },
    setControlDocumentario: function(proyectoid, control_documentario) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_json + 'cambiarcontroldocumentario/proyectoid/' +
      proyectoid + '/controldocumentario/' + control_documentario)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setConfiguracionTransmittal: function(datos) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_json + 'guardarconfiguraciontransmittal/codificacion/' +
      datos.codificacion + '/correlativo/' + datos.correlativo + '/formato/' +
      datos.formato + '/tipoenvio/' + datos.tipo_envio + '/clienteid/' +
      datos.clienteid + '/proyectoid/' + datos.proyecto + '/controldocumentario/' +
      datos.control_documentario + '/atencion/' + datos.atencion + '/diasalerta/'
      + datos.dias_alerta + '/tipoproyecto/' + datos.tipo_proyecto)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getEdts: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'edt/proyectoid/' + proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getEntregables: function(proyectoid, estado) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'entregables/proyectoid/' + proyectoid +
      '/estado/' + estado)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setCodigoAnddes: function(entregableid, codigo_anddes) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_json + 'actualizarcodigoanddes/entregableid/' + entregableid +
      '/codigoanddes/' + codigo_anddes)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setCodigoCliente: function(entregableid, codigo_cliente) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_json + 'actualizarcodigocliente/entregableid/' + entregableid +
      '/codigocliente/' + codigo_cliente)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setContacto: function(clienteid, contacto) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_json + 'agregarcontacto/clienteid/' + clienteid + '/nombre/' +
      contacto.atencion + '/area/' + contacto.area + '/correo/' + contacto.correo)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setDetalleTransmittal: function(datos) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_json + 'guardardetalle/codigo/' + datos.codigo + '/tipoenvio/' +
      datos.tipo_envio + '/revision/' +
      datos.revision + '/estadorevision/' + datos.estado_revision + '/transmittal/' +
      datos.transmittal + '/correlativo/' + datos.correlativo + '/emitido/' +
      datos.emitido + '/fecha/' + datos.fecha + '/estado/' + datos.estado)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getEmisiones: function() {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'tiposdeenvio/')
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setTipoEnvio: function(tipo) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'guardartiposdeenvio/empresa/' + tipo.empresa + '/abrev/' +
      tipo.abrev + '/emitidopara/' + tipo.emitido_para)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    createPdfProyectos: function(estado) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_print + 'imprimirproyectos/estado/' + estado)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    }
  }

  return publico;
}]);
