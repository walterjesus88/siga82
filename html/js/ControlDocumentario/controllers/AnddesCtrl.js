app.controller('AnddesCtrl', ['httpFactory', 'entregableFactory', '$routeParams',
'transmittalFactory', '$rootScope',
function(httpFactory, entregableFactory, $routeParams, transmittalFactory, $rootScope) {

  va = this;

  //obteniendo el codigo del proyecto de los parametros de la ruta
  var proyecto = $routeParams.proyecto;
  va.transmittal = {};

  //modos de envio disponibles (fisico o orreo electronico)
  va.modos = [{codigo: 'F', nombre: 'Físico'}, {codigo: 'C', nombre: 'Correo'}];

  //objeto que se visualizara al pie de los entregables a emitir
  va.atencion = {
    codigo: '',
    nombre: '',
    area: '',
    correo: ''
  }

  /*array que contendra la lista de entregables de los proyectos y el que
  contendra a los elementos seleccionados para generar transmittal*/
  va.entregables = [];
  va.entregables_gestion = [];
  va.entregables_comunicacion = [];
  va.seleccionados = [];

  /*estados por defecto de la revision del transmittal y tipo de entregable por
  defecto (Tecnico, Gestion, Comunicacion)*/
  va.estado = 'Ultimo';
  va.clase = 'Tecnico';

  //funcion para cargar los entregables de un proyecto, por estado y tipo (T, G, C)
  var listarEntregables = function(proyecto, estado_revision, clase) {
    httpFactory.getEntregables(proyecto, estado_revision, clase)
    .then(function(data) {
      var ent_temp = [];
      data.forEach(function(item) {
        entregable = new entregableFactory.Entregable(item.cod_le, item.edt,
        item.tipo_documento, item.disciplina, item.codigo_anddes, item.codigo_cliente,
        item.descripcion_entregable, item.revision_entregable, item.estado_revision, item.transmittal,
        item.correlativo, item.emitido, item.fecha, item.respuesta_transmittal,
        item.respuesta_emitido, item.respuesta_fecha, item.estado, item.comentario, item.clase);
        entregable.setProyectoId(proyecto);
        ent_temp.push(entregable);
      })
      if (clase == 'Tecnico') {
        va.entregables = ent_temp;
        $rootScope.$broadcast('to_childrens', {lista: va.entregables, clase: 'Tecnico'});
      } else if (clase == 'Gestion') {
        va.entregables_gestion = ent_temp;
        $rootScope.$broadcast('to_childrens', {lista: va.entregables_gestion, clase: 'Gestion'});
      } else if (clase == 'Comunicacion') {
        va.entregables_comunicacion = ent_temp;
        $rootScope.$broadcast('to_childrens', {lista: va.entregables_comunicacion, clase: 'Comunicacion'});
      }
    })
    .catch(function(err) {
      alert('No se pudieron obtener los entregables de ' + estado + ' del proyecto');
    });
  }

  //cargar los entregables del proyecto con los datos por defecto (Ultimos, Tecnicos)
  listarEntregables(proyecto, va.estado, va.clase);

  $rootScope.$on("to_parents", function(event, data){
    listarEntregables(proyecto, va.estado, va.clase);
  })

  //estado de los paneles de la vista
  va.edt_activo = '';
  va.tecnicos_activo = 'active';
  va.gestion_activo = '';
  va.comunicacion_activo = '';
  va.tabla_visible = 'active';

  //cambio de panel visible segun menu seleccionado
  va.cambiarPanel = function(panel) {
    if (panel == 'edt') {
      va.edt_activo = 'active';
      va.tecnicos_activo = '';
      va.gestion_activo = '';
      va.comunicacion_activo = '';
      va.tabla_visible = '';
    } else if (panel == 'tecnicos') {
      va.edt_activo = '';
      va.tecnicos_activo = 'active';
      va.gestion_activo = '';
      va.comunicacion_activo = '';
      va.tabla_visible = 'active';
      va.clase = 'Tecnico';
      listarEntregables(proyecto, 'Ultimo', va.clase);
    } else if (panel == 'gestion') {
      va.edt_activo = '';
      va.tecnicos_activo = '';
      va.gestion_activo = 'active';
      va.comunicacion_activo = '';
      va.tabla_visible = 'active';
      va.clase = 'Gestion';
      listarEntregables(proyecto, 'Ultimo', va.clase);
    } else if (panel == 'comunicacion') {
      va.edt_activo = '';
      va.tecnicos_activo = '';
      va.gestion_activo = '';
      va.comunicacion_activo = 'active';
      va.tabla_visible = 'active';
      va.clase = 'Comunicacion';
      listarEntregables(proyecto, 'Ultimo', va.clase);
    }
  }

  /*sub paneles dee la vista para visualizar los datos de ultimas revisiones,
  historial de revisiones, transmittal y planificacion*/
  va.tabla_activa = 'active';
  va.trans_activo = '';

  var cambiarSubPanel = function(panel) {
    if (panel == 'tablas') {
      va.tabla_activa = 'active';
      va.trans_activo = '';
    } else if (panel == 'trans') {
      va.tabla_activa = '';
      va.trans_activo = 'active';
    }
  }

  //cambio de datos cargados de entregables
  va.cargarRevisiones = function(estado) {
    listarEntregables(proyecto, estado, va.clase);
    va.estado = estado;
    cambiarSubPanel('tablas');
  }

  //generar el transmittal con los entregables seleccionados
  va.generarTr = function() {
    transmittalFactory.guardarCambios();
    transmittalFactory.getConfiguracion()
    .then(function(data) {

      va.transmittal = data;

      //cargar los datos del contacto a mostrar en la vista del transmittal
      va.atencion.codigo = va.transmittal.atencion;
      httpFactory.getDatosContacto(va.transmittal.clienteid, va.atencion.codigo)
      .then(function(data) {
        va.atencion = data;
      })
      .catch(function(err) {
      });

      //listar todos los elementos seleccionados en las tablas anteriores
      va.seleccionados = [];
      if (va.transmittal.codificacion != '' && va.transmittal.codificacion != null) {
        va.entregables.forEach(function(entregable) {
          if (entregable.seleccionado == 'selected') {
            entregable.agregarToTransmittal(va.transmittal);
            va.seleccionados.push(entregable);
          }
        });
        va.entregables_gestion.forEach(function(entregable) {
          if (entregable.seleccionado == 'selected') {
            entregable.agregarToTransmittal(va.transmittal);
            va.seleccionados.push(entregable);
          }
        });
        va.entregables_comunicacion.forEach(function(entregable) {
          if (entregable.seleccionado == 'selected') {
            entregable.agregarToTransmittal(va.transmittal);
            va.seleccionados.push(entregable);
          }
        });
        cambiarSubPanel('trans');
      } else {
        alert('Configure el Transmittal antes de agregar entregables');
      }
    })
    .catch(function(err) {

    });
  }

  //cambiar el modo de envio del transmittal
  va.cambiarModoEnvio = function() {
    if (va.transmittal.codificacion != '' && va.transmittal.codificacion != null) {
      transmittalFactory.setModoEnvio(va.transmittal.modo_envio);
    } else {
      alert('No se ha configurado el transmittal');
    }
  }

  //listas de revisiones y emisiones
  va.revisiones = ['A', 'B', 'C', 'D', 'E', '0'];
  va.emisiones = [];

  listarEmisiones = function() {
    transmittalFactory.getConfiguracion()
    .then(function(data) {
      va.transmittal = data;
      httpFactory.getEmisionesByTipo(va.transmittal.tipo_envio)
      .then(function(data) {
        va.emisiones = data;
      })
      .catch(function(err) {
        va.emisiones = [];
      });
    })
    .catch(function(err) {

    });
  }

  listarEmisiones();


  //vista de edicion de transmittal
  va.editarTransmittal = function() {
    cambiarSubPanel('trans');
  }

  //guardar los detalles del transmittal
  va.guardarDetalleTr = function() {
    va.seleccionados.forEach(function(entregable) {
      entregable.guardarDetalle();
    });
    alert('Entregables Guardados Satisfactoriamente');
  }

  //imprimir el reporte de los entregables
  va.imprimirReporteTr = function() {
    httpFactory.createPdfRT(proyecto, va.estado, va.clase)
    .then(function(data) {
      window.open(data.archivo, '_blank');
    })
    .catch(function(err) {

    });
  }

  //imprimir el transmittal en edicion
  va.imprimirTransmittal = function(argument) {
    transmittalFactory.imprimirTransmittal();
  }
}]);
