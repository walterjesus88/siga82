app.controller('AreaCtrl', ['httpFactory', 'areaFactory','$filter',
function(httpFactory, areaFactory,$filter) {

  /*referencia del scope y  los arrays que contendra a los proyectos y a los
  integrantes de control documentario*/
  var va = this;
  // va.listaareas = [];
  // va.control_documentario = [];

  // var isproyecto='S';

  httpFactory.getAreas()
    .then(function(data) {
      va.area=data;
      // console.log(va.area);
      })
    .catch(function(err) {
      va.area = [];
    });

    va.agregarArea = function() {
    if(va.area)
    {
      va.inserted = {
        id: va.area.length+1,
        areaid: null,
        nombre: null,
        area_padre: null ,
        isproyecto: null ,
        ispropuesta: null ,
        iscontacto: null ,
        iscomercial: null ,
        orden: null ,
        // isnew: true,
      };
    }
    else
    {
      va.area=[];
      va.inserted = {
        id: va.area.length+1,
        areaid: null,
        nombre: null,
        area_padre: null ,
        isproyecto: null ,
        ispropuesta: null ,
        iscontacto: null ,
        iscomercial: null ,
        orden: null ,
      };
    }
    va.area.push(va.inserted);
    // console.log(va.area);
};



  va.guardararea=function()
{
  // console.log(va.area);
  angular.forEach(va.area, function(val) {


    areaid=val['areaid'];
    nombre=val['nombre'];
    area_padre=val['area_padre'];
    isproyecto=val['isproyecto'];
    ispropuesta=val['ispropuesta'];
    iscontacto=val['iscontacto'];
    iscomercial=val['iscomercial'];
    orden=val['orden'];

    httpFactory.setGuardarArea(areaid,nombre,area_padre,isproyecto,ispropuesta,iscontacto,iscomercial,orden)
    .then(function(data) {
     // va.area=data;
    alert('Cambios guardados satisfactoriamente');
    })
    .catch(function(err) {
      va.area = [];
    })

  })

}

}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});
/*app.run(function(editableOptions) {
  editableOptions.theme = 'bs2';
});*/