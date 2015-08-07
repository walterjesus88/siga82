
angular.module('moduloCp', ['ngRoute', 'chart.js','ui.bootstrap','ui.bootstrap.tpls','ui.router','checklist-model','dialogs','xeditable'])
.config(['$routeProvider', function($routeProvider) {
  $routeProvider
  .when("/", {
    controller: "PanelCtrl",
    controllerAs: "CD",
    templateUrl: "/proyecto/index/panel"
  })
  .when("/curvas", {
    controller: "CurvasCtrl",
    controllerAs: "CP",
    templateUrl: "/control/control/curvas"
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

//theme bootstrap //
.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
})

.controller('CurvasCtrl', ['httpFactory', function ( httpFactory ) {
  //referencia del scope
  var $scope = this;

  $scope.cuanto=['29'];

  $scope.sumar= function(){
    
  }

  $scope.labels = ['06 May', '13 May', '20 May', '27 May', '03 Jun', '10 Jun', '15 Jun'];
  $scope.series = ['Planeado', 'Ejecutado'];

  $scope.data = [
    [65/100, 59/100, 80/100, 81/100, 56/100, 55/100, 40/100],
    [28/100, 48/100, 40/100, 19/100, 86/100, 27/100, 90/100]
  ];

}])

.controller('PanelCtrl', ['httpFactory','$modal','$dialogs', function ( httpFactory,$modal,$dialogs) {
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
        //console.log("cvbn"+cd.selectedCategory.items);
          console.log(cd.selectedCategory);
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


  cd.ver= function(item) {
    console.log('s');
  //     console.log(cd.role.uid);
  //     $scope.playList = $filter('filter')($scope.selectedAlbumSongs, {checked: true});
  //     console.log($scope.playList);
  };


  // cd.isChecked = function(id){

  //     var match = false;
  //     for(var i=0 ; i < cd.roles.length; i++) {
  //       if(cd.roles[i].id == id){
  //         match = true;
  //       }
  //     }

  //    // console.log(match);
  //     return match;
  // };

  // cd.sync = function(bool, role){  
  //   if(bool){
  //     //console.log(bool);
  //     //console.log(role);
  //     // add item
  //     //cd.roles.push(role);
  //   } else {
  //     // remove item
  //     for(var i=0 ; i < cd.roles.length; i++) {
  //       //console.log(cd.roles[i].id);
  //       if(cd.roles[i].id == role.id){
  //         cd.roles.splice(i,1);
  //       }
  //     }      
  //   }
  // };
  



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
    console.log(cd.integrantes);
    cd.selectedCategory = cd.integrantes[0];
   })
  .error(function(res) {
    cd.integrantes = [];
  });

  cd.newAreaName = "";
  cd.newTaskName = "";
 
  cd.errorNewCategory = false;
  cd.errorNewTask = false;


  var $scope=this;

 httpFactory.getUsuarios()
  .success(function(res) {
    $scope.roles = res; 
    //console.log($scope.roles);
  
   })
  .error(function(res) {
    $scope.roles= [];
  });

  //fin de factory para llamar datos




  $scope.confirmed = 'You have yet to be confirmed!';
  $scope.name = '"Your name here."';

  cd.launch = function(which,integrante){
    var dlg = null;
    switch(which){

      case 'confirm':

        //console.log(which,integrante);
        dlg = $dialogs.confirm('Please Confirm','Is this awesome or what?');
        dlg.result.then(function(btn){
          $scope.confirmed = 'You thought this quite awesome!';
        },function(btn){
          $scope.confirmed = 'Shame on you for not thinking this is awesome!';
        });


        $scope.animationsEnabled = true;       
        var modalInstance = $modal.open({
            animation: $scope.animationsEnabled, 
            controller: 'ModalInstanceCtrl', 
            templateUrl: 'myModalContent.html',
            resolve: {
              users: function () 
              {                
                console.log(integrante);
                //console.log($scope.roles);
                return $scope.roles;
              },

              integrantes: function () 
              {                
                return integrante;
                
                //console.log('llego');
                //console.log($scope.integrantes);
                //return $scope.integrantes;
              }
            }

        // modalInstance.result.then(function (selectedItem) 
        // {
        //   $scope.selected = selectedItem;
        // }, function () {
        //     //$log.info('Modal dismissed at: ' + new Date());
        // });


          });

        $scope.toggleAnimation = function ()
        {
          $scope.animationsEnabled = !$scope.animationsEnabled;
        };

        break;

   // Create Your Own Dialog
      case 'create':
        console.log('create');
      
        dlg = $dialogs.create('/dialogs/whatsyourname.html','whatsYourNameCtrl',{},{key: false,back: 'static'});
        dlg.result.then(function(name){
          $scope.name = name;
        },function(){
          $scope.name = 'You decided not to enter in your name, that makes me sad.';
        });
        
        break;

    }; // end switch
  }; // end launch
}])


//.controller('ModalDemoCtrl', ['$scope', '$modal','httpFactory', function ( $scope, $modal , httpFactory) {


 // httpFactory.getUsuarios()
 //  .success(function(res) {
 //    $scope.roles = res; 
  
 //   })
 //  .error(function(res) {
 //    $scope.roles= [];
 //  });


  // $scope.items=[
  // {nombre:"xxxx 1"},
  // {nombre:"walter jesus"},
  // {nombre:"ss"}];

      // $scope.roles = [
      //   {id: 1, text: 'guest'},
      //   {id: 2, text: 'user'},
      //   {id: 3, text: 'customer'},
      //   {id: 4, text: 'admin'}
      // ];

  // $scope.animationsEnabled = true;

  // $scope.open = function (size) {
  //   var modalInstance = $modal.open({
  //     animation: $scope.animationsEnabled,
  //     templateUrl: 'myModalContent.html',
  //     controller: 'ModalInstanceCtrl',
  //     //controller: 'PanelCtrl',
  //     size: size,
  //     resolve: {
  //       items: function () 
  //       {          
  //         //console.log($scope.roles);
  //         return $scope.roles;
  //       }
  //     }
  //   });

    // modalInstance.result.then(function (selectedItem) 
    // {
    //   $scope.selected = selectedItem;
    // }, function () {
    //     //$log.info('Modal dismissed at: ' + new Date());
    // });

  //};

  // $scope.toggleAnimation = function ()
  // {
  //   $scope.animationsEnabled = !$scope.animationsEnabled;
  // };
//}])


.controller('ModalInstanceCtrl',  ['$scope', '$modal','users','integrantes',function ($scope, $modalInstance ,users,integrantes){

  $scope.roles = users;
  $scope.integrantes = integrantes;

  console.log($scope.roles); //son las personas //
  console.log($scope.integrantes); //es el areaid //


  $scope.user = {
    roles: []
  };

  // $scope.user = {
  //   roles: [$scope.roles[1]]
  // };

  //console.log($scope.user);

  // $scope.avisar = function(){
  //   //console.log($scope.user.roles);
  //   console.log($scope.role.uid);
  //   console.log("avisar");
  // }


  // $scope.isChecked = function(id){
  //    //console.log(id);

  //     var match = false;
  //     for(var i=0 ; i < $scope.roles.length; i++) {

  //       console.log($scope.roles.length);
        
  //       if($scope.roles[i].id == id){
  //         match = true;
  //         console.log(match);
  //       }
  //     }

  //     return match;
  // };

  $scope.sync = function(bool, role){  
    if(bool){
      //console.log(bool);
      //console.log(role);
      role_uid=role['uid'];
      // add item
      //console.log(role_uid);
      var agregar_persona = { uid: role_uid, completed: false };
      $scope.integrantes.items.push(agregar_persona);

    }
    else
    {
      console.log('hhhhhh');
          console.log($scope.integrantes.items);
          console.log($scope.integrantes.items.length);

        for(var i=0 ; i < $scope.integrantes.items.length; i++) 
        {

          if($scope.integrantes.items[i].uid == role.uid)
          {
            console.log($scope.integrantes.items[i]);  
            console.log(i);

            $scope.integrantes.items.splice(i,1);

          }
        }  
    }
  }

  $scope.checkAll = function() {
    $scope.user.roles = angular.copy($scope.roles);
    //console.log($scope.user.roles);

       for (var i = 0; i < $scope.user.roles.length; i++) {
          console.log($scope.user.roles[i].uid);

          uid_i=$scope.user.roles[i].uid;

          var newTaske = { uid: uid_i, completed: false };

          //console.log($scope.integrantes[0]);
          $scope.integrantes.items.push(newTaske);

       }



  };
  $scope.uncheckAll = function() {
    $scope.user.roles = [];
    console.log($scope.user.roles);
    $scope.integrantes.items.splice($scope.integrantes.items);
    //$scope.integrantes.items.push( $scope.user.roles);
    };

  $scope.checkFirst = function() {
    $scope.user.roles.splice(0, $scope.user.roles.length);
    $scope.user.roles.push($scope.roles[0]);

    console.log($scope.user.roles[0]['uid']);
    console.log($scope.user.roles[0]);
    uid_i= $scope.user.roles[0]['uid'];
    //console.log($scope.user.roles);
    var newTaske = { uid: uid_i, completed: false };

    //console.log($scope.integrantes[0]);
    $scope.integrantes.items.push(newTaske);
   // $scope.integrantes.push(newTaske);

  };


  $scope.ok = function () {
    console.log('ok');
    $modalInstance.close($scope.selected.item);
  };

  $scope.cancel = function () {
    console.log('cancel');
    $modalInstance.dismiss('cancel');
  };
}])




// .controller('whatsYourNameCtrl',function($scope,$modalInstance,data){
//   $scope.user = {name : ''};

//   $scope.cancel = function(){
//     $modalInstance.dismiss('canceled');  
//   }; // end cancel
  
//   $scope.save = function(){
//     $modalInstance.close($scope.user.name);
//   }; // end save
  
//   $scope.hitEnter = function(evt){
//     if(angular.equals(evt.keyCode,13) && !(angular.equals($scope.name,null) || angular.equals($scope.name,'')))
//         $scope.save();
//   }; // end hitEnter
// }) // end whatsYourNameCtrl


// .run(['$templateCache',function($templateCache){
//   $templateCache.put('/dialogs/whatsyourname.html','<div class="modal"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"><span class="glyphicon glyphicon-star"></span> User\'s Name</h4></div><div class="modal-body"><ng-form name="nameDialog" novalidate role="form"><div class="form-group input-group-lg" ng-class="{true: \'has-error\'}[nameDialog.username.$dirty && nameDialog.username.$invalid]"><label class="control-label" for="username">Name:</label><input type="text" class="form-control" name="username" id="username" ng-model="user.name" ng-keyup="hitEnter($event)" required><span class="help-block">Enter your full name, first &amp; last.</span></div></ng-form></div><div class="modal-footer"><button type="button" class="btn btn-default" ng-click="cancel()">Cancel</button><button type="button" class="btn btn-primary" ng-click="save()" ng-disabled="(nameDialog.$dirty && nameDialog.$invalid) || nameDialog.$pristine">Save</button></div></div></div></div>');
// }]); // end run / module

