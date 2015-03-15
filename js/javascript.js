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
        var data = {};
        // data.clientName = document.querySelector('#organization').selectedItemLabel;
        // data.numberTo = document.querySelector('#numberTo').value;
        // data.message = document.querySelector('#message').value;
        // data.authKey = document.querySelector('#authKey').value;
        data.clientName = "CARE";
        data.numberTo = "9194484206";
        data.message = "Dance in the rain";
        data.authKey = "z8ZeEtN6Hs";

        alert(JSON.stringify(data));

        $http.post(URLSend, data).
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
  


