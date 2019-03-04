'use strict';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("RequestApp", ['ngSanitize', 'ui.select',
    'ngAnimate', 'ui.bootstrap', 'checklist-model','bic.common','bic.module'
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



/*
 * Controller
 */
app.controller("RequestFormController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {

        console.log("location search =" , $location.search());
        var ctrl = this;
        // resources
        $scope.dataWait = [];

        // model
        ctrl.GET = $location.search();
        ctrl.auto_docname = ctrl.GET.name;
        $scope.myId = ctrl.GET.id;
        console.log("myId=>", $scope.myId);
        $scope.model = {
            id: -1,
            autodoc:true,
            state: "wait",
        };
        console.log({log: "Initial request form", msg:$scope.model});
        console.log("initail");
        $http.get('index.php?r=helpdesk/request/load-form-ajax')
            .then(function (response) {
                var resources = response.data;
                $scope.states = resources.states;
                if(!$scope.myId) {
                    $scope.model.name = "IT*NEW*";
                } else {
                    $scope.loadModel($scope.myId);
                }
            });

        $scope.loadModel = function (_id){
            console.log({'log': 'loadModel',msg: _id});
            $http.get('index.php?r=helpdesk/request/load-model-form-json&id=' + _id)
                .then(function (response) {
                    $scope.model = response.data.model;
                    console.log(response.data.model);
                });
        }

        ctrl.onClickAutoDoc = function ($event) {
            $event.preventDefault();
            $scope.model.name = ctrl.auto_docname;
            $scope.model.autodoc = true;
        }
        
        ctrl.formValidate = function (){
            var isPass = true;
            var value = $("#sn_number").val();
            if(value.trim() == "") {
                addErrorHelper($("#sn_number"), 'โปรดระบุหมายเลขคุรภัณฑ์');
                isPass = false;
            }

            if($("#description").val().trim() == "") {
                addErrorHelper($("#description"), 'โปรดระบุตรวจสอบเบื้องต้น (ระบุสาเหตุ/ปัญหาที่พบ)');
                isPass = false;
            }
            var brand = $("#brand").val();
            if(brand.trim() == "") {
                addErrorHelper($("#brand"), 'โปรดระบุรุ่น/ยี่ห้อ');
                isPass= false;
            }

            /*var problem = $("#problem").val();
            if(problem.trim()==""){
                addErrorHelper($("#problem"), 'โปรดระบุแจ้งปัญหา');
                isPass = false;
            }
            return isPass;*/
        }

        function addErrorHelper($el, message) {
            $el.parents(".form-group").find(".help-block").html(message).addClass("has-error");
            $el.parents(".form-group").addClass("has-error");
        }

        function clearErrorMsg() {
            $(".form-group").removeClass("has-error");
            $(".form-group .help-block").removeClass("has-error");
            $(".form-group .help-block").empty();
        }
        

        /*
         * Form Submit
         * @returns {undefined}
         */
        $scope.save = function (){
            console.log("save", $scope.model);
            
           /* clearErrorMsg();
            if (!ctrl.formValidate()) {
                return;
            }*/

            if($scope.requestForm.$invalid) {
                return;
            }
            var data = {
                model: $scope.model
            };
            console.log(data);
            $http.post("index.php?r=helpdesk/request/save-request", data)
                .then(function (response) {
                    console.log(response);
                    var requests = response.data.request;
                    console.log(requests.id);
                    $scope.loadModel(requests.id);
                    navigateToView(requests.id);
            }, function errorCallback(response) {
                    console.log("save-request error", response);
            });
            
        }

        function navigateToView(id) {
            $window.location.href = 'index.php?r=helpdesk/request/view&id=' + id;
        }
});

app.controller("RequestViewController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {

        var ctrl = this;
       $scope.Ids = $("#getId").val();
       
       console.log($scope.Ids);

       $http.get("index.php?r=helpdesk/request/used-repair-list-json")
        .then(function (response) {
            console.log(response.data);
            $scope.use_repair = response.data;
        });

        $http.get("index.php?r=helpdesk/request/used-builder-list-json")
            .then(function(response) {
                $scope.use_builder = response.data;
            });
        $http.get('index.php?r=helpdesk/request/used-officer-list-json')
            .then(function (response) {
                $scope.use_officer = response.data;
            });

        $http.get("index.php?r=helpdesk/request/load-model-form-json&id=" + $scope.Ids)
            .then(function(response) {
                $scope.model = response.data.model;
            });

        $scope.saveRepair = function (){
            console.log($scope.model);
            var data = {
                model: $scope.model
            };
            $http.post("index.php?r=helpdesk/request/save-state-repair", data)
                .then(function (response) {
                    console.log(response.data);
                    ctrl.reView(response.data);
                });
        }

        $scope.saveAll = function (){
            if($scope.model.state === "repair" || $scope.model.state === null || $scope.model.state === "") {
                bootbox.alert("กรุณาเลือกสถานะการซ่อม");
            } else {
                console.log($scope.model);
            var data = {
                model: $scope.model
            };
            $http.post("index.php?r=helpdesk/request/save-as",data)
                .then(function (response) {
                    ctrl.reView(response.data);
                });
            }
            
        }

        $scope.saveAsComment = function (){
            if($scope.model.comment_state === null || $scope.model.state === "") {
                bootbox.alert("กรุณาประเมินความพึงพอใจ");
            } else {
                var data = {
                model: $scope.model
            };
            $http.post("index.php?r=helpdesk/request/save-comment", data)
                .then(function (response) {
                    ctrl.reView(response.data);
                });
            }
            
        }

        $scope.saveClose = function (){
           if($scope.model.state === "clame" || $scope.model.state === null || $scope.model.state === "") {
                bootbox.alert("กรุณาเลือกสถานะการซ่อม");
            } else {
                var data = {
                model: $scope.model
            };
            $http.post("index.php?r=helpdesk/request/save-close",data)
                .then(function (response) {
                    ctrl.reView(response.data);
                });
            }

            
        }

        $scope.saveCancel = function (){
           if($scope.model.state === "clame" || $scope.model.state === null || $scope.model.state === "") {
                bootbox.alert("กรุณาเลือกสถานะการซ่อม");
            } else {
                var data = {
                model: $scope.model
                };
                $http.post("index.php?r=helpdesk/request/save-cancel",data)
                .then(function (response) {
                    ctrl.reView(response.data);
                });
            }

            
        }

        ctrl.reView = function(id) {
            $window.location.href= "index.php?r=helpdesk/request/view&id=" + id;
        }
});
