define(function() {
	'use strict';

	function ctrl($rootScope, $scope, $http) {

		$scope.feed = [];
		$scope.load = function(){
			$http.get('api/v1/newsfeed').success(function(data){
				$scope.feed = data.activity;
			});
		};
		$scope.load();


		$scope.data = {};
		$scope.post = function(){
			$http.post('api/v1/newsfeed', $scope.data).success(function(){
			$scope.load();
			});
		};

	}


	ctrl.$inject = ['$rootScope', '$scope', '$http'];
	return ctrl;

});