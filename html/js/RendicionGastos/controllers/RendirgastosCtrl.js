app.controller('RendirgastosCtrl', ['httpFactory', 'gastosFactory', '$modal',
'$routeParams', function(httpFactory, gastosFactory, $modal, $routeParams) {


  /*referencia del scope en vt, obtencion del proyecto seleccionado y el objeto
  que contendra los datos del proyecto*/
  var vr = this;
  
  vr.gastos = {
    numero: $routeParams.gastos
  };

  //carga de los datos de la rendicion seleccionada
  gastosFactory.getDatosRendicion(vr.gastos.numero)
  .then(function(data) {
    vr.gastos = data;
  })
  .catch(function(err) {
    vr.gastos = {};
  });


}]);
