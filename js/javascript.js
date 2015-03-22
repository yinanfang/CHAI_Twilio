// executes when HTML-Document is loaded and DOM is ready
$(document).ready(function () {

  var URLSend = "./twilio/twilio_send.php";

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
      $scope.request = {};
      $scope.request.doClick = function(item, event) {
        var data = {};
        // data.clientName = document.querySelector('#organization').selectedItemLabel;
        // data.numberTo = document.querySelector('#numberTo').value;
        // data.message = document.querySelector('#message').value;
        // data.authKey = document.querySelector('#authKey').value;
        data.From = "CARE";
        data.To = "+19194484206";
        data.Body = "Dance in the rain";
        data.AuthKey = "3pKtr0P1p9";
        data.MediaUrl = "http://upload.wikimedia.org/wikipedia/commons/e/e5/Vienna_Skyline.jpg";

        alert(JSON.stringify(data));

        

        $http.post(URLSend, $.param(data))
          .success(function(data, status, headers, config) {
            // this callback will be called asynchronously
            // when the response is available
          })
          .error(function(data, status, headers, config, statusText) {
            alert("somthing wrong..."+data+status);
          });




      };
    } );
});
  


