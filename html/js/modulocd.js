angular.module('moduloCd', ['ngRoute', 'chart.js'])
.config(['$routeProvider', function($routeProvider) {
  $routeProvider
  .when("/", {
    controller: "PanelCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/panel"
  })
  .when("/proyectos", {
    controller: "ProyectoCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/proyectos"
  })
  .when("/asignarcd", {
    controller: "AsignarCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/asignarcd"
  })
  .when("/carpetas", {
    controller: "CarpetasCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/carpetas"
  })
  .when("/reporte", {
    controller: "ReporteCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/reporte"
  })
  .when("/transmittal", {
    controller: "TransmittalCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/transmittal"
  })
  .otherwise({
    redirectTo: '/'
  });
}])
.factory('httpFactory', ['$http', function($http) {
  var url = '/controldocumentario/index/';
  //var proyecto_seleccionado = {};
  //var datostransmittal = {};

  var publico = {
    getIntegrantes: function(){
      return $http.get(url + 'integrantes')
    },
    getProyectos: function() {
      return $http.get(url + 'proyectosjson');
    },
    getDatosTransmittal: function() {
      return datostransmittal;
    }
  }

  return publico;
}])
.controller('PanelCtrl', ['httpFactory', function(httpFactory) {
  var cd = this;

  cd.integrantes = [];
  cd.cantidad_proyectos = {
    total: 0,
    en_proceso: 0,
    stand_by: 0,
    cancelado: 0,
    cerrado: 0
  };

  cd.labels = [];
  cd.series = ['En Proceso'];
  cd.datos = [];
  cd.options = {
    legend: true,
    animationSteps: 150,
    animationEasing: "easeInOutQuint"
  };
  var valores = [];

  httpFactory.getIntegrantes()
  .success(function(res) {
    cd.integrantes = res;
    for (var i = 0; i < cd.integrantes.length; i++) {
      cd.cantidad_proyectos.en_proceso = cd.cantidad_proyectos.en_proceso + cd.integrantes[i].A;
      cd.cantidad_proyectos.stand_by = cd.cantidad_proyectos.stand_by + cd.integrantes[i].P;
      cd.cantidad_proyectos.cancelado = cd.cantidad_proyectos.cancelado + cd.integrantes[i].CA;
      cd.cantidad_proyectos.cerrado = cd.cantidad_proyectos.cerrado + cd.integrantes[i].C;

      cd.labels.push(cd.integrantes[i].nombre);
      valores.push(cd.integrantes[i].A);
    }
    cd.datos[0] = valores;
    cd.cantidad_proyectos.total = cd.cantidad_proyectos.en_proceso + cd.cantidad_proyectos.stand_by +
      cd.cantidad_proyectos.cancelado + cd.cantidad_proyectos.cerrado;
  })
  .error(function(res) {
    cd.integrantes = [];
  })
}])
.controller('ProyectoCtrl', ['httpFactory', function(httpFactory) {
  var cd = this;
  cd.proyectos = [];
  httpFactory.getProyectos()
  .success(function(res) {
    res.forEach(function(item) {
      proyecto = new Proyecto(item.codigo, item.cliente, item.nombre,
        item.gerente, item.control_proyecto, item.control_documentario,
        item.estado);
      cd.proyectos.push(proyecto);
    });
  })
  .error(function(res) {
    cd.proyectos = [];
  })

  cd.seleccionarProyecto = function(proyecto) {
    httpFactory.proyecto_seleccionado = proyecto;
  }
}])
.controller('AsignarCtrl', ['httpFactory', function(httpFactory) {

}])
.controller('CarpetasCtrl', ['httpFactory', function(httpFactory) {

}])
.controller('ReporteCtrl', ['httpFactory', function(httpFactory) {

}])
.controller('TransmittalCtrl', ['httpFactory', function(httpFactory) {
  var cd = this;

  cd.configurarActivo = '';
  cd.anddesActivo = 'active';
  cd.clienteActivo = '';
  cd.contratistaActivo = '';

  cd.cambiarPanel = function(panel) {
    if (panel == 'configurar') {
      cd.configurarActivo = 'active';
      cd.anddesActivo = '';
      cd.clienteActivo = '';
      cd.contratistaActivo = '';
    } else if (panel == 'anddes') {
      cd.configurarActivo = '';
      cd.anddesActivo = 'active';
      cd.clienteActivo = '';
      cd.contratistaActivo = '';
    } else if (panel == 'cliente') {
      cd.configurarActivo = '';
      cd.anddesActivo = '';
      cd.clienteActivo = 'active';
      cd.contratistaActivo = '';
    } else if (panel == 'contratista') {
      cd.configurarActivo = '';
      cd.anddesActivo = '';
      cd.clienteActivo = '';
      cd.contratistaActivo = 'active';
    }
  }
  //cd.transmittal = {};
  //cd.transmittal = httpFactory.getDatosTransmittal();
}]);




function Proyecto(codigo, cliente, nombre, gerente, control_proyecto,
  control_documentario, estado) {
  this.codigo = codigo;
  this.cliente = cliente;
  this.nombre = nombre;
  this.gerente = gerente;
  this.control_proyecto = control_proyecto;
  this.control_documentario = control_documentario;
  this.estado = estado;
}

Proyecto.prototype.asignarCd = function(con_doc) {
  this.control_documentario = con_doc;
};
