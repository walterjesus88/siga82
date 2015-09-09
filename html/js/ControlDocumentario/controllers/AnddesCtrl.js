app.controller('AnddesCtrl', ['httpFactory', 'entregableFactory', '$routeParams',
'transmittalFactory', '$rootScope',
function(httpFactory, entregableFactory, $routeParams, transmittalFactory, $rootScope) {

  va = this;

  //obteniendo el codigo del proyecto de los parametros de la ruta
  var proyecto = $routeParams.proyecto;

  //obteniendo la variable de la vista a mostrar
  var vista = $routeParams.vista;

  va.transmittal = {};

  /*estados por defecto de la revision del transmittal y tipo de entregable por
  defecto (Tecnico, Gestion, Comunicacion)*/
  va.estado = 'Ultimo';
  va.clase = 'Tecnico';

  /*array que contendra la lista de entregables de los proyectos y el que
  contendra a los elementos seleccionados para generar transmittal*/
  va.entregables = [];
  va.entregables_gestion = [];
  va.entregables_comunicacion = [];
  va.seleccionados = [];

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
      alert('No se pudieron obtener los entregables de ' + va.estado + ' del proyecto');
    });
  }

  //cargar los entregables del proyecto con los datos por defecto (Ultimos, Tecnicos)
  listarEntregables(proyecto, va.estado, va.clase);

  //cambio de lista de entregables cargados en la tabla Entregables
  va.cargarRevisiones = function(estado) {
    listarEntregables(proyecto, estado, va.clase);
    va.estado = estado;
    cambiarSubPanel('tablas');
  }

  //vista de edicion de transmittal
  va.editarTransmittal = function() {
    cambiarSubPanel('trans');
  }

  //ver la tabla de planificacion
  va.verPlanificacion = function() {
    cambiarSubPanel('plan');
  }

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
  if (vista == 'informacion') {
    va.tabla_activa = 'active';
    va.trans_activo = '';
    va.plan_activo = '';
  } else if (vista == 'generartr') {
    va.tabla_activa = '';
    va.trans_activo = 'active';
    va.plan_activo = '';
  } else if (vista == 'generarrpt') {
    va.tabla_activa = '';
    va.trans_activo = '';
    va.plan_activo = 'active';
  }


  var cambiarSubPanel = function(panel) {
    if (panel == 'tablas') {
      va.tabla_activa = 'active';
      va.trans_activo = '';
      va.plan_activo = '';
    } else if (panel == 'trans') {
      va.tabla_activa = '';
      va.trans_activo = 'active';
      va.plan_activo = '';
    } else if (panel == 'plan') {
      va.tabla_activa = '';
      va.trans_activo = '';
      va.plan_activo = 'active';
    }
  }

  //generar el transmittal con los entregables seleccionados
  va.generarTr = function() {
    transmittalFactory.getConfiguracion()
    .then(function(data) {
      va.transmittal = data;
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

        if (va.seleccionados.length != 0) {
          //guardar los detalles del transmittal
          transmittalFactory.guardarCambios();
          va.seleccionados.forEach(function(entregable) {
            entregable.guardarDetalle();
          });

          $rootScope.$broadcast('recarga_detalles');
          cambiarSubPanel('trans');
        } else {
          alert('Seleccione un entregable para generar transmittal');
        }


      } else {
        alert('Configure el Transmittal antes de agregar entregables');
      }
    })
    .catch(function(err) {

    });
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
}]);
