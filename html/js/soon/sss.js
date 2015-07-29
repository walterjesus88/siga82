function AlumnosController($scope){
	$scope.alumnos=[
	{nombre:"christel yussara",telefono:"9636699",curso:"segundo"},
	{nombre:"walter jesus",telefono:"9636699",curso:"primero"},
	{nombre:"sheyla pinto",telefono:"9636699",curso:"tercero"}

	];

$scope.Save=function(){
$scope.alumnos.push({nombre:$scope.nuevoAlumno.nombre,telefono:$scope.nuevoAlumno.telefono,curso:$scope.nuevoAlumno.curso});
$scope.formVisibility=false;
console.log($scope.formVisibility)


}

$scope.ShowForm=function(){
	$scope.formVisibility=true;
	console.log($scope.formVisibility)
}


}
