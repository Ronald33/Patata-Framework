angular.module('app').config(appConfig);

function appConfig($routeProvider, $locationProvider, $httpProvider)
{
    $routeProvider
    .when('/', 
    {
        controller: 'EditorialListController', 
        templateUrl: 'html/editorial/list.html'
    })
    .when('/editoriales', 
    {
        controller: 'EditorialListController', 
        templateUrl: 'html/editorial/list.html'
    })
    .when('/autores', 
    {
        controller: 'AutorListController', 
        templateUrl: 'html/autor/list.html'
    })
    .when('/libros', 
    {
        controller: 'LibroListController', 
        templateUrl: 'html/libro/list.html'
    })
    .when('/libros/nuevo',
    {
        controller: 'LibroFormController', 
        templateUrl: 'html/libro/form.html'
    })
    .when('/libros/ver/:id',
    {
        controller: 'LibroVerController', 
        templateUrl: 'html/libro/ver.html'
    })
    .when('/libros/editar/:id',
    {
        controller: 'LibroFormController', 
        templateUrl: 'html/libro/form.html'
    })
    .otherwise({
        redirectTo: '/'
    });

    $locationProvider.hashPrefix('');
    $locationProvider.html5Mode(true);
    
    $httpProvider.interceptors.push('interceptors');
    $httpProvider.interceptors.push('loadingInterceptors');
}
