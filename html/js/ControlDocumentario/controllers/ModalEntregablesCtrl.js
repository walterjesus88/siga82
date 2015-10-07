app.controller('ModalEntregablesCtrl', ['httpFactory', '$modalInstance',
'transmittal', 'correlativo', 'proyecto', 'entregableFactory',
'transmittalFactory',
function(httpFactory, $modalInstance, transmittal, correlativo, proyecto,
entregableFactory, transmittalFactory) {
  me = this;

  me.transmittal = transmittal;
  me.correlativo = correlativo;
  me.proyecto = proyecto;
  me.estado_revision = 'All';
  me.clase = 'Tecnico';

  me.entregables = [];

  transmittalFactory.getTransmittal(me.transmittal, me.correlativo)
  .then(function(data) {
    me.data_transmittal = data;
  })
  .catch(function(err) {
    me.data_transmittal = {};
  });

  httpFactory.getEntregables(me.proyecto, me.estado_revision, me.clase)
  .then(function(data) {
    data.forEach(function(item) {
      entregable = new entregableFactory.Entregable(item.cod_le, item.edt,
      item.tipo_documento, item.disciplina, item.codigo_anddes, item.codigo_cliente,
      item.descripcion_entregable, item.revision_entregable, item.estado_revision, item.transmittal,
      item.correlativo, item.emitido, item.fecha, item.respuesta_transmittal,
      item.respuesta_emitido, item.respuesta_fecha, item.estado, item.comentario, item.clase);
      entregable.setProyectoId(proyecto);
      me.entregables.push(entregable);
    })
  })
  .catch(function(err) {
    alert('No se pudieron obtener los entregables de ' + va.estado + ' del proyecto');
  });

  me.agregar = function() {
    for (var i = 0; i < me.entregables.length; i++) {
      if (me.entregables[i].seleccionado == 'selected') {
        me.entregables[i].agregarToTransmittal(me.data_transmittal);
        me.entregables[i].guardarDetalle();
      }
    }
    $modalInstance.close();
  }

  me.cancelar = function() {
    $modalInstance.dismiss('cancel');
  }
}]);
