app.controller('AdminCtrl', ['$scope','httpFactory', 'adminFactory',
    function($scope,httpFactory, adminFactory) {
    var va = this;
    //va.proyectos = [];
    //va.control_documentario = [];
    //va.variable=[{nombre:"jesus",texto:"ffff"},{nombre:"walter",texto:"sssss"}];
    //$scope.name = 'Pablo';

    adminFactory.getDatosxACL()
        .then(function(data) {
            va.usuarios_acl=data;
            console.log(va.usuarios_acl);
            //console.log('va.usuarios_acl');
        })
        .catch(function(err) {
            va.usuarios_acl = {};
  });

}]);

