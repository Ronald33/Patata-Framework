angular.module('app').factory('interceptors', function($q){
	
	return {
		responseError: function(response){
			var msg;
			switch(response.status)
			{
				case 400: msg = 'Ocurrio un error en la petición'; break;
				case 401: msg = 'No se tiene autorización para acceder a este recurso'; break;
				case 403: msg = 'No se tiene los permisos suficientes'; break;
				case 404: msg = 'Recurso no encontrado'; break;
				case 409: msg = 'Ocurrió un conflicto con su petición'; break;
				case 500: msg = 'Error en el servidor'; break;
				case 501: msg = 'Método no implementado'; break;
				default: msg = 'Ocurrió un error';
			};
			alert(msg);
			
			return $q.reject(response);
		}
	};
});

angular.module('app').factory('loadingInterceptors', function($q, $rootScope){
	
	var loader = 0;
	
	function updateStatus()
	{
		$rootScope.loading = loader != 0;
	}	
	
	return {
		request: function(config)
		{
			loader++;
			updateStatus();
			return config;
		}, 
		requestError: function(rejection)
		{
			loader--;
			updateStatus();
			return $q.reject(rejection);
		}, 
		response: function(response)
		{
			loader--;
			updateStatus();
			return response;
		},
		responseError: function(rejection)
		{
			loader--;
			updateStatus();
			return $q.reject(rejection);
		}
	};
});
