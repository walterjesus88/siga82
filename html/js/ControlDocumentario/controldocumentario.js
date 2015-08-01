/*Creacion del modulo de Control Documentario con la inyeccion de dependencias
de ngRoute para el manejo de rutas y Chart.js para la creacion de graficos
estadisticos*/

var app = angular.module('moduloCd', ['ngRoute', 'chart.js']);

/*Configuracion de las rutas disponibles en el modulo y asociacion con las
vistas y controladores necesarios*/

app.config(['$routeProvider', function($routeProvider) {
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
}]);

/*clase temporal para la gestion de los proyectos*/

function Proyecto(codigo, cliente, nombre, gerente, control_proyecto,
  control_documentario, estado) {
  var estados = {
    'A': 'Activo',
    'P': 'Paralizado',
    'C': 'Cerrado',
    'CA': 'Cancelado'
  }
  this.codigo = codigo;
  this.cliente = cliente;
  this.nombre = nombre;
  this.gerente = gerente.changeFormat();
  this.control_proyecto = control_proyecto;
  this.control_documentario = control_documentario;
  this.estado = estados[estado];
}
