angular.module('userPage', [])
  .controller('UserPageCtrl', ['$scope', function($scope){
    $scope.memberList = '';
    $scope.memberSet = [];
    $scope.addMember = function($event){
      $event.preventDefault();
      $event.stopPropagation();
      if($scope.selectedMember !== undefined){
        var found = false;
        for(var i = 0; i < $scope.memberSet.length; ++i){
          if($scope.memberSet[i].localeCompare($scope.selectedMember) === 0){
            found = true;
          }
        }
        if(!found){
          $scope.memberList += $scope.selectedMember + '\n';
          $scope.memberSet.push($scope.selectedMember);
        }
      }
    }
    $scope.removeLastMember = function($event){
      $event.preventDefault();
      $event.stopPropagation();
      // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/lastIndexOf
      var index = $scope.memberList.slice(0, -1).lastIndexOf('\n');
      $scope.memberList = $scope.memberList.slice(0, index + 1);
      $scope.memberSet = $scope.memberSet.slice(0, -1);
    }
  }]);