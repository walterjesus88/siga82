/*servicio Factory que simula una clase Proyecto con include de httpFactory para
poder actualizar el control documentario y $location para redigir a las vistas
de informacion, generar transmittal y generar reporte*/
app.factory('proyectoFactory', ['httpFactory', '$location', '$q',
function(httpFactory, $location, $q) {

  var datos = {
    codigo_prop_proy:'',
    codigo: '',
    nombre: '',
    clienteid: '',
    cliente: '',
    unidad_minera: '',
    estado: '',
    fecha_inicio: '',
    fecha_cierre: '',
    control_documentario: '',
    descripcion: '',
    tipo_proyecto: '',
    logo_cliente: ''
  };



  var publico = {
    Proyecto: function(codigo_prop_proy,codigo, cliente, nombre, gerente, control_proyecto,
      control_documentario, estado) {
      var estados = {
        'A': 'Activo',
        'P': 'Paralizado',
        'C': 'Cerrado',
        'CA': 'Cancelado'
      }

      //this.codigo=codigo_prop_proy;  
      //console.log("hola");
      //console.log(this.codigo_prop_proy);

      this.codigo = codigo;
      this.cliente = cliente;
      this.nombre = nombre;
      this.gerente = gerente.changeFormat();
      this.control_proyecto = control_proyecto.changeFormat();
      this.control_documentario = control_documentario;
      this.estado = estados[estado];

      this.cambiarControlDocumentario = function() {
        httpFactory.setControlDocumentario(this.codigo, this.control_documentario)
        .then(function(data) {
          alert('Control Documentario cambiado');
        })
        .catch(function(err) {
          alert('No se pudo cambiar el Control Documentario');
        })
      }

      this.verInformacion = function() {
        //configuracionTransmittal.setProyecto(proyectoid);
        console.log("verInformacion");
        console.log(this.codigo);
        //console.log(this.codx);

        $location.path("/detalle/proyecto/" +this.codigo);
        // +'/codigo/'+this.codigo_prop_proy        
      }
    },

    getDatosProyecto: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.getProyectoById(proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    getVerCronogramaxActivo: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.getCronogramaxActivo(proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    getDatosProyectoxCronograma: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.getProyectoxCronograma(proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    getDatosProyectoxPerfomance: function(proyectoid,revision) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.getProyectoxPerfomance(proyectoid,revision)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setActualizarDatosxPerfomance: function(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,codigo_cronograma,codigo_performance,porcentaje_performance,fecha_calculo_performance,proyectoid,revision_cronograma,fecha_ingreso_performance)
    {
      var defered = $q.defer();
      var promise = defered.promise;
       //console.log("esoty en setActualizarDatosxPerfomance");
       //console.log(uperformance);

      httpFactory.setDatosxPerfomance(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,
        codigo_cronograma,codigo_performance,porcentaje_performance,fecha_calculo_performance,proyectoid,revision_cronograma,
        fecha_ingreso_performance,fecha_performance)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setActualizarPerformance: function(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,codigo_cronograma,codigo_performance,fecha_calculo_performance,proyectoid,revision_cronograma,fecha_ingreso_performance,revision_propuesta,costo_real,horas_real,fecha_comienzo_real,fecha_fin_real,fecha_fin,fecha_comienzo,porcentaje_calculo,nivel_esquema,predecesoras,sucesoras,costo_presupuesto,duracion) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.setModificarxPerformance(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,codigo_cronograma,codigo_performance,fecha_calculo_performance,proyectoid,revision_cronograma,fecha_ingreso_performance,revision_propuesta,costo_real,horas_real,fecha_comienzo_real,fecha_fin_real,fecha_fin,fecha_comienzo,porcentaje_calculo,nivel_esquema,predecesoras,sucesoras,costo_presupuesto,duracion)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },


    /****EDT**/////
    getDatosxEDT: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
     

      httpFactory.getDatosxProyectoxEDT(proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setDatosxGuardarxEDT: function(codigoedt,nombre,descripcion,codigo_prop_proy,codigo) {
      var defered = $q.defer();
      var promise = defered.promise;
     
      
      httpFactory.setDatosxGrabarxEDT(codigoedt,nombre,descripcion,codigo_prop_proy,codigo)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setDatosxModificarxEDT: function(codigoedt,codigoproyecto,proyectoid,codigoedtmodificado,nombremodificado,descripcionmodificado) {
      var defered = $q.defer();
      var promise = defered.promise;     
      
      httpFactory.setDatosxModificarxEDT(codigoedt,codigoproyecto,proyectoid,codigoedtmodificado,nombremodificado,descripcionmodificado)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setEliminarxEDT: function(codigoedt,codigoproyecto,proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;     
      
      httpFactory.setDatosxEliminarxEDT(codigoedt,codigoproyecto,proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    ////////////////////  F E C H A  D E  C O R T E /////////////////////////

    getDatosxProyectoxFechaxCorte: function(proyectoid,revision) {
      var defered = $q.defer();
      var promise = defered.promise;
     

      httpFactory.getProyectoxFechaxCorte(proyectoid,revision)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

   
    setDatosxEliminarxFechaCorte: function(fechacorteid) {
      var defered = $q.defer();
      var promise = defered.promise;
     

      httpFactory.setEliminarxFechaCorte(fechacorteid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },


   
    setDatosxGuardarxFechaCorte: function(revision,codigoproyecto,proyectoid,fechacorte,tipocorte) {
      var defered = $q.defer();
      var promise = defered.promise;
     
      httpFactory.setGuardarxFechaCorte(revision,codigoproyecto,proyectoid,fechacorte,tipocorte)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setDatosxCambiarxFechaxCorte: function(valorcolumna,codigoproyecto,proyectoid,fechacorteid,columna) {
      var defered = $q.defer();
      var promise = defered.promise;
     
      httpFactory.setCambiarxFechaxCorte(valorcolumna,codigoproyecto,proyectoid,fechacorteid,columna)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },


/////////////////////// F I N  F E C H A  C O R T E /////////////////////////



    setDatosxGuardarxCronograma: function(codigocronograma,revision,estado,codigo_prop_proy,proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
     

      httpFactory.setGuardarxCronograma(codigocronograma,revision,estado,codigo_prop_proy,proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },


    setDatosxModificarxCronograma: function(codigo_cronograma,codigoproyecto,proyectoid,revision_cronograma,cronogramaid,state) {
      var defered = $q.defer();
      var promise = defered.promise;
     

      httpFactory.setModificarxCronograma(codigo_cronograma,codigoproyecto,proyectoid,revision_cronograma,cronogramaid,state)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setDatosxEliminarxCronograma: function(cronogramaid,codigoproyecto,proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;     

      httpFactory.setEliminarxCronograma(cronogramaid,codigoproyecto,proyectoid)
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
