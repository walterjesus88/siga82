var app = angular.module('pruebaApp', [])
.controller('pruebaAppCtrl', controladorPrincipal);



app.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });

                event.preventDefault();
            }
        });
    };
});



app.directive("ltcEditable", function($document) {
  return {
    scope: {
      text: "=ngModel"
    },
    restrict: 'A', // restrict directive to use as attribute
    link: function (scope, element, attrs) {
      // restrict this directive to span elements
      if(element[0].nodeName !== 'SPAN') {
         return;
      }

      // store refererences
      var $ = angular.element;
      var body = $($document[0].body);
      var input = $('<input type="text"/>');

      // initial value
      element.text(scope.text);

      // shit happens
      element.on("dblclick", function() {
        // class name classOff will prevent editable behaviour if element has that class
        if(!element.hasClass(attrs['classOff'])) {
           // swap span for input
          input.val(element.text());
          element.parent().append(input);
          input[0].focus();
          element.text('');

          // nice ux :)
          input.on("keydown", function(event) {
            if(event.which == 13 || event.which == 27) {
              input.triggerHandler('blur');
            }
          });

          // handle click outside
          body.on('click', function(event) {
            if(event.target !== input[0]) {
              input.triggerHandler('blur');
            }
          });

          // on blur, store value and clean up after ourselves
          input.on('blur', function(event) {
            // unregister listeners!
            body.off();
            input.off();

            // set value - could validate before!
            element.text(input.val());

            // clean up
            input.remove();
          });
        }
      });
    }
  };
});

function controladorPrincipal(){
    var scope = this


      scope.roles = [
    {id: 1, text: 'guest'},
    {id: 2, text: 'user'},
    {id: 3, text: 'customer'},
    {id: 4, text: 'admin'}
  ];



  scope.user = {
    roles: [scope.roles[1]]
  };
  scope.checkAll = function() {
    scope.user.roles = angular.copy(scope.roles);
    console.log(scope.user.roles);
  };
  scope.uncheckAll = function() {
    scope.user.roles = [];
    console.log(scope.user.roles);
    };
  scope.checkFirst = function() {
    scope.user.roles.splice(0, scope.user.roles.length); 
    scope.user.roles.push(scope.roles[0]);
    console.log(scope.user.roles);
    
  };




      this.data = [
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


    scope.alumnos=[
	{nombre:"christel yussara",telefono:"9636699",curso:"segundo"},
	{nombre:"walter jesus",telefono:"9636699",curso:"primero"},
	{nombre:"sheyla pinto",telefono:"9636699",curso:"tercero"}

	];

	//scope.data = dataModel.data;
  	scope.newCategoryName = "";
  	scope.newTaskName = "";
  	scope.selectedCategory = scope.data[0];
  	scope.errorNewCategory = false;
  	scope.errorNewTask = false;

    this.algo = "Hola Angular, usando ssscontroller más simple";

    scope.total=0;
    scope.cuanto=0;

    scope.sumar=function(){
    	scope.total+=parseInt(scope.cuanto);
    	console.log("suma")
    }

    scope.restar=function(){
    	scope.total-=parseInt(scope.cuanto);
    	
    }    

	scope.ShowForm=function(){
		scope.formVisibility=true;
		//console.log(scope)
	}

	scope.Save=function(){
	scope.alumnos.push({nombre:scope.nuevoAlumnonombre,telefono:scope.nuevoAlumnotelefono,curso:scope.nuevoAlumnocurso});
	//scope.alumnos.push({nombre:'scope.nuevoAlumnonombre',telefono:'scope.nuevoAlumnotelefono',curso:'scope.nuevoAlumnocurso'});
	scope.formVisibility=false;
	console.log('agregando')

	}

	scope.toggleCategory = function(category) {
    	category.expanded = !category.expanded;
  	};

	scope.addCategory = function() {
	    if (scope.newCategoryName) {
		console.log("jjj");
	    var newCategory = { name: scope.newCategoryName, expanded: false };
	   	    newCategory.items = [];
	        scope.data.push(newCategory);
	        scope.errorNewCategory = false;
	    	} else {
	    	scope.errorNewCategory = true;
	     }
	  };


	scope.addTask = function() {
	    if (scope.newTaskName) {
	       console.log("ddd");
	      var newTask = { name: scope.newTaskName, completed: false };
	      scope.selectedCategory.items.push(newTask);
	      scope.selectedCategory.expanded=true;
	      scope.errorNewTask = false;
	    } else {
	      scope.errorNewTask = true;
	    }
	  };


 	scope.deleteTask = function(category, item) {
	    category.items.splice(category.items.indexOf(item), 1);
	};

	scope.completeTask = function(item) {
    	item.completed = true;
  	};

	scope.uncompleteTask = function(item) {
	    item.completed = false;
	};

  	scope.tamTitular = "titularpeq"
    scope.clases = ["uno", "dos", "tres"];

}














