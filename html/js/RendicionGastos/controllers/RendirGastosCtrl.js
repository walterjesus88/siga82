app.controller('RendirGastosCtrl', ['httpFactory', 'gastosFactory', '$modal',
'$routeParams', function(httpFactory, gastosFactory, $modal, $routeParams) {


  /*referencia del scope en vr, obtencion del proyecto seleccionado y el objeto
  que contendra los datos del proyecto*/

console.log($routeParams);

  var vr = this;

  vr.gasto = {
    codigo: $routeParams.gasto,
  };

  // vr.cliente = {
  //     cliente: $routeParams.gasto,
  // };

  //carga de los datos del gasto seleccionado
  gastosFactory.getDatosGastos(vr.gasto.codigo)
  .then(function(data) {
    console.log("estoy en rendir de gasto");
    console.log(data);
    vr.gasto = data;
    //console.log(vr.gasto);
  })
  .catch(function(err) {
    vr.gasto = {};
  });


}]);
