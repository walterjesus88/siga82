var app = app || {};

Prueba.Views.App = Backbone.View.extend({
	//el: '#app',

	events : {
		"click .newschool" : "showModal",
		"submit form" : "createSchool"
	},

	initialize : function ($el) {
		this.$el = $el;	
	},
		// this.template = _.template( $("#school-template").html());
	
	showModal : function () {
		console.log('akiiiiiiiii');
		this.$el.find('form').slideToggle();
	},



	createSchool : function (e) {
		e.preventDefault();

		var escid = $('input[name=inputescid]').val();
		var name = $('input[name=inputname]').val();

		var data = {
			"eid"   		: "20154605046",
			"oid"   		: "1",
			"facid" 		: "4",
			"escid" 		: escid,
			"name"      	: name,
			"register"  	: "Administrador",
			"state"			: "A",
			"created"		: "2015-02-16"
		};

		var model = new Prueba.Models.School(data);
		// model.save();
		model.save(null, {
			type:'POST',
		    success: function (model, response, options) {
		        if (response.status=="OK"){		
		        	model.set({
						"facid" : "Ingenieria",
						"register" : "Administrador",
						"state" : "Activo"
					});
					var	view =  new Prueba.Views.School({ model : model });
					view.render();
					view.$el.prependTo('.vistas_schools');
				}			        
		    },
		    error: function (model, xhr, options) {
		        console.log("Error");
		        //console.log(xhr);
		    }
		});			
	}
});



// app.MostrarLibroView = Backbone.View.extend({
// 	template: swig.compile($('#school-template').html()),

// 	tagName: 'li',
// 	className: 'list-group-item',


// 	initialize: function() {
// 		var self = this;
// 		 	self.render();
// 	},

// 	render: function() {
// 		var self = this;
// 		this.$el.html( this.template( this.model.toJSON() ) );
	
// 		return this;
// 	},


// });