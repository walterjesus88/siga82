Prueba.Routers.Base = Backbone.Router.extend({
	routes : {
		//'' : 'index',
		'default' : 'school',
		//'register/preregister' : 'preregister'
	},

	initialize : function(){
		Backbone.history.start({
			root      : '/',
			pushState : true
		});
	},


	index : function(){
		console.log('estoy en mi index');
	},

	school : function(){
		viw = new Prueba.Views.App( $('main') );
		var school = new Prueba.Collections.School();
		school.on('add', function( model ){
	
			var	view =  new Prueba.Views.School({ model : model });
		 	view.render();
		 	view.$el.prependTo('.vistas_schools');
	    });
		school.fetch();

	},
});

