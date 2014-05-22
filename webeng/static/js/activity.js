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
      $scope.searching = false;
      $scope.searchImages = function(){
        $scope.searching = true;
        $http({
          url : '/flickr/',
          method : 'POST',
          data : JSON.stringify({
            tags : $scope.tags
          })
        }).success(function(data, status, headers){
          $scope.searching = false;
          $scope.image_chunks = [];
          data.photos.forEach(function(ele, idx){
            if(idx % 6 === 0){
              $scope.image_chunks.push([]);
            }
            $scope.image_chunks[$scope.image_chunks.length - 1].push(ele);
          });
        });
      };
    } ]);