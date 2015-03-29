// executes when HTML-Document is loaded and DOM is ready
$(document).ready(function () {
  // Variables
  var TwilioSend = "./twilio/twilio_send.php";
  var TwilioQuery = "./twilio/twilio_query.php";

  // Only hide the loading screen for current app
  if (document.getElementsByTagName("html")[0].getAttribute("ng-app") == "adminApp") {
    $('body').addClass('loaded');
  }
 
  // control.html
  angular.module("controlApp", [])
    .controller("controller", function($scope, $http) {
      // Only hide the loading screen for current app
      if (document.getElementsByTagName("html")[0].getAttribute("ng-app") == "controlApp") {
        $('body').addClass('loaded');
      }

      $scope.request = {};
      $scope.request.doClick = function(item, event) {
        var data = {};
        // data.clientName = document.querySelector('#organization').selectedItemLabel;
        // data.numberTo = document.querySelector('#numberTo').value;
        // data.message = document.querySelector('#message').value;
        // data.authKey = document.querySelector('#authKey').value;
        // data.From = "CARE";
        // data.To = "+19194484206";
        // data.Body = "Dance in the rain";
        // data.AuthKey = "3pKtr0P1p9";
        // data.MediaUrl = "http://upload.wikimedia.org/wikipedia/commons/e/e5/Vienna_Skyline.jpg";
        data = {
          From: "CARE",
          To: "+19194484206",
          Body: "Dance in the rain",
          MediaUrl: "http://upload.wikimedia.org/wikipedia/commons/e/e5/Vienna_Skyline.jpg",
          AuthKey: "3pKtr0P1p9",
        };
        // alert(JSON.stringify(data));
        $http.post(TwilioSend, $.param(data), {
            headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function(data, status, headers, config) {
            // alert("Succeeded! "+data+status);
            $scope.request.status = "The message is queued on the server.";
          })
          .error(function(data, status, headers, config, statusText) {
            $scope.request.status = "Something went wrong :(";
            alert("somthing wrong..."+data+status);
          });

      };
    }
  );

  // billing.html
  angular.module("billingApp", [])
    .controller("controller", function($scope, $http) {
      // Only hide the loading screen for current app
      if (document.getElementsByTagName("html")[0].getAttribute("ng-app") == "billingApp") {
        $('body').addClass('loaded');
      }

      var data = {
            Type: "billing",
            AuthKey: "3pKtr0P1p9",
          };
      $http.post(TwilioQuery, $.param(data), {
          headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
      }).success(function(data, status, headers, config) {
          alert("Succeeded! "+JSON.stringify(data)+status);
          var myEl = angular.element( document.querySelector( '#billingTable' ) );
          // myEl.attr('columns', '["fruit","alice","bill","casey"]');
          alert(myEl.attr('columns'));
          myEl.attr('columns', '["Client","day","month","year"]');
          myEl.attr('data', '[[ "banana", 110, 4, 0 ],[ "grape", 2, 3, 5 ],[ "pear", 4, 2, 8 ],[ "strawberry", 0, 14, 0 ] ]');
          // myEl.attr('columns','["test","sadf","bi;lkjll","lkj"]');

          // alert("changing columns back");
          // $scope.columns = '["fruit","alice","bill","casey"]';
          // $scope.something = "sadfasdljk";

        })
        .error(function(data, status, headers, config, statusText) {
          // $scope.request.status = "Something went wrong :(";
          alert("somthing wrong..."+data+status);
        });




      
    }
  );
});
  


