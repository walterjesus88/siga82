/*Controlador de la vista de general de transmittals con include de
httpFactory para cargar los datos del proyecto necesarios,
configuracionTransmittal para acceder al objeto configuracionTransmittal,
$routeParams para obtener el codigo del proyecto actual de la ruta*/

app.controller('TransmittalCtrl', ['httpFactory', 'proyectoFactory',
'$routeParams', function(httpFactory, proyectoFactory, $routeParams) {

  /*referencia del scope en vt, obtencion del proyecto seleccionado y el objeto
  que contendra los datos del proyecto*/
  var vt = this;
  vt.proyecto = {
    codigo: $routeParams.proyecto
  };

  //carga de los datos del proyecto seleccionado
  proyectoFactory.getDatosProyecto(vt.proyecto.codigo)
  .then(function(data) {
    vt.proyecto = data;
  })
  .catch(function(err) {
    vt.proyecto = {};
  });

  //panel visible por defecto aplicando la clase css active
  vt.configurarActivo = '';
  vt.anddesActivo = 'active';
  vt.clienteActivo = '';
  vt.contratistaActivo = '';

  //metodo para cambiar el panel visible
  vt.cambiarPanel = function(panel) {
    if (panel == 'configurar') {
      vt.configurarActivo = 'active';
      vt.anddesActivo = '';
      vt.clienteActivo = '';
      vt.contratistaActivo = '';
    } else if (panel == 'anddes') {
      vt.configurarActivo = '';
      vt.anddesActivo = 'active';
      vt.clienteActivo = '';
      vt.contratistaActivo = '';
    } else if (panel == 'cliente') {
      vt.configurarActivo = '';
      vt.anddesActivo = '';
      vt.clienteActivo = 'active';
      vt.contratistaActivo = '';
    } else if (panel == 'contratista') {
      vt.configurarActivo = '';
      vt.anddesActivo = '';
      vt.clienteActivo = '';
      vt.contratistaActivo = 'active';
    }
  }

}]);
