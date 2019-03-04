'use strict';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("ReportApp", ['ngSanitize', 'ui.select',
    'ngAnimate', 'ui.bootstrap', 'checklist-model','bic.common','bic.module'
]);
app.config(['$httpProvider', function ($httpProvider) {
   $httpProvider.defaults.headers.post['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr("content");
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript';
        $httpProvider.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8';
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

app.filter('format', function () {
  return function (item) {
     var t = item.split(/[- :]/);
     var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
     var time=d.getTime();                 
     return time;
 };
});

/*
 * Controller
 */
app.controller("ReportController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {

    $scope.model = {};
    $scope.report = [];
    $scope.categories = [];
    $scope.today = new Date();
    var nextDay = new Date($scope.today);
    $scope.model.date_start = nextDay.setDate($scope.today.getDate() - 31);
    $scope.model.date_end = new Date();
    // ui
    $scope.datepickerOptions = {
        showWeeks: true
    };
    $scope.datepicker1 = {
        opened: false
    };
    $scope.openDatepicker1 = function(){
        $scope.datepicker1.opened = true;
    }

    $scope.datepickerOptions = {       
        showWeeks: true
    };

    $scope.datepicker2 = {
        opened: false
    };

    $scope.openDatepicker2 = function() {
        $scope.datepicker2.opened = true;
    }

    $scope.onCategoriesOpenClose = function (isOpen) {
        if(isOpen) {
            $scope.refreshCategories("");
        }
    }

    $scope.refreshCategories = function (category) {
        var params = {q: category, limit: 20};
        return $http.get("index.php?r=asset/report/categories-list-json", {params: params})
            .then(function (response) {
                $scope.categ = response.data;
            });
    }
    $scope.RunReport = function (){
        if($scope.form1.$invalid) {
            bootbox.alert("โปรดกรอกข้อมูลค้นหา");
            return;
        }
        $scope.executeReport();
    }

    $scope.executeReport = function (){
        var $params = $scope.model;
        $scope.date_start = $filter('date')($params.date_start , 'yyyy-MM-dd', "TH");
        $scope.date_end = $filter('date')($params.date_end, 'yyyy-MM-dd', "TH");
        var data = {
            date_start: $scope.date_start,
            date_end: $scope.date_end,
            categ: $params.category
        };
        $http.post("index.php?r=asset/report/report-categories-list", data)
            .then(function (response) {
                $scope.categories = response.data;
                angular.forEach($scope.categories, function (v,k) {
                    v.date_approve = $filter('date')(v.order.date_approve, "dd/MM/yyyy", "TH");
                });
            });
    }

});



app.controller("ReportLocationController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {

    $scope.model = {};
    $scope.report_location = [];
    $scope.local = [];
    $scope.today = new Date();
    var nextDay = new Date($scope.today);
    $scope.model.date_start = nextDay.setDate($scope.today.getDate() - 31);
    $scope.model.date_end = new Date();
    // ui
    $scope.datepickerOptions = {
        showWeeks: true
    };
    $scope.datepicker1 = {
        opened: false
    };
    $scope.openDatepicker1 = function(){
        $scope.datepicker1.opened = true;
    }

    $scope.datepickerOptions = {       
        showWeeks: true
    };

    $scope.datepicker2 = {
        opened: false
    };

    $scope.openDatepicker2 = function() {
        $scope.datepicker2.opened = true;
    }

    $scope.onLocationOpenClose = function (isOpen) {
        if(isOpen) {
            $scope.refreshLocation("");
        }
    }

    $scope.refreshLocation = function (category) {
        var params = {q: category, limit: 20};
        return $http.get("index.php?r=asset/report/location-list-json", {params: params})
            .then(function (response) {
                $scope.local = response.data;
            });
    }
    $scope.RunReport = function (){
        if($scope.form1.$invalid) {
            bootbox.alert("โปรดกรอกข้อมูลค้นหา");
            return;
        }
        $scope.executeReport();
    }

    $scope.executeReport = function (){
        var $params = $scope.model;
        $scope.date_start = $filter('date')($params.date_start , 'yyyy-MM-dd', "TH");
        $scope.date_end = $filter('date')($params.date_end, 'yyyy-MM-dd', "TH");
        var data = {
            date_start: $scope.date_start,
            date_end: $scope.date_end,
            location: $params.location
        };
        $http.post("index.php?r=asset/report/report-location-list", data)
            .then(function (response) {
                $scope.report_location = response.data;
                angular.forEach($scope.report_location, function (v,k) {
                    v.date_approve = $filter('date')(v.order.date_approve, "dd/MM/yyyy", "TH");
                   
                });
            });
    }

});

app.controller("ReportAssetYearController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {

        $scope.model = {};
        $scope.report_asset_year = [];
        $scope.years = [
            {'id': 2017 ,'name': 2560},
            {'id': 2018 ,'name': 2561},
            {'id': 2019 ,'name': 2562},
            {'id': 2020 ,'name': 2563},
            {'id': 2021 ,'name': 2564},
            {'id': 2022 ,'name': 2565},
            {'id': 2023 ,'name': 2566},
            {'id': 2024 ,'name': 2567},
            {'id': 2025 ,'name': 2568},
        ];
        
        $scope.RunReport = function (){
            console.log("runreport");
            var model = $scope.model;
            var data = {
                model: model
            };
            console.log(data);
            $http.post("index.php?r=asset/report/report-asset-year-json",data)
                .then(function (response) {
                    $scope.report_asset_year = response.data;
                    console.log(response.data);
                    angular.forEach($scope.report_asset_year, function (v,k) {
                        var total = 0;
                        total += (parseFloat(v.qty));
                        $scope.sum = total;
                    });
                });
        }

        $scope.dialog = function (_line) {
            console.log("line", _line);
            $scope.modline = _line;
            var data = {
                model: $scope.modline
            };
            $http.post("index.php?r=asset/report/report-asset-year-detail", data)
                .then(function (response) {
                    $scope.modelLines = response.data;
                    angular.forEach($scope.modelLines, function (v,k) {
                        var total = 0;
                        total += parseInt(v.qty);
                        
                    });
                    $scope.sum = total;
                });
        }
});


app.controller("ReportAssetMonthController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {
        
        $scope.model = {};
        $scope.report_asset_month = [];
        $scope.years = [
            {'id': 2017 ,'name': 2560},
            {'id': 2018 ,'name': 2561},
            {'id': 2019 ,'name': 2562},
            {'id': 2020 ,'name': 2563},
            {'id': 2021 ,'name': 2564},
            {'id': 2022 ,'name': 2565},
            {'id': 2023 ,'name': 2566},
            {'id': 2024 ,'name': 2567},
            {'id': 2025 ,'name': 2568},
        ];
        $scope.months = [
            {id: '01', name: 'มกราคม'},
            {id: '02', name: 'กุมภาพันธ์'},
            {id: '03', name: 'มีนาคม'},
            {id: '04', name: 'เมษายน'},
            {id: '05', name: 'พฤษภาคม'},
            {id: '06', name: 'มิถุนายน'},
            {id: '07', name: 'กรกฎาคม'},
            {id: '08', name: 'สิงหาคม'},
            {id: '09', name: 'กันยายน'},
            {id: '10', name: 'ตุลาคม'},
            {id: '11', name: 'พฤศจิกายน'},
            {id: '12', name: 'ธันวาคม'},
        ];

        $scope.RunReport = function (){
            console.log("runreport");
            var model = $scope.model;
            var data = {
                model: model
            };
            console.log(data);
            $http.post("index.php?r=asset/report/report-asset-month-json",data)
                .then(function (response) {
                    $scope.report_asset_month = response.data;
                    console.log(response.data);
                    angular.forEach($scope.report_asset_month, function (v,k) { 
                        v.date_approve = $filter('date')(v.date_approve, "dd/MM/yyyy", "TH");
                        
                    });
                    
                });
        }
});



