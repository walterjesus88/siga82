var app = angular.module('areasApp', [])
.controller('areacontroller', ['$http', function($http){


  area = this;


  area.getAreas = function () {
    $http.get('/control/index/verjson')
    .success(function (res) {
      reporte.gerentes = res
    })
  }

}] )


















