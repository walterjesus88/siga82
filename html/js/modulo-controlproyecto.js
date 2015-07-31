
angular.module('moduloCp', ['ngRoute', 'chart.js','ui.bootstrap','ui.bootstrap.tpls','ui.router','checklist-model'])
.config(['$routeProvider', function($routeProvider) {
  $routeProvider
  .when("/", {
    controller: "PanelCtrl",
    controllerAs: "CD",
    templateUrl: "/proyecto/index/panel"
  })
//  .when("/", {
  //  controller: "ModalDemoCtrl",
    //controllerAs: "CD",
  //  templateUrl: "/proyecto/index/panel"
//  })

  // .when("/", {
  //   controller: "ModalInstanceCtrl",
  //   controllerAs: "CD",
  //   templateUrl: "/proyecto/index/panel"
  // })

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
  .otherwise({
    redirectTo: '/'
  });
}])
.factory('httpFactory', ['$http', function($http) {
  var url = '/proyecto/index/';
  //var url = '/controldocumentario/index/'; 

  var publico = {
    getIntegrantes: function(){
      return $http.get(url + 'verjson')
      //return $http.get(url + 'integrantes')
    },
    getUsuarios: function() {
      return $http.get(url + 'usuariosjson');
    }


  }

  return publico;
}])

.controller('PanelCtrl', ['httpFactory', function(httpFactory) {
  var cd = this;

      cd.data = [
      { name: 'Personal', expanded: true,
        items: [
          { name: 'Walk dog', completed: false },
          { name: 'Write blog post', completed: true },
          { name: 'Buy milk', completed: false },
        ]
      },
      { name: 'Work', expanded: false,
        items: [
          { name: 'Ask for holidays', completed: false }
        ]
      },
      { name: 'Books to read', expanded: false,
        items: [
          { name: 'War and peace', completed: false },
          { name: '1Q84', completed: false },
        ]
      }
    ];

  //scope.selectedCategory = scope.data[0];
  //modal


  cd.addArea = function() {
      if (cd.newAreaName) {
      var newArea = { name: cd.newAreaName, expanded: false };
          newArea.items = [];
          cd.integrantes.push(newArea);
          console.log(cd);
          cd.errorNewCategory = false;
        } else {
        cd.errorNewCategory = true;
       }
    };

  cd.addTask = function() {
      if (cd.newTaskName) {
         console.log("ddd");
        var newTask = { uid: cd.newTaskName, completed: false };
        cd.selectedCategory.items.push(newTask);
        cd.selectedCategory.expanded=true;
        cd.errorNewTask = false;
      } else {
        cd.errorNewTask = true;
      }
    };

  cd.toggleArea = function(integrante)
  {
      integrante.expanded = !integrante.expanded;
  };

  cd.deleteTask = function(integrante, item) {
      integrante.items.splice(integrante.items.indexOf(item), 1);
  };

  cd.completeTask = function(item) {
      item.completed = true;
    };

  cd.uncompleteTask = function(item) {
      item.completed = false;
  };

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

  //factory para llamar datos

  httpFactory.getIntegrantes()
  .success(function(res) {
    cd.integrantes = res; 
    cd.selectedCategory = cd.integrantes[0];
   })
  .error(function(res) {
    cd.integrantes = [];
  });

  cd.newAreaName = "";
  cd.newTaskName = "";
 
  cd.errorNewCategory = false;
  cd.errorNewTask = false;
}])



.controller('ModalDemoCtrl', ['httpFactory','$scope', '$modal',function (httpFactory, $scope, $modal , $log) {
  //$scope=this;
   $scope.avisar = function(){
            console.log("cambié");
        }


  $scope.algo = "fffffffffffHola Angular, usando controller más simple";
  console.log("dddddddddd");

  //$scope.items = ['item1', 'item2', 'item3'];

  $scope.items=[
  {nombre:"xxxx 1"},
  {nombre:"walter jesus"},
  {nombre:"ss"}];


  httpFactory.getUsuarios()
  .success(function(res) {
    $scope.roles = res; 
   
   })
  .error(function(res) {
    $scope.roles = [];
  });


  // $scope.roles = [
  //   {id: 1, text: 'guest'},
  //   {id: 2, text: 'user'},
  //   {id: 3, text: 'customer'},
  //   {id: 4, text: 'admin'}
  // ];



  $scope.user = {
    roles: [$scope.roles[1]]
  };
  $scope.checkAll = function() {
    $scope.user.roles = angular.copy($scope.roles);
    console.log($scope.user.roles);
  };
  $scope.uncheckAll = function() {
    $scope.user.roles = [];
    console.log($scope.user.roles);
    };
  $scope.checkFirst = function() {
    $scope.user.roles.splice(0, $scope.user.roles.length); 
    $scope.user.roles.push($scope.roles[0]);
    console.log($scope.user.roles);

  };

  //console.log($scope);

  $scope.animationsEnabled = true;

  $scope.open = function (size) {
    var modalInstance = $modal.open({
     
      animation: $scope.animationsEnabled,
      templateUrl: 'myModalContent.html',
      controller: 'ModalInstanceCtrl',
      size: size,
      resolve: {
        items: function () 
        {
          return $scope.items;
        }
      }
    });

    modalInstance.result.then(function (selectedItem) 
    {
      $scope.selected = selectedItem;
    }, function () {
        //$log.info('Modal dismissed at: ' + new Date());
    });

  };

  $scope.toggleAnimation = function ()
  {
    $scope.animationsEnabled = !$scope.animationsEnabled;
  };

}])


.controller('ModalInstanceCtrl',  ['$scope', '$modal','items',function ($scope, $modalInstance ,items){
  //$scope=this;
  $scope.items = items;
  
  console.log($scope.items);

  $scope.selected = {
    item: $scope.items
  };

  $scope.ok = function () {
    console.log('ok');
    $modalInstance.close($scope.selected.item);
  };

  $scope.cancel = function () {
    console.log('cancel');
    $modalInstance.dismiss('cancel');
  };
}]);




