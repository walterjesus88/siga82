/*Controlador de la vista lista de proyectos, con include de httpFactory para
obtener la lista de proyectos, proyectoFactory para crear objetos de la clase
Proyecto*/

app.controller('ProyectoCtrl', ['httpFactory', 'proyectoFactory',
function(httpFactory, proyectoFactory) {

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
      });
    })
    .catch(function(err) {
      vp.proyectos = [];
    });
  }

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
