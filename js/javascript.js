// executes when HTML-Document is loaded and DOM is ready
$(document).ready(function () {
  // Variables
  var TwilioAPI = "./twilio/TwilioAPI.php";
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

      // For sending message
      $scope.requestMessage = {};
      $scope.requestMessage.data = {
        Request: "message",
        From : "CHAI",
      };
      $scope.requestMessage.data.From = "CHAI";

      $scope.requestMessage.display = {
        status: "",
      };
      $scope.requestMessage.display.From = ["CARE", "CHAI", "PainCoach", "ProtectThem"];
      $scope.requestMessage.display.setFrom = function(item){
        $scope.requestMessage.data.From = item;
      };
      $scope.requestMessage.doClick = function(item, event) {
        alert(JSON.stringify($scope.requestMessage.data));
        $http.post(TwilioAPI, $.param($scope.requestMessage.data), {
            headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function(data, status, headers, config) {
            // alert("Succeeded! "+data+status);
            $scope.requestMessage.display.status = "The message is queued on the server.";
          })
          .error(function(data, status, headers, config, statusText) {
            // alert("somthing wrong..."+data+status);
            if (data === "") {
              $scope.requestMessage.display.status = "Something went wrong :(";
            } else {
              $scope.requestMessage.display.status = data;
            }
          });
      };

      // For making call
      $scope.requestCall = {};
      $scope.requestCall.data = {
        Request: "call",
        From : "CHAI",
      };
      $scope.requestCall.data.From = "CHAI";

      $scope.requestCall.display = {
        status: "",
      };
      $scope.requestCall.display.From = ["CARE", "CHAI", "PainCoach", "ProtectThem"];
      $scope.requestCall.display.setFrom = function(item){
        $scope.requestCall.data.From = item;
      };
      $scope.requestCall.doClick = function(item, event) {
        alert(JSON.stringify($scope.requestCall.data));
        $http.post(TwilioAPI, $.param($scope.requestCall.data), {
            headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function(data, status, headers, config) {
            // alert("Succeeded! "+data+status);
            $scope.requestCall.display.status = "The call is queued on the server.";
          })
          .error(function(data, status, headers, config, statusText) {
            // alert("somthing wrong..."+data+status);
            if (data === "") {
              $scope.requestCall.display.status = "Something went wrong :(";
            } else {
              $scope.requestCall.display.status = data;
            }
          });
      };
    }
  );

  // logging.html
  angular.module("loggingApp", [])
    .controller("controller", function($scope, $http) {
      // Only hide the loading screen for current app
      if (document.getElementsByTagName("html")[0].getAttribute("ng-app") == "loggingApp") {
        $('body').addClass('loaded');
      }
      // Init for the sortable table
      $scope.table = {};
      $scope.table.columns = "[]";
      $scope.table.data = "[]";
      $scope.something = "Loading data...";

      var data = {
            Request: "query_logging",
            AuthKey: "4W9mL8YAcE0uFkTdXnfXChtQH",
          };
      $http.post(TwilioAPI, $.param(data), {
          headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
      }).success(function(data, status, headers, config) {
          alert("Succeeded! "+JSON.stringify(data)+status);
          // var myEl = angular.element( document.querySelector( '#billingTable' ) );
          // alert(myEl.attr('columns'));
          // myEl.attr('columns', '["Client","day","month","year"]');
          // myEl.attr('data', '[[ "banana", 110, 4, 0 ],[ "grape", 2, 3, 5 ],[ "pear", 4, 2, 8 ],[ "strawberry", 0, 14, 0 ] ]');
          $scope.table.columns = data["columns"];
          $scope.table.data = data["data"];
          $scope.something = "Log for the messages!";

        })
        .error(function(data, status, headers, config, statusText) {
          // $scope.request.status = "Something went wrong :(";
          alert("somthing wrong..."+data+status);
        });
    }
  );

  // billing.html
  angular.module("billingApp", [])
    .controller("controller", function($scope, $http) {
      // Only hide the loading screen for current app
      if (document.getElementsByTagName("html")[0].getAttribute("ng-app") == "billingApp") {
        $('body').addClass('loaded');
      }
      // Init for the sortable table
      $scope.table = {};
      $scope.table.columns = "[]";
      $scope.table.data = "[]";
      $scope.something = "Loading data...";

      var data = {
            Type: "billing",
            AuthKey: "3pKtr0P1p9",
          };
      $http.post(TwilioAPI, $.param(data), {
          headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
      }).success(function(data, status, headers, config) {
          // alert("Succeeded! "+JSON.stringify(data)+status);
          // var myEl = angular.element( document.querySelector( '#billingTable' ) );
          // alert(myEl.attr('columns'));
          // myEl.attr('columns', '["Client","day","month","year"]');
          // myEl.attr('data', '[[ "banana", 110, 4, 0 ],[ "grape", 2, 3, 5 ],[ "pear", 4, 2, 8 ],[ "strawberry", 0, 14, 0 ] ]');
          $scope.table.columns = data["columns"];
          $scope.table.data = data["data"];
          $scope.something = "Monthly usage!";

        })
        .error(function(data, status, headers, config, statusText) {
          // $scope.request.status = "Something went wrong :(";
          alert("somthing wrong..."+data+status);
        });
    }
  );

});
  