app.controller("ReportAssetGroupController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {
        
    $scope.model = {};
    $scope.report_asset_group = [];
    $scope.groups = [];
    $scope.modelLines = [];
    $scope.today = new Date();
    var nextDay = new Date($scope.today);
    $scope.model.date_start = nextDay.setDate($scope.today.getDate() - 31);
    $scope.model.date_end = new Date();
    // ui
    $scope.datepickerOptions = {
        showWeeks: true
    };
    $scope.datepicker1 = {
        opened: false
    };
    $scope.openDatepicker1 = function(){
        $scope.datepicker1.opened = true;
    }

    $scope.datepickerOptions = {       
        showWeeks: true
    };

    $scope.datepicker2 = {
        opened: false
    };

    $scope.openDatepicker2 = function() {
        $scope.datepicker2.opened = true;
    }

    $scope.onGroupOpenClose = function (isOpen) {
        if(isOpen) {
            $scope.refreshGroup("");
        }
    }

    $scope.refreshGroup = function (group) {
        var params = {q: group, limit: 20};
        return $http.get("index.php?r=asset/report/group-list-json", {params: params})
            .then(function (response) {
                $scope.groups = response.data;
            });
    }

    $scope.RunReport = function (){
        var $params = $scope.model;
        $scope.date_start = $filter('date')($params.date_start , 'yyyy-MM-dd', "TH");
        $scope.date_end = $filter('date')($params.date_end, 'yyyy-MM-dd', "TH");
        var data = {
            date_start: $scope.date_start,
            date_end: $scope.date_end,
            group: $params.group
        };
        console.log(data);
        $http.post("index.php?r=asset/report/report-asset-group-json", data)
            .then(function (response) {
                $scope.report_asset_group = response.data;
                angular.forEach($scope.report_asset_group, function (v,k) { 
                        v.date_approve = $filter('date')(v.date_approve, "dd/MM/yyyy", "TH");
                        
                    });
            });
    }

    $scope.detail = function (_line) {
        console.log(_line);
        var model = _line;
        var data = {
            model: model
        };
        console.log(data);
        $http.post("index.php?r=asset/report/report-asset-group-detail", data)
            .then(function (response) {
                $scope.modelLines = response.data;
            });
    }
});



