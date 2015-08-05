/*Controlador de la vista de general de transmittals con include de
httpFactory para cargar los datos del proyecto necesarios,
configuracionTransmittal para acceder al objeto configuracionTransmittal,
$routeParams para obtener el codigo del proyecto actual de la ruta*/

app.controller('TransmittalCtrl', ['httpFactory', 'configuracionTransmittal',
'$routeParams', function(httpFactory, configuracionTransmittal, $routeParams) {

  /*referencia del scope en vt, obtencion del proyecto seleccionado y el objeto
  que contendra los datos del proyecto*/
  var vt = this;
  vt.proyecto = {
    codigo: $routeParams.proyecto
  };

  //obtencion de los datos de configuracion del transmittal
  vt.transmittal = configuracionTransmittal.getConfiguracion();

  //carga de los datos del proyecto seleccionado
  httpFactory.getProyectoById(vt.proyecto.codigo)
  .success(function(res) {
    vt.proyecto = res;

    //cargar los datos del transmittal con los datos del proyecto
    vt.transmittal.proyecto = vt.proyecto.codigo;
    vt.transmittal.clienteid = vt.proyecto.clienteid;
    vt.transmittal.cliente = vt.proyecto.cliente;
    vt.transmittal.control_documentario = vt.proyecto.control_documentario;
    vt.transmittal.tipo_proyecto = vt.proyecto.tipo_proyecto;
    //obtencion del numero correlativo correspondiente a este transmittal
    httpFactory.getCorrelativoTransmittal(vt.transmittal.proyecto)
    .success(function(res) {
      vt.transmittal.correlativo = res.correlativo;
    })
    .error(function(res) {
      vt.transmittal.correlativo = '';
    })
  })
  .error(function(res) {
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
