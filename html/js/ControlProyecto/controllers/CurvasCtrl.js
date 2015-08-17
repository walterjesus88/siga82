
// app.controller('CurvasCtrl', ['httpFactory','transmittalFactory','proyectoFactory','$scope',
//  function ( httpFactory,transmittalFactory,proyectoFactory, $scope ) {


app.controller('CurvasCtrl', ['httpFactory', 'proyectoFactory', '$scope',
'transmittalFactory',
function(httpFactory, proyectoFactory, $scope, transmittalFactory) {

  var $scope = this;


  console.log("estoy en curvas");
  console.log($scope);

  $scope.cuanto=['29'];

  $scope.tabla_activa = 'active';
  $scope.trans_activo = '';

  var cambiarSubPanel = function(panel) {
    if (panel == 'tablas') {
      $scope.tabla_activa = 'active';
      $scope.trans_activo = '';
    } else if (panel == 'trans') {
      $scope.tabla_activa = '';
      $scope.trans_activo = 'active';
    }
  }

 //estado de los paneles de la vista
  $scope.edt_activo = '';
  $scope.tecnicos_activo = 'active';
  $scope.gestion_activo = '';
  $scope.comunicacion_activo = '';

  //cambio de panel visible segun menu seleccionado
  $scope.cambiarPanel = function(panel) {
    if (panel == 'edt') {
      $scope.edt_activo = 'active';
      $scope.tecnicos_activo = '';
      $scope.gestion_activo = '';
      $scope.comunicacion_activo = '';
    } else if (panel == 'tecnicos') {
      $scope.edt_activo = '';
      $scope.tecnicos_activo = 'active';
      $scope.gestion_activo = '';
      $scope.comunicacion_activo = '';
    } else if (panel == 'gestion') {
      $scope.edt_activo = '';
      $scope.tecnicos_activo = '';
      $scope.gestion_activo = 'active';
      $scope.comunicacion_activo = '';
    } else if (panel == 'comunicacion') {
      $scope.edt_activo = '';
      $scope.tecnicos_activo = '';
      $scope.gestion_activo = '';
      $scope.comunicacion_activo = 'active';
    }
  }



  //var vt = this;
  // $scope.proyecto = {
  //   codigo: $routeParams.proyecto,
   
  // };

  // proyectoFactory.getDatosProyecto($scope.proyecto.codigo)
  // .then(function(data) {
  //   $scope.proyecto = data;     
  // })
  // .catch(function(err) {
  //   alert('No se pudo cargar los datos del proyecto');
  // });

  // console.log($scope.proyecto.codigo);
  // console.log($scope.proyecto.codigo_prop_proy);

  
  //guardando columnas//
  $scope.saveColumn= function(column){
    //console.log($scope.dat);

    // var results = [];
    angular.forEach($scope.dat, function(fecha) {  
      //a=results.push($http.post('/saveColumn', {column: column, value: fecha[column], id: fecha.id_tproyecto}));
    httpFactory.setCambiarfechaproyecto(fecha[column],column,fecha.id_tproyecto)
        .then(function(data) {
          console.log('Curvas cambiado');
        })
        .catch(function(err) {
          console.log('No se pudo cambiar Curvas');
        })
    })
    //return $q.all(results);   
  };


  $scope.saveUser_l = function(data,id) {
    //console.log(data);
    //console.log(id);
    //angular.extend(data, {id: id});
    //return $http.post('/saveUser', data);
  };

  $scope.checkName = function(data) {
    //console.log('data');
    // if (data !== 'awesome') {
    //   return "Username should be `awesome`";
    // }
  };

  $scope.revision=[];

  
  
  $scope.busca = function(revision) {
    //$scope.revision=revision;
      console.log(revision);
      //console.log($scope.proyecto.codigo);

      httpFactory.getTiempos(revision)
      .success(function(data) { 

        console.log(data);


        $scope.dat=data[0]['1'];
        //console.log($scope.dat);
        var max = data[0]['1'].length;     
        var varx=[];
        var vary=[];
        var labelx=[];

        var label= $.map(data[0], function(value, index) {   
            for (var i =0; i < max; i++) {
              a=[];
              //console.log(value[i]['porc_avance_plani']);
              a=value[i]['fecha_proyecto'];        
              labelx.push(a);
            };
              return [labelx];
        });    
        $scope.labels=label[0];
        console.log($scope.labels);

        var array = $.map(data[0], function(value, index) {   
            for (var i =0; i < max; i++) {
              a=[];
              //console.log(value[i]['porc_avance_plani']);
              a=parseFloat(value[i]['porc_avance_real']);
              b=parseFloat(value[i]['porc_avance_plani']);
              varx.push(a);
              vary.push(b);

            };
              return [varx,vary];
        });
        $scope.data = array;
        console.log($scope.data[0] );   
       
       })
      .error(function(data) {
         $scope.data = [] ; 
      });

  };
  
  //console.log($scope.revision);

  // $scope.labels = ['29 Abr', '14 May', '21 May', '28 May', '04 Jun', '11 Jun', '18 Jun','25 Jun','02 Jun',];
  // console.log($scope.labelss);

  $scope.series = ['Planeado', 'Real'];
  $scope.options = {
      legend: true,
      animationSteps: 150,
      animationEasing: "easeInOutQuint"
    };

  // $scope.data = [
  //   [65/100, 59/100, 80/100, 81/100, 56/100, 55/100, 40/100],
  //   [28/100, 48/100, 40/100, 19/100, 86/100, 27/100, 90/100]
  // ];
  // console.log($scope.datas);  
  //  $scope.data = [] ; 
   //ejemplo de objeto// 
    //var myObj = {
    // 1: [1, 2, 3],
    // 2: [4, 5, 6]
    // };
}])


