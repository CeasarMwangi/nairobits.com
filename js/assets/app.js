(function(){
	angular.module('assetsApp',[])
	
	//define controller and inject usersService service and scope as dependencies.
	.controller('assetsController',['assetsService','$scope',function(usersService,$scope){ 
		usersService.getAssets().then(function(response){ 
			//console.log(response.message);
			$scope.assets = response.message; //Assign data received to $scope.users
		});
        //this is our custom filter
        $scope.searchFilter = function (asset) {
            var re = new RegExp($scope.assetFilter, 'i');
            return !$scope.assetFilter || re.test(asset.full_name) || re.test(asset.username);
        };
	}])
	.service(
            "assetsService",['$http','$q',
            function( $http, $q ) {
                // Return public API.
                return({
                    addAsset: addAsset,
                    getAssets: getAssets,
                    removeAsset: removeAsset,
                    updateAsset: updateAsset
                });
                // ---
                // PUBLIC METHODS.
                // ---
                // I add a friend with the given name to the remote collection.
                function addAsset( name ) {
                    var request = $http({
                        method: "put",
                        url: "api/index.cfm",
                        params: {
                            action: "add"
                        },
                        data: {
                            name: name
                        }
                    });
                    return( request.then( handleSuccess, handleError ) );
                }
                // I get all of the friends in the remote collection.
                function getAssets() {
                    var request = $http({
                        method: "get",
                        url: "http://localhost/nairobits.com/api/assets",
                        params: {
                            action: "get"
                        }
                    });
                    return( request.then( handleSuccess, handleError ) );
                }

                // I update the user with the given ID from the remote collection.
                function updateAsset( id ) {
                    var request = $http({
                        method: "post",
                        url: "api/index.cfm",
                       

                        params: {
                            action: "post"
                        },
                        data: {
                            asset_id: id
                        }
                    });
                    return( request.then( handleSuccess, handleError ) );
                }
                // I delete/remove the user with the given ID from the remote collection.
                function removeAsset( id ) {
                    var request = $http({
                        method: "delete",
                        url: "api/index.cfm",
                        params: {
                            action: "delete"
                        },
                        data: {
                            asset_id: id
                        }
                    });
                    return( request.then( handleSuccess, handleError ) );
                }
                // ---
                // PRIVATE METHODS.
                // ---
                // I transform the error response, unwrapping the application data from
                // the API response payload.
                function handleError( response ) {
                    // The API response from the server should be returned in a
                    // nomralized format. However, if the request was not handled by the
                    // server (or whas not handled properly - ex. server error), then we
                    // may have to normalize it on our end, as best as we can.
                    if (
                        ! angular.isObject( response.data ) ||
                        ! response.data.message
                        ) {
                        return( $q.reject( "An unknown error occurred." ) );
                    }
                    // Otherwise, use expected error message.
                    return( $q.reject( response.data.message ) );
                }
                // I transform the successful response, unwrapping the application data
                // from the API response payload.
                function handleSuccess( response ) {
                    return( response.data );
                }
            }])
})();