
Prueba.Views.School = Backbone.View.extend({
	events : {
		"click .js_edit" :  "editData"
	},	
	initialize : function(){
		// this.template = _.template( $("#school-template").html());
		this.template = swig.compile( $("#school-template").html());
	},
	render : function(){
		var data = this.model.toJSON();
		var html = this.template(data);
		this.$el.html( html);
	},

	editData : function () {
		var escid = this.model.get('escid');
		this.model.set('escid', escid);
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

});

