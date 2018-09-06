var editorialListController = function ($scope, ResEditorial, $uibModal)
{
    $scope.editoriales = ResEditorial.query();

    $scope.showFormModal = function (editorial)
    {
        $uibModal.open({
            templateUrl: 'html/editorial/form.html',
            controller: editorialFormController, 
            resolve:
            {
                editorial: editorial || {}, 
                list: function() { return $scope.editoriales; }
            }
        });
    }
    
    $scope.eliminar = function(editorial)
    {
        if(confirm('¿Realmente desea eliminar esta editorial?'))
        {
            ResEditorial.delete({id: editorial.id}, function(){
                $scope.editoriales.splice($scope.editoriales.indexOf(editorial), 1);
            });
        }
    };
}

function editorialFormController($scope, ResEditorial, $uibModalInstance, editorial, list)
{
    if(editorial.id)
    {
        $scope.action = 'Editar';
        $scope.item = angular.copy(editorial);
        $scope.save = function()
        {
            ResEditorial.update({id: $scope.item.id}, $scope.item, function(){
                angular.extend(editorial, $scope.item);
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
            ResEditorial.save($scope.item, function(response){
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

app.controller('EditorialListController', editorialListController);
