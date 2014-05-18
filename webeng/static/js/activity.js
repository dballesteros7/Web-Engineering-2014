angular.module('activityPage', []).controller(
    'ActivityPageCtrl',
    [ '$scope', '$http', function($scope, $http){
      $scope.showEditForm = false;
      $scope.showEditFormMsg = 'Show';
      $scope.showForm = function(){
        $scope.showEditForm = true;
        $scope.showEditFormMsg = 'Hide';
        $scope.formShowButtomAction = $scope.hideForm;
      };
      $scope.hideForm = function(){
        $scope.showEditForm = false;
        $scope.showEditFormMsg = 'Show';
        $scope.formShowButtomAction = $scope.showForm;
      };
      $scope.formShowButtomAction = $scope.showForm;
    } ]).controller('AddImageCtrl',
    [ '$scope', '$http', '$location', function($scope, $http, $location){
      $scope.tags = '';
      $scope.encodeURIComponent = encodeURIComponent;
      $scope.searchImages = function(){
        $http({
          url : '/flickr/',
          method : 'POST',
          data : JSON.stringify({
            tags : $scope.tags
          })
        }).success(function(data, status, headers){
          $scope.images = data.photos;
        });
      };
    } ]);