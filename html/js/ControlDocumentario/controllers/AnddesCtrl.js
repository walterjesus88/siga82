app.controller('AnddesCtrl', ['httpFactory', 'entregableFactory', '$scope',
function(httpFactory, entregableFactory, $scope) {

  va = this;

  //obteniendo el codigo del proyecto del scope padre
  var proyecto = $scope.$parent.vt.proyecto;

  //array que contendra la lista de entregables de los proyectos
  va.entregables = [];

  //cargar los entregables
  httpFactory.getEntregables(proyecto.codigo)
  .then(function(data) {
    va.entregables = [];
    data.forEach(function(item) {
      entregable = new entregableFactory.entregable(item.codigo, item.edt,
      item.tipo, item.disciplina, item.codigo_anddes, item.codigo_cliente,
      item.descripcion, item.revision, item.estado, item.transmittal);
      va.entregables.push(entregable);
    })
  })
  .catch(function(err) {
    va.entregables = [];
  });

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

  va.edt_activo = '';
  va.tecnicos_activo = 'active';
  va.gestion_activo = '';
  va.comunicacion_activo = '';

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
}]);
