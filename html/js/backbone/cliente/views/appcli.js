
Prueba.Views.Appcli = Backbone.View.extend({

	events : {
		"click .newcliente" : "showModal",
		"submit form" : "createCliente"
	},

	// render : function(){
	// 	this.$el.html('ddddd');
	// },

	initialize : function ($el) {
		this.$el = $el;	
	},
	showModal : function () {
		//console.log('dddd');
		this.$el.find('form').slideToggle();
	},
	createCliente : function (e) {
		e.preventDefault();

		var ruc = $('input[name=inputruc]').val();
		var nombre = $('input[name=inputnombre]').val();
		var descripcion = $('input[name=inputdescripcion]').val();

		var data = {			
			"ruc" 		    : ruc,
			"nombre"      	: nombre,
			"descripcion"   : descripcion,
			
		};

		console.log('en createCliente');

		var model = new Prueba.Models.Cliente(data);
		// model.save();
		model.save(null, {
			type:'POST',
		    success: function (model, response, options) {
		        if (response.status=="OK"){		
		        	model.set({
						
						"estado" : "1"
					});
					console.log('fffffff');
					var	view =  new Prueba.Views.Cliente({ model : model });
					view.render();
					view.$el.prependTo('.vistas_clientes');
				}			        
		    },
		    error: function (model, xhr, options) {
		        console.log("Error");
		        //console.log(xhr);
		    }
		});			
	}
});