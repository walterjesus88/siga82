// var eAndes=eAndes || {}

// ClientesCollection = Backbone.Collection.extend({
// 	url : '/rest/cliente',
// 	model : eAndes.Cliente
// });

// eAndes.clientes= new ClientesCollection();


Prueba.Collections.Cliente = Backbone.Collection.extend({
	model : Prueba.Models.Cliente,
	url : '/cliente/'
});