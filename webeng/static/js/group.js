angular.module('groupPage', [])
  .controller('EditGroupDetailsCtrl', ['$scope', function($scope){
    $scope.showEditForm = false;
    $scope.showEditFormMsg = 'Show';
    $scope.showForm = function(){
      $scope.showEditForm = true;
      $scope.showEditFormMsg = 'Hide';
      $scope.formShowButtomAction = $scope.hideForm;
    }
    $scope.hideForm = function(){
      $scope.showEditForm = false;
      $scope.showEditFormMsg = 'Show';
      $scope.formShowButtomAction = $scope.showForm;
    }
    $scope.formShowButtomAction = $scope.showForm;
    $scope.members = [];
    $scope.memberList = '';
    $scope.$watch('members', function(){
      $scope.memberList = '';
      $scope.members.forEach(function(val){
        $scope.memberList += val + '\n';
      });
    });
    $scope.addMember = function($event){
      $event.preventDefault();
      $event.stopPropagation();
      if($scope.selectedMember !== undefined){
        var found = false;
        for(var i = 0; i < $scope.members.length; ++i){
          if($scope.members[i].localeCompare($scope.selectedMember) === 0){
            found = true;
          }
        }
        if(!found){
          $scope.memberList += $scope.selectedMember + '\n';
          $scope.members.push($scope.selectedMember);
        }
      }
    };
    $scope.removeLastMember = function($event){
      $event.preventDefault();
      $event.stopPropagation();
      // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/lastIndexOf
      var index = $scope.memberList.slice(0, -1).lastIndexOf('\n');
      $scope.memberList = $scope.memberList.slice(0, index + 1);
      $scope.members = $scope.members.slice(0, -1);
    };
  }]);