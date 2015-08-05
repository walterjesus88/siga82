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

  //carga inicial de los proyectos con estado activo
  httpFactory.getProyectos('A')
  .success(function(res) {
    vp.proyectos = [];
    res.forEach(function(item) {
      proyecto = new proyectoFactory.Proyecto(item.codigo, item.cliente,
        item.nombre, item.gerente, item.control_proyecto,
        item.control_documentario, item.estado);
      vp.proyectos.push(proyecto);
    });
  })
  .error(function(res) {
    vp.proyectos = [];
  });

  //carga inicial de integrantes de control documentario
  httpFactory.getIntegrantes()
  .success(function(res) {
    vp.control_documentario = [];
    res.forEach(function(integrante) {
      integrante.nombre = integrante.uid.changeFormat();
      vp.control_documentario.push(integrante);
    })
  })
  .error(function(res) {
    vp.control_documentario = [];
  });

  //metodo para cargar los proyectos de los diferentes estados
  vp.cargarProyectos = function(estado) {
    httpFactory.getProyectos(estado)
    .success(function(res) {
      vp.proyectos = [];
      res.forEach(function(item) {
        proyecto = new proyectoFactory.Proyecto(item.codigo, item.cliente,
          item.nombre, item.gerente, item.control_proyecto,
          item.control_documentario, item.estado);
        vp.proyectos.push(proyecto);
      });
    })
    .error(function(res) {
      vp.proyectos = [];
    });
  }

}]);
