var autorListController = function ($scope, ResAutor, $uibModal)
{
    $scope.autores = ResAutor.query();

    $scope.showFormModal = function (autor)
    {
        $uibModal.open({
            templateUrl: 'html/autor/form.html',
            controller: autorFormController, 
            resolve:
            {
                autor: autor || {}, 
                list: function() { return $scope.autores; }
            }
        });
    }
    
    $scope.eliminar = function(autor)
    {
        if(confirm('¿Realmente desea eliminar este autor?'))
        {
            ResAutor.delete({id: autor.id}, function(){
                $scope.autores.splice($scope.autores.indexOf(autor), 1);
            });
        }
    };
}

function autorFormController($scope, ResAutor, $uibModalInstance, autor, list)
{
    if(autor.id)
    {
        $scope.action = 'Editar';
        $scope.item = angular.copy(autor);
        $scope.save = function()
        {
            ResAutor.update({id: $scope.item.id}, $scope.item, function(){
                angular.extend(autor, $scope.item);
                $uibModalInstance.close();
            }, handleErrors);
        }
    }
    else
    {
        $scope.action = 'Agregar';
        $scope.item = {};
        $scope.save = function()
        {
            ResAutor.save($scope.item, function(response){
                $scope.item.id = response.id;
                list.push($scope.item);
                $uibModalInstance.close();
            }, handleErrors);
        };
    }
    
    $scope.cancel = function() { $uibModalInstance.close(); }

    function handleErrors(response)
    {
        if(response.status == 400) { $scope.inputs_with_errors = response.data; }
    }
}

app.controller('AutorListController', autorListController);
