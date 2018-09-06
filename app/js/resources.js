angular.module('app').service('config', function(){
	this.api_url = function()
	{
		if(IS_PRODUCTION) { return 'http://rondarav.epizy.com/'; }
		else { return 'http://localhost:88/patata2/'; }
	};
});

angular.module('app').factory('ResEditorial', ['$resource', 'config', function($resource, config){
    return $resource(config.api_url() + 'Editorial/:id', {id: '@_id'}, {update: {method: 'PUT'}});
}]);

angular.module('app').factory('ResAutor', ['$resource', 'config', function($resource, config){
    return $resource(config.api_url() + 'Autor/:id', {id: '@_id'}, {update: {method: 'PUT'}});
}]);

angular.module('app').factory('ResLibro', ['$resource', 'config', function($resource, config){
    return $resource(config.api_url() + 'Libro/:id', {id: '@_id'}, {update: {method: 'PUT'}/*, 
    get:{
        method: 'GET', 
        transformResponse: function (data) { return { responseData: data.toString() } }
    }*/
    });
}]);

angular.module('app').factory('ResExtras', ['$resource', 'config', function($resource, config){
    return $resource(config.api_url() + 'Extras/:id', {id: '@_id'});
}]);
