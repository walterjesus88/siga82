app.controller('LECtrl', ['httpFactory', '$routeParams', 'entregableFactory',
function(httpFactory, $routeParams, entregableFactory) {
  le = this;
  proyectoid = $routeParams.proyecto;
  le.entregables = [];

  httpFactory.getListaEntregables(proyectoid, 'All', 'Tecnico')
  .then(function(data) {
    data.forEach(function(item) {
      entregable = new entregableFactory.Entregable(item.cod_le, item.edt,
      item.tipo_documento, item.disciplina, item.codigo_anddes, item.codigo_cliente,
      item.descripcion_entregable, item.revision_entregable, item.estado_revision, item.transmittal,
      item.correlativo, item.emitido, item.fecha, item.respuesta_transmittal,
      item.respuesta_emitido, item.respuesta_fecha, item.estado, item.comentario, item.clase);
      entregable.setProyectoId(proyectoid);
      entregable.fecha_a = item.fecha_a;
      entregable.fecha_b = item.fecha_b;
      entregable.fecha_0 = item.fecha_0;
      le.entregables.push(entregable);
    })

  })
  .catch(function(err) {
    le.entregables = [];
  })
}]);
