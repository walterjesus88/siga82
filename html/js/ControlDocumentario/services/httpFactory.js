/*servicio Factory para el manejo de solicitudes http get, post, update y
delete al servidor*/

app.factory('httpFactory', ['$http', function($http) {

  var url = '/controldocumentario/index/';

  var publico = {
    getIntegrantes: function(){
      return $http.get(url + 'integrantes')
    },
    getProyectos: function(estado) {
      return $http.get(url + 'proyectosjson/estado/' + estado);
    },
    getClientes: function() {
      return $http.get(url + 'clientes');
    },
    getTiposProyecto: function() {
      return $http.get(url + 'tipoproyecto');
    },
    getContactosByCliente: function(clienteid) {
      return $http.get(url + 'contactos/clienteid/' + clienteid);
    },
    getProyectoById: function(proyectoid) {
      return $http.get(url + 'proyecto/proyectoid/' + proyectoid);
    },
    setConfiguracionTransmittal: function(datos) {
      return $http.post(url + 'conftrans/codificacion/' + datos.codificacion +
      '/formato/' + datos.formato + '/tipoenvio/' + datos.tipo_envio +
      '/cliente/' + datos.cliente + '/controldocumentario/' +
      datos.control_documentario + '/atencion/' + datos.atencion +
      '/dias_alerta/' + datos.dias_alerta + '/tipoproyecto/' +
      datos.tipo_proyecto);
    }
  }

  return publico;
}]);