app.controller("ReportReturnYearController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {

        $scope.model = {};
        $scope.report_return_year = [];
        $scope.years = [
            {'id': 2017 ,'name': 2560},
            {'id': 2018 ,'name': 2561},
            {'id': 2019 ,'name': 2562},
            {'id': 2020 ,'name': 2563},
            {'id': 2021 ,'name': 2564},
            {'id': 2022 ,'name': 2565},
            {'id': 2023 ,'name': 2566},
            {'id': 2024 ,'name': 2567},
            {'id': 2025 ,'name': 2568},
        ];
        
        $scope.RunReport = function (){
            console.log("runreport");
            var model = $scope.model;
            var data = {
                model: model
            };
            console.log(data);
            $http.post("index.php?r=asset/report/report-return-year-json",data)
                .then(function (response) {
                    $scope.report_return_year = response.data;
                    console.log(response.data);
                    angular.forEach($scope.report_return_year, function (v,k) {
                        var total = 0;
                        total += (parseFloat(v.qty));
                        $scope.sum = total;
                    });
                });
        }

        $scope.dialog = function (_line) {
            console.log("line", _line);
            $scope.modline = _line;
            var data = {
                model: $scope.modline
            };
            $http.post("index.php?r=asset/report/report-return-year-detail", data)
                .then(function (response) {
                    $scope.modelLines = response.data;
                    angular.forEach($scope.modelLines, function (v,k) {
                        var total = 0;
                        total += parseInt(v.qty);
                        
                    });
                    $scope.sum = total;
                });
        }
});




