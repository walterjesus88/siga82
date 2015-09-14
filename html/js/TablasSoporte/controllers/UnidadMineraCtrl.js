app.controller('UnidadMineraCtrl', ['httpFactory', 'unidadmineraFactory','$filter',
function(httpFactory, unidadFactory,$filter) {

  /*referencia del scope y  los arrays que contendra a los proyectos y a los
  integrantes de control documentario*/
  var vum = this;

  httpFactory.getUnidadMineras()
    .then(function(data) {
      vum.unidadminera=data;
      // console.log(vum.unidadminera);
      })
    .catch(function(err) {
      vum.unidadminera = [];
    });


vum.ShowFormUnidadMinera=function(){
   vum.formVisibilityUnidadMinera=true;

  }

vum.GuardarUnidadMinera= function(){

console.log(vum.unidad_mineraid,clienteid,nombre);
    unidadmineraFactory.setGuardarUnidadMinera(vum.unidad_mineraid,vum.clienteid,vum.nombre,vum.estado,vum.direccion,vum.paisid,vum.departamentoid,vum.distritoid,vum.tag,vum.isunidadminera)
    .then(function(data) {
/*insertar una nueva fila*/
console.log(vum.unidad_mineraid,clienteid,nombre);
      vum.inserted = {
        unidad_mineraid:vum.unidad_mineraid,
        clienteid:vum.clienteid,
        nombre:vum.nombre,
        estado:vum.estado,
        direccion:vum.direccion,
        paisid:vum.paisid,
        departamentoid:vum.departamentoid,
        distritoid:vum.distritoid,
        tag:vum.tag,
        isunidadminera:vum.isunidadminera,
      }

      vum.unidadminera.push(vum.inserted);
      console.log('guardar area');
      console.log(vum.inserted);
      vum.formVisibilityUnidadMinera=false;

    })
    .catch(function(err) {
              vum.unidadminera = {};
    });
  }

vum.ModificarUnidadMinera=function(){

  angular.forEach(vum.unidadminera, function(val) {


    unidad_mineraid=val['unidad_mineraid'];
    clienteid=val['clienteid'];
    nombre=val['nombre'];
    estado=val['estado'];
    direccion=val['direccion'];
    paisid=val['paisid'];
    departamentoid=val['departamentoid'];
    distritoid=val['distritoid'];
    tag=val['tag'];
    isunidadminera=val['isunidadminera'];

    httpFactory.setModificarUnidadMinera(unidad_mineraid,clienteid,nombre,estado,direccion,paisid,departamentoid,distritoid,tag,isunidadminera)
    .then(function(data) {

    // alert('Cambios guardados satisfactoriamente');
    })
    .catch(function(err) {
      vum.unidadminera = [];
    })

  })
  alert(vum.unidad_mineraid);
  }

  vum.CancelarUnidadMinera=function(){
    vum.formVisibilityUnidadMinera=false;
  }


}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});
