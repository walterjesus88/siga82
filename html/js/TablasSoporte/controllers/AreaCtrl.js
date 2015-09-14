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


va.ShowFormArea=function(){ 
   va.formVisibilityArea=true;    

  }

va.GuardarArea= function(){
    // console.log(va.areaid);
    // console.log(va.nombre);
    // console.log(va.area_padre);
    // console.log(va.isproyecto);
    // console.log(va.ispropuesta);
    // console.log(va.iscontacto);
    // console.log(va.iscomercial);
    // console.log(va.orden);
    areaFactory.setGuardarArea(va.areaid,va.nombre,va.area_padre,va.isproyecto,va.ispropuesta,va.iscontacto,va.iscomercial,va.orden)
    .then(function(data) {
/*insertar una nueva fila*/
      va.inserted = {
        areaid:va.areaid,
        nombre:va.nombre,
        area_padre:va.area_padre,
        isproyecto:va.isproyecto,
        ispropuesta:va.ispropuesta,
        iscontacto:va.iscontacto,
        iscomercial:va.iscomercial,
        orden:va.orden,

      }

      va.area.push(va.inserted); 
      // console.log('guardar edt');  
      // console.log(va.edt);  
      va.formVisibilityArea=false;

    })
    .catch(function(err) {
              //va.procronograma = {};
    });
  }

va.ModificarArea=function(){ 
    // console.log(va.area);
  // console.log(va.inserted);
  angular.forEach(va.area, function(val) {


    areaid=val['areaid'];
    nombre=val['nombre'];
    area_padre=val['area_padre'];
    isproyecto=val['isproyecto'];
    ispropuesta=val['ispropuesta'];
    iscontacto=val['iscontacto'];
    iscomercial=val['iscomercial'];
    orden=val['orden'];

    httpFactory.setModificarArea(areaid,nombre,area_padre,isproyecto,ispropuesta,iscontacto,iscomercial,orden)
    .then(function(data) {
     // va.area=data;
    // alert('Cambios guardados satisfactoriamente');
    })
    .catch(function(err) {
      va.area = [];
    })

  })
    
  }

  va.CancelarArea=function(){
    va.formVisibilityArea=false;
  }

  // va.EliminarArea=function(index,codigoedt){

  //   codigoproyecto=va.proyectop.codigo_prop_proy;
  //   proyectoid=va.proyectop.codigo;

  //   console.log(index);
  //   console.log(codigoedt);
  //   console.log(va.edt);

  //   proyectoFactory.setEliminarxEDT(codigoedt,codigoproyecto,proyectoid)
  //   .then(function(data) {
  //     va.edt.splice(index, 1);          
  //   })
  //   .catch(function(err) {
  //       console.log("error al eliminar edt");
  //   });

  // }


  va.showStatus = function(lista) {
    var selected = [];
    if(lista.area) {
      selected = $filter('filter')(va.area, {areaid: lista.area});
    }
    return selected.length ? selected[0].areaid : 'Not set';
  };

}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});
