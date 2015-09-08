app.controller('AreaCtrl', ['httpFactory', 'areaFactory','$filter',
function(httpFactory, areaFactory,$filter) {

  /*referencia del scope y  los arrays que contendra a los proyectos y a los
  integrantes de control documentario*/
  var va = this;
  va.listaareas = [];
  // va.control_documentario = [];

va.variable=[{nombre:"jesus", texto:"jajaja"},{nombre:"mario", texto:"jojojo"}];

  var isproyecto='S';

  httpFactory.getAreas(isproyecto)
    .then(function(data) {
      va.area=data;
      console.log(va.area);
      })
    .catch(function(err) {
      va.area = [];
    });


     //funcion para obtener los proyectos del servidor
  // var listarAreas = function(isproyecto) {
  //   httpFactory.getAreas(isproyecto)
  //   .then(function(data) {
  //     //console.log(data);
  //     va.listaareas = [];
  //     data.forEach(function(item) {
  //       area = new areaFactory.Area(item.areaid,item.nombre);
  //       va.listaareas.push(listararea);
  //      // console.log(data);

  //     });
  //   })
  //   .catch(function(err) {
  //     va.listaareas = [];
  //   });
  // }

}]);