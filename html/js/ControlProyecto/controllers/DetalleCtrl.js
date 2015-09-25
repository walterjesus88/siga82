/*Controlador de la vista de general de transmittals con include de
httpFactory para cargar los datos del proyecto necesarios,
configuracionTransmittal para acceder al objeto configuracionTransmittal,
$routeParams para obtener el codigo del proyecto actual de la ruta*/

app.controller('DetalleCtrl', ['httpFactory', 'proyectoFactory', '$modal',
'$routeParams', function(httpFactory, proyectoFactory, $modal, $routeParams) {

  /*referencia del scope en vt, obtencion del proyecto seleccionado y el objeto
  que contendra los datos del proyecto*/
//console.log($routeParams);

  var vt = this;

  vt.proyecto = {
    codigo: $routeParams.proyecto,
  };

  // vt.cliente = {
  //     cliente: $routeParams.proyecto,
  // };

  //carga de los datos del proyecto seleccionado
  proyectoFactory.getDatosProyecto(vt.proyecto.codigo)
  .then(function(data) {
    //console.log("estoy en detalle de proyecto");
    //console.log(data);
    vt.proyecto = data;
    //console.log(vt.proyecto);
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

    /*modalInstance.result.then(function (selectedItem) {
      vt.selected = selectedItem;
    }, function () {
      alert('Modal dismissed at: ' + new Date());
    });*/
 // }

}]);
