// executes when HTML-Document is loaded and DOM is ready
$(document).ready(function () {

  var URLSend = "./twilio_send.php";

  $('body').addClass('loaded');

  // Loading screen
  // setTimeout(function(){
  //   $('body').addClass('loaded');
  //   // $("paper-button[raised]").css({
  //   //   "background": "green"
  //   // });
  // }, 2000);


  // control.html
  angular.module("controlApp", [])
    .controller("controller", function($scope, $http) {
      $scope.inputData = {};
      $scope.inputData.doClick = function(item, event) {
        var responsePromise = $http.get("/angularjs-examples/json-test-data.jsp");
        responsePromise.success(function(data, status, headers, config) {
          $scope.myData.fromServer = data.title;
        });
        responsePromise.error(function(data, status, headers, config) {
          alert("AJAX failed! " + $scope.numTo);
        });

        $http.post(URLSend, {msg:'hello word!'}).
          success(function(data, status, headers, config) {
            // this callback will be called asynchronously
            // when the response is available
          }).
          error(function(data, status, headers, config) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
          });

      };
    } );
});
  


