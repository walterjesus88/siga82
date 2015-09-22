app.controller('ModalRendicionCtrl', ['httpFactory', '$modalInstance', 'gastosFactory',
  function(httpFactory, $modalInstance, gastosFactory) {

    mr = this;
    mr.alerts = [];
    mr.rendicion=[];


mr.GuardarRendicion= function(){

  gastosFactory.setGuardarRendicion(mr.numero_completo,mr.nombre,mr.fecha,mr.monto_total,mr.estado)
  .then(function(data) {
    /*insertar una nueva fila*/
    mr.inserted = {
      numero_completo:mr.numero_completo,
      nombre:mr.nombre,
      fecha:mr.fecha,
      monto_total:mr.monto_total,
      estado:mr.estado,
    }
    // console.log(mr.inserted);
    mr.rendicion.push(mr.inserted);
    // console.log(mr.inserted);
    mr.alerts.push({type: 'success', msg: 'Rendicion guardada satisfactoriamente'});

  })
  .catch(function(err) {
    mr.alerts.push({type: 'danger', msg: 'Error al momento de guardar rendicion'});
  });
}


mr.cancelar = function() {
  $modalInstance.dismiss('cancel');
}

mr.closeAlert = function(index) {
  mr.alerts.splice(index, 1);
}
}]);
