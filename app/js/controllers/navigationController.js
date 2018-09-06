var navigationController = function ($scope, $location)
{
    $scope.isCurrent = function(ruta_actual)
    {
        return ruta_actual == $location.path();
    };
}

app.controller('NavigationController', navigationController);