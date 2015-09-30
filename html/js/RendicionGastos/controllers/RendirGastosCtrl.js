app.controller('RendirGastosCtrl', ['$scope','httpFactory', 'gastosFactory', '$modal', '$location', '$routeParams',
  function($scope,httpFactory, gastosFactory, $modal, $location, $routeParams){

  /*referencia del scope en vr, obtencion de la rendicion seleccionada y el objeto
  que contendra los datos de la rendicion*/

  var vrg = this;
  // var estado_actual = ;
  // vrg.gastospersona = [];

  var numero= $routeParams['numero'];
  var fecha= $routeParams['fecha'];

  //console.log(this);
  console.log("fecha "+fecha);

 // console.log(vrg.rendir[0]['numero']);

  //carga de los datos de la rendicion seleccionada
  gastosFactory.getDatosGastos(numero)
  .then(function(data) {
    // console.log("estoy en rendir de gastos");
    // console.log(data);
    vrg.rendir = data;
    console.log(vrg.rendir);
  })
  .catch(function(err) {
    vrg.rendir = [];
  });



  vrg.ShowFormRendir=function(){
   vrg.formVisibilityRendir=true;
 }


vrg.GuardarGastos= function(){

    gastosFactory.setGuardarGastos(vrg.descripcion,vrg.gastoid,vrg.bill_cliente,vrg.reembolsable,vrg.fecha_factura,vrg.num_factura,vrg.moneda,vrg.proveedor,vrg.monto_igv,vrg.otro_impuesto,numero,fecha)
    .then(function(data) {
/*insertar una nueva fila*/
      vrg.inserted = {
        descripcion:vrg.descripcion,
        gastoid:vrg.gastoid,
        bill_cliente:vrg.bill_cliente,
        reembolsable:vrg.reembolsable,
        fecha_factura:vrg.fecha_factura,
        num_factura:vrg.num_factura,
        moneda:vrg.moneda,
        proveedor:vrg.proveedor,
        monto_igv:vrg.monto_igv,
        otro_impuesto:vrg.otro_impuesto,
        numero_rendicion:numero,
        fecha_gasto:fecha,

      }

  console.log("numero de rendicion " + numero);
      vrg.rendir.push(vrg.inserted); 
      // console.log('guardar rendir');  
      console.log("fecha de rendicion " + fecha);  
      // vrg.formVisibilityrendir=false;

    })
    .catch(function(err) {
              vrg.rendir = {};
    });
  }



 vrg.CancelarRendir=function(){
  vrg.formVisibilityRendir=false;
}






}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});