app.controller('AnddesCtrl', ['httpFactory', 'entregableFactory', '$scope',
'transmittalFactory',
function(httpFactory, entregableFactory, $scope, transmittalFactory) {

  va = this;

  //obteniendo el codigo del proyecto del scope padre
  var proyecto = $scope.$parent.vt.proyecto;

  /*array que contendra la lista de entregables de los proyectos y el que
  contendra a los elementos seleccionados para generar transmittal*/
  va.entregables = [];
  va.seleccionados = [];

  //cargar los entregables
  var listarEntregables = function(proyecto, estado_revision) {
    httpFactory.getEntregables(proyecto, estado_revision)
    .then(function(data) {
      va.entregables = [];
      data.forEach(function(item) {
        entregable = new entregableFactory.Entregable(item.cod_le, item.edt,
        item.tipo_documento, item.disciplina, item.codigo_anddes, item.codigo_cliente,
        item.descripcion_entregable, item.revision, item.estado_revision, item.transmittal,
        item.correlativo, item.emitido, item.fecha, item.respuesta_transmittal,
        item.respuesta_emitido, item.respuesta_fecha, item.estado, item.comentario);
        va.entregables.push(entregable);
      })
    })
    .catch(function(err) {
      va.entregables = [];
    });
  }

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


  listarEntregables(proyecto.codigo, 'Ultimo');
  //array que contendra la lista de edts por proyecto
  va.edt = [];

  //cargar los edt
  httpFactory.getEdts(proyecto.codigo)
  .then(function(data){
    va.edt = data;
  })
  .catch(function(err) {
    va.edt = [];
  });

  //estado de los paneles de la vista
  va.edt_activo = '';
  va.tecnicos_activo = 'active';
  va.gestion_activo = '';
  va.comunicacion_activo = '';

  //cambio de panel visible segun menu seleccionado
  va.cambiarPanel = function(panel) {
    if (panel == 'edt') {
      va.edt_activo = 'active';
      va.tecnicos_activo = '';
      va.gestion_activo = '';
      va.comunicacion_activo = '';
    } else if (panel == 'tecnicos') {
      va.edt_activo = '';
      va.tecnicos_activo = 'active';
      va.gestion_activo = '';
      va.comunicacion_activo = '';
    } else if (panel == 'gestion') {
      va.edt_activo = '';
      va.tecnicos_activo = '';
      va.gestion_activo = 'active';
      va.comunicacion_activo = '';
    } else if (panel == 'comunicacion') {
      va.edt_activo = '';
      va.tecnicos_activo = '';
      va.gestion_activo = '';
      va.comunicacion_activo = 'active';
    }
  }

  //cambio de datos cargados de entregables
  va.cargarRevisiones = function(estado) {
    listarEntregables(proyecto.codigo, estado);
    cambiarSubPanel('tablas');
  }

  //generar el transmittal con los entregables seleccionados
  va.generarTr = function() {
    var transmittal = transmittalFactory.getConfiguracion();
    va.seleccionados = [];
    va.entregables.forEach(function(entregable) {
      if (entregable.seleccionado == 'selected') {
        entregable.transmittal = transmittal.codificacion;
        entregable.correlativo = transmittal.correlativo;
        entregable.agregarToTransmittal(transmittal);
        va.seleccionados.push(entregable);
      }
    });
    cambiarSubPanel('trans');

  }

  //vista de edicion de transmittal
  va.editarTransmittal = function() {
    cambiarSubPanel('trans');
  }
}]);
