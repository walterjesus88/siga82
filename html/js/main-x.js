$(function() {	
	console.log('hh');

	window.views.app = new Prueba.Views.App( $('main') );
	window.collections.schools = new Prueba.Collections.School();
	window.collections.schools.on('add', function( model ){
		model.set({
			"facid" : "Ingenieria",
			"register" : "Administrador",
			"state" : "Activo"
		});
	 	var	view =  new Prueba.Views.School({ model : model });

	 	view.render();
	 	view.$el.prependTo('.vistas_schools');
	 });
	window.collections.schools.fetch();

})