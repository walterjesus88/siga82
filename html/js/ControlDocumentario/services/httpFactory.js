/*servicio Factory para el manejo de solicitudes http get, post, update y
delete al servidor*/

app.factory('httpFactory', ['$http', '$q', function($http, $q) {

  var url_json = '/controldocumentario/json/';
  var url_print = '/controldocumentario/print/';
  var url_ent = '/controldocumentario/entregable/';
  var url_tran = '/controldocumentario/transmittal/';

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
    getCarpetas: function() {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'carpetas')
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
    deleteContacto: function(clienteid, contactoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'eliminarcontacto/clienteid/' + clienteid +
      '/contactoid/' + contactoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
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
      $http.get(url_tran + 'correlativotransmittal/proyectoid/' + proyectoid)
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
    getTransmittal: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_tran + 'ultimotransmittal/proyectoid/' + proyectoid)
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
      $http.post(url_tran + 'guardarconfiguraciontransmittal/codificacion/' +
      datos.codificacion + '/correlativo/' + datos.correlativo + '/formato/' +
      datos.formato + '/tipoenvio/' + datos.tipo_envio + '/clienteid/' +
      datos.clienteid + '/proyectoid/' + datos.proyecto + '/controldocumentario/' +
      datos.control_documentario + '/atencion/' + datos.atencion + '/diasalerta/'
      + datos.dias_alerta + '/tipoproyecto/' + datos.tipo_proyecto + '/modoenvio/' +
      datos.modo_envio)
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
    getEntregables: function(proyectoid, estado, clase) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_ent + 'entregables/proyectoid/' + proyectoid +
      '/estado/' + estado + '/clase/' + clase)
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
      $http.post(url_ent + 'actualizarcodigoanddes/entregableid/' + entregableid +
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
      $http.post(url_ent + 'actualizarcodigocliente/entregableid/' + entregableid +
      '/codigocliente/' + codigo_cliente)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setTipoEntregable: function(entregableid, tipo) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_ent + 'actualizartipoentregable/entregableid/' +
      entregableid + '/tipo/' + tipo)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setDisciplina: function(entregableid, disciplina) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_ent + 'actualizardisciplina/entregableid/' + entregableid +
      '/disciplina/' + disciplina)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setDescripcion: function(entregableid, descripcion) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_ent + 'actualizardescripcion/entregableid/' + entregableid +
      '/descripcion/' + descripcion)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setRevisionEntregable: function(entregableid, revision) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_ent + 'actualizarrevisionentregable/entregableid/' +
      entregableid + '/revision/' + revision)
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
      $http.post(url_json + 'setcontacto/clienteid/' + clienteid +
      '/contactoid/' + contacto.id + '/nombre/' + contacto.atencion + '/area/' +
      contacto.area + '/correo/' + contacto.correo)
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
      $http.post(url_tran + 'guardardetalle/codigo/' + datos.codigo + '/tipoenvio/' +
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
      $http.post(url_json + 'guardartiposdeenvio/empresa/' + tipo.empresa + '/abrev/' +
      tipo.abrev + '/emitidopara/' + tipo.emitido_para)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    deleteTipo: function(tipo) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.delete(url_json + 'eliminartipodeenvio/empresa/' + tipo.empresa +
      '/abrev/' + tipo.abrev)
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
    },
    createPdfCarpetas: function() {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_print + 'imprimircarpetas/')
      .success(function(data) {
        defered.resolve(data)
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    createPdfEdt: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_print + 'imprimiredt/proyectoid/' + proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    createPdfRT: function(proyectoid, estado, clase) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_print + 'imprimirreportetransmittal/proyectoid/' + proyectoid +
      '/estado/' + estado + '/clase/' + clase)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setEntregable: function(ent) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_ent + 'guardarentregable/entregableid/' + ent.codigo +
      '/proyectoid/' + ent.proyectoid +
      '/tipo/' + ent.tipo + '/disciplina/' + ent.disciplina + '/codigoanddes/' +
      ent.codigo_anddes + '/codigocliente/' + ent.codigo_cliente + '/descripcion/' +
      ent.descripcion + '/revision/' + ent.revision + '/clase/' + ent.clase)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    deleteEntregable: function(entregableid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.delete(url_ent + 'eliminarentregable/entregableid/' + entregableid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getDetallesinRespuesta: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_tran + 'detallessinrespuesta/proyectoid/' + proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setRespuesta: function(respuesta) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_tran + 'guardarrespuesta/detalleid/' + respuesta.detalleid +
      '/respuestatransmittal/' + respuesta.transmittal + '/codigoanddes/' +
      respuesta.codigoanddes + '/codigocliente/' + respuesta.codigocliente +
      '/descripcion/' + respuesta.descripcion + '/revision/' + respuesta.revision +
      '/emitido/' + respuesta.emitido + '/fecha/' + respuesta.fecha)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    createPdfTR: function(transmittal, correlativo) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_print + 'imprimirtransmittal/transmittal/' + transmittal +
      '/correlativo/' + correlativo)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(data);
      });
      return promise;
    },
    getRespuestas: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_tran + 'obtenerrespuestas/proyectoid/' + proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    getDatosContacto: function(clienteid, contactoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_json + 'obtenerdatoscontacto/clienteid/' + clienteid +
      '/contactoid/' + contactoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
    setModoEnvio: function(transmittal, correlativo, modo) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_tran + 'actualizarmodoenvio/transmittal/' + transmittal +
      '/correlativo/' + correlativo + '/modo/' + modo)
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
