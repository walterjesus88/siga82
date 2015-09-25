app.controller('ModalRendicionCtrl', ['httpFactory', '$modalInstance', 'gastosFactory',
  function(httpFactory, $modalInstance, gastosFactory) {

    mr = this;
    mr.alerts = [];
    mr.rendicion=[];
    var estado_actual='B'
    mr.fecha = new Date();
    mr.text = mr.fecha.Ddmmyyyy();

    // console.log(mr.fecha);

mr.GuardarRendicion= function(){

  gastosFactory.setGuardarRendicion(mr.numero_completo,mr.nombre,mr.text,estado_actual)
  .then(function(data) {
    /*insertar una nueva fila*/
    mr.inserted = {
      numero_completo:mr.numero_completo,
      nombre:mr.nombre,
      fecha:mr.text,
      estado:estado_actual,
    }
    // console.log(mr.inserted);
    mr.rendicion.push(mr.inserted);
    mr.alerts.push({type: 'success', msg: 'Rendicion guardada satisfactoriamente'});

    //recargar la pagina cuando se guardo para visualizar los gastos
    window.location.reload();

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
