//var app = app || {};

Prueba.Collections.School = Backbone.Collection.extend({
	model : Prueba.Models.School,
	  url : '/restschool/'
});

//app.schools = new Prueba.Collections.School();