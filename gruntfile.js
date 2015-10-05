module.exports= function(grunt)
{
	grunt.initConfig({
		jshinit:{
			all:['script.js']
		}
	});
	grunt.loadNpmTasks('grunt-contrib-jshinit');
	grunt.registerTask('default',['jshinit']);
};