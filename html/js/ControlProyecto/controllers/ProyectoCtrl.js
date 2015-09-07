/*Controlador de la vista lista de proyectos, con include de httpFactory para
obtener la lista de proyectos, proyectoFactory para crear objetos de la clase
Proyecto*/

app.controller('ProyectoCtrl', ['httpFactory', 'proyectoFactory','$filter',
function(httpFactory, proyectoFactory,$filter) {

  /*referencia del scope y  los arrays que contendra a los proyectos y a los
  integrantes de control documentario*/
  var vp = this;
  vp.proyectos = [];
  vp.control_documentario = [];


  //funcion para obtener los proyectos del servidor
  var listarProyectos = function(estado) {
    httpFactory.getProyectos(estado)
    .then(function(data) {
      //console.log(data);
      vp.proyectos = [];
      data.forEach(function(item) {
        proyecto = new proyectoFactory.Proyecto(item.codigo_prop_proy,item.codigo, item.cliente,
          item.nombre, item.gerente, item.control_proyecto,
          item.control_documentario, item.estado);
        vp.proyectos.push(proyecto);
       // console.log(data);

      });
    })
    .catch(function(err) {
      vp.proyectos = [];
    });
  }


  vp.estadoproyecto = [
    {value: 'A', text: 'Activo'},
    {value: 'C', text: 'Cerrado'},   
    {value: 'CA', text: 'Cancelado'},   
    {value: 'P', text: 'Paralizado'},   
  ]; 

  vp.cambiarEstadoProyecto= function(index,estado)  
  {

    // console.log(proyecto['codigo']);
    // console.log(proyecto['estado']);
     //console.log(vp.estado);

    // proyectoFactory.setDatosxCambiarxEstadoproyecto(proyecto['codigo'],proyecto['estado'],proyecto['codigo_prop_proy'])
    // .then(function(data) {
    //     alert('Estado del Proyecto cambiado');
          
    //     //vp.proyectos.splice(index, 1); 
    // })
    // .catch(function(err) {
                
    // });

  }

  vp.showEstadoproyecto = function(proyecto) {
    var selected = [];
    if(proyecto.estado) {
      selected = $filter('filter')(vp.estadoproyecto, {value: proyecto.estado});
    }
    return selected.length ? selected[0].text : 'Not set';
  };


  //carga inicial de integrantes de control documentario
  // httpFactory.getIntegrantes()
  // .then(function(data) {
  //   vp.control_documentario = [];
  //   data.forEach(function(integrante) {
  //     integrante.nombre = integrante.uid.changeFormat();
  //     vp.control_documentario.push(integrante);
  //   })
  // })
  // .catch(function(err) {
  //   vp.control_documentario = [];
  // });

  //carga inicial de los proyectos con estado activo
  listarProyectos('A');

  //metodo para cargar los proyectos de los diferentes estados
  vp.cargarProyectos = function(estado) {
    listarProyectos(estado);
  }

}]);
