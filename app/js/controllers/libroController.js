// List

var libroListController = function ($scope, ResLibro)
{
    $scope.libros = ResLibro.query();

    $scope.eliminar = function(libro)
    {
        if(confirm('¿Realmente desea eliminar este libro?'))
        {
            ResLibro.delete({id: libro.id}, function(response){
                $scope.libros.splice($scope.libros.indexOf(libro), 1);
            });
        }
    };
}

app.controller('LibroListController', libroListController);

// Ver

var libroVerController = function ($scope, ResLibro, $routeParams, ResExtras, ResEditorial)
{
    ResLibro.get({id: $routeParams.id}, function(response){
        $scope.libro = response;
        $scope.fecha_ingreso = new Date($scope.libro.fecha_ingreso).addDays(1);
        $scope.estados = ResExtras.query({id: 'libros_estado'});
        $scope.editoriales = ResEditorial.query();
    });
}

app.controller('LibroVerController', libroVerController);

// Form 

var libroFormController = function ($scope, ResLibro, ResExtras, $window, ResEditorial, ResAutor, $filter, $routeParams)
{
    var id = $routeParams.id;

    if(id)
    {
        $scope.action = 'Editar';
        ResLibro.get({id: id}, function(response)
        {
            $scope.item = response;
            $scope.autores_selecteds = response.autores;
            $scope.item.autores = $scope.autores_selecteds.map(a => a.id);
            $scope.fecha_ingreso = new Date($scope.item.fecha_ingreso).addDays(1);

            ResAutor.query(function(response)
            {
                $scope.autores = response.filter(obj => !$scope.item.autores.includes(obj.id));
            });

            $scope.save = function ()
            {
                setDataToItem();
                ResLibro.update({id: id}, $scope.item, function(response){
                    $window.location.href = 'libros';
                }, handleErrors);
            }
        });
    }
    else
    {
        $scope.action = 'Agregar';
        $scope.item = {};
        $scope.autores_selecteds = [];

        $scope.autores = ResAutor.query();

        $scope.save = function ()
        {
            setDataToItem();
            ResLibro.save($scope.item, function(response){
                $window.location.href = 'libros';
            }, handleErrors);
        };
    }

    function setDataToItem()
    {
        $scope.item.fecha_ingreso = $filter('date')($scope.fecha_ingreso, "dd/MM/yyyy");
        $scope.item.autores = $scope.autores_selecteds.map(a => a.id);
    }
    
    function handleErrors(response)
    {
        if(response.status == 400) { $scope.inputs_with_errors = response.data; }
    }
    
    // Common

    $scope.estados = ResExtras.query({id: 'libros_estado'});
    $scope.editoriales = ResEditorial.query();

    $scope.agregarAutor = function()
    {
        $scope.autores_selecteds.push(this.autor);
        $scope.autores.splice($scope.autores.indexOf(this.autor), 1);
    }

    $scope.eliminarAutor = function (autor)
    {
        $scope.autores.push(autor);
        $scope.autores_selecteds.splice($scope.autores_selecteds.indexOf(autor), 1);
    };

    // Hack to fix input date
    $scope.changeFechaIngreso = function()
    {
        $scope.fecha_ingreso = new Date(this.fecha_ingreso.getTime());
    };
}

app.controller('LibroFormController', libroFormController);
