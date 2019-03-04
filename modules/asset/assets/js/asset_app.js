'use strict';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("myApp", ['ngSanitize', 'ui.select',
    'ngAnimate', 'ngRoute', 'ui.bootstrap', 'checklist-model','bic.common','bic.module'
]);

app.config(['$httpProvider', function ($httpProvider) {
   $httpProvider.defaults.headers.post['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr("content");
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript';
        $httpProvider.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8';
}]);


app.config(['$locationProvider', function ($locationProvider) {
        // ปรับให้ใช้กับ Url ธรรมดา http://abc/ab?id=123
        $locationProvider.html5Mode({enabled:true,requireBase:false});
        //$locationProvider.hashPrefix('!');
    }]);



app.factory("Resource", function ($http) {

    var items = [];
    for (var i = 0; i < 50; i++) {
        items.push({id: i, name: "name " + i, description: "description " + i});
    }
    var resources = [];
//    $http.get('/purchase/purchase-request/resource-for-form-ajax')
//            .then(function (response) {
//                resources = response.data;
//            });
    return {
        init: function () {
            return resources;
        },
        get: function (offset, limit) {
            return items.slice(offset, offset + limit);
        },
        total: function () {
            return items.length;
        }
    };
});

app.filter('formatDate', function () {
    return function (input) {
        // use moment.js
        var date = moment(input);

        return date.format('DD/MM/YYYY');
    };
});

// แปลง เปน number
app.directive('convertToNumber', function() {
	return {
		require: 'ngModel',
		link: function(scope, element, attrs, ngModel) {
			ngModel.$parsers.push(function (val) {
				return val == null ? null : parseInt(val, 10);
			});
			ngModel.$formatters.push(function (val) {
				return val == null ? null : '' + val;
			});
		}
	}
});

// แปลง สติง เป็น ตัวเลข 
app.directive('stringToNumber', function() {

	return {
		require: 'ngModel',
		link: function (scope, element, attrs, ngModel) {
			ngModel.$parsers.push(function (value) {
				return value == null ? null : (parseFloat(value) || 0);
			});
			ngModel.$formatters.push(function (value) {
				return parseFloat(value);
			});
		}
	}
});

app.filter('sumOfValue', function() {
        return function(data, key) {

          if(angular.isUndefined(data) && angular.isUndefined(key))
            return 0;
          var sum = 0;

          angular.forEach(data, function(v ,k) {
            sum = sum + parseFloat(v[key]);
          });
          return sum;
        }
      });

/*
 * Controller
 */
app.controller("AddController", function ($scope, $http,
    $location, $window,
    $filter, uibDateParser) {

    $scope.listAsset = [];
    $scope.category = [];
    $scope.unit = [];
    $scope.cart = [];
    $scope.groups = [];
    $scope.locations = [];
    $scope.slide = false;
    
         $http.get("index.php?r=asset/asset/load-form-json")
            .then(function (response) {
                $scope.listAsset = response.data.asset;
                $scope.category = response.data.category;
                $scope.unit = response.data.unit;
                console.log($scope.listAsset);
            });

    $scope.addData = function (_line) {
        $scope.modline = _line;
        $scope.modline.new_qty = 1;
    }

    $scope.addItem = function (_modline) {
        console.log("เพิ่มรายการ", _modline);
       if(parseInt(_modline.new_qty) > parseInt(_modline.qty)) {
           $scope.msg = "ระบุจำนวนใหม่อีกครั้ง";
           console.log("จำนวน มากกว่า จำนวนคงเหลือ");
       } 
       if(parseInt(_modline.new_qty) <= parseInt(_modline.qty)) {
           console.log("ระบุจำนวนได้");
           $scope.in = {
               id: _modline.id,
               name: _modline.name,
               description: _modline.description,
               qty: _modline.qty,
               category: {
                   id: _modline.category.id,
                   name: _modline.category.name
               },
               new_qty: _modline.new_qty,
               unit: {
                   id: _modline.unit.id,
                   name: _modline.unit.name
               }
           };
           $scope.msg = undefined;
           $scope.cart.push($scope.in);
       }
       console.log("cart", $scope.cart);
       console.log("in", $scope.in);
       var total = 0;
       angular.forEach($scope.cart, function (v,k) {
           total += parseInt(v.new_qty);
           
       });
       $scope.amount_total = total; 

       var data = {
           cart: $scope.cart
       };
    
    }

    $scope.DelItem = function (_line) {
         var index = -1;
          var comArr = eval($scope.cart);
          for (var i = 0; i < comArr.length; i++) {
            if(comArr[i].name === _line.name) {
              index = i;
              break;
            }
          }
          if(index === -1) {
            alert("Something gone wrong");
          }

          $scope.cart.splice(index,1);
    }

    $scope.saveCart = function(){
        console.log("เก็บ cart", $scope.cart);
        console.log("model", $scope.model);
        var data = {
            model: $scope.model,
            cart: $scope.cart
        };
        $http.post("index.php?r=asset/asset/save-cart-list-json", data)
            .then(function (response) {
                console.log(response.data);
                $window.location.href = 'index.php?r=asset/asset/add';
            });
    }

     $scope.onGroupOpenClose = function (isOpen) {
        if(isOpen) {
            $scope.refreshGroup("");
        }
    }
    $scope.onLocationOpenClose = function (isOpen) {
        if(isOpen) {
            $scope.refreshLocation("");
        }
    }

    $scope.refreshGroup = function (group) {
        var params = {q: group};
        return $http.get("index.php?r=helpdesk/report/group-list-json", {params: params})
            .then(function(response) {
                $scope.groups = response.data;
                console.log($scope.groups);
            });
    }

    $scope.refreshLocation = function (locl) {
        var params = {q: locl};
        return $http.get('index.php?r=asset/asset/location-list-json', {params: params})
            .then(function(response) {
                $scope.locations = response.data;
            });
    }
});