app.controller("ReportReturnMonthController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {
        
        $scope.model = {};
        $scope.report_return_month = [];
        $scope.years = [
            {'id': 2017 ,'name': 2560},
            {'id': 2018 ,'name': 2561},
            {'id': 2019 ,'name': 2562},
            {'id': 2020 ,'name': 2563},
            {'id': 2021 ,'name': 2564},
            {'id': 2022 ,'name': 2565},
            {'id': 2023 ,'name': 2566},
            {'id': 2024 ,'name': 2567},
            {'id': 2025 ,'name': 2568},
        ];
        $scope.months = [
            {id: '01', name: 'มกราคม'},
            {id: '02', name: 'กุมภาพันธ์'},
            {id: '03', name: 'มีนาคม'},
            {id: '04', name: 'เมษายน'},
            {id: '05', name: 'พฤษภาคม'},
            {id: '06', name: 'มิถุนายน'},
            {id: '07', name: 'กรกฎาคม'},
            {id: '08', name: 'สิงหาคม'},
            {id: '09', name: 'กันยายน'},
            {id: '10', name: 'ตุลาคม'},
            {id: '11', name: 'พฤศจิกายน'},
            {id: '12', name: 'ธันวาคม'},
        ];

        $scope.RunReport = function (){
            console.log("runreport");
            var model = $scope.model;
            var data = {
                model: model
            };
            console.log(data);
            $http.post("index.php?r=asset/report/report-return-month-json",data)
                .then(function (response) {
                    $scope.report_return_month = response.data;
                    console.log(response.data);
                    angular.forEach($scope.report_return_month, function (v,k) { 
                        v.date_approve = $filter('date')(v.date_approve, "dd/MM/yyyy", "TH");
                        
                    });
                    
                });
        }
});



app.controller("ReportReturnGroupController", function ($scope, $http,
        $location, $window,
        $filter, uibDateParser, Resource) {
        
    $scope.model = {};
    $scope.report_return_group = [];
    $scope.groups = [];
    $scope.modelLines = [];
    $scope.today = new Date();
    var nextDay = new Date($scope.today);
    $scope.model.date_start = nextDay.setDate($scope.today.getDate() - 31);
    $scope.model.date_end = new Date();
    // ui
    $scope.datepickerOptions = {
        showWeeks: true
    };
    $scope.datepicker1 = {
        opened: false
    };
    $scope.openDatepicker1 = function(){
        $scope.datepicker1.opened = true;
    }

    $scope.datepickerOptions = {       
        showWeeks: true
    };

    $scope.datepicker2 = {
        opened: false
    };

    $scope.openDatepicker2 = function() {
        $scope.datepicker2.opened = true;
    }

    $scope.onGroupOpenClose = function (isOpen) {
        if(isOpen) {
            $scope.refreshGroup("");
        }
    }

    $scope.refreshGroup = function (group) {
        var params = {q: group, limit: 20};
        return $http.get("index.php?r=asset/report/group-list-json", {params: params})
            .then(function (response) {
                $scope.groups = response.data;
            });
    }

    $scope.RunReport = function (){
        var $params = $scope.model;
        $scope.date_start = $filter('date')($params.date_start , 'yyyy-MM-dd', "TH");
        $scope.date_end = $filter('date')($params.date_end, 'yyyy-MM-dd', "TH");
        var data = {
            date_start: $scope.date_start,
            date_end: $scope.date_end,
            group: $params.group
        };
        console.log(data);
        $http.post("index.php?r=asset/report/report-return-group-json", data)
            .then(function (response) {
                $scope.report_return_group = response.data;
                angular.forEach($scope.report_return_group, function (v,k) { 
                        v.date_approve = $filter('date')(v.date_approve, "dd/MM/yyyy", "TH");
                        
                    });
            });
    }

    $scope.detail = function (_line) {
        console.log(_line);
        var model = _line;
        var data = {
            model: model
        };
        console.log(data);
        $http.post("index.php?r=asset/report/report-return-group-detail", data)
            .then(function (response) {
                $scope.modelLines = response.data;
            });
    }
});