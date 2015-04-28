// var eAndes=eAndes || {}

// eAndes.Cliente = Backbone.View.extend({
// 	el : '#eAndes',

// 	events: {
// 		//'click #crear': 'crearCliente'
// 		'submit form' : 'submitForm'
// 	},


// });

Cliente = Backbone.View.extend({
	events : {
		"click .js_edit" :  "editData"
	},	
	initialize : function(){
		// this.template = _.template( $("#school-template").html());
		this.template = swig.compile( $("#cliente-template").html());
	},
	render : function(){
		var data = this.model.toJSON();
		var html = this.template(data);
		this.$el.html( html);
	},
	editData : function () {
		var ruc = this.model.get('ruc');
		this.model.set('ruc', ruc);
		//this.model.save(null, {type: 'PUT'});
		this.model.save(null, {
			type:'PUT',
		    success: function (model, response, options) {
		        console.log(response);
		    },
		    error: function (model, xhr, options) {
		        // console.log(xhr.result.Errors);
		        console.log("Error");
		    }
		});
	}

})
