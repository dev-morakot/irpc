'use strict';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("ResUsersApp", ['ngSanitize', 'ui.select',
    'ngAnimate', 'ui.bootstrap', 'checklist-model', 'ngRoute', 'bic.common'
]);

app.config(['$httpProvider', function ($httpProvider) {
        $httpProvider.defaults.headers.post['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr("content");
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
        $httpProvider.defaults.headers.common['Accept'] = 'application/json, text/javascript';
        $httpProvider.defaults.headers.common['Content-Type'] = 'application/json; charset=utf-8';
    }]);

app.directive('fileModel', ['$parse', function ($parse) {
        return {
          restrict: 'A',
          link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            element.bind('change', function() {
              scope.$apply(function() {
                modelSetter(scope, element[0].files[0]);
              });
            });
          }
        }
      }]);

app.service('fileUpload', ['$http', function ($http) {
    this.uploadFileToUrl = function (file, uploadUrl, username, email, firstname, lastname, active, code,id, select_admin ,rule_admin,select_help,rule_help,select_asset,rule_asset,tel) {
        var fd = new FormData();
        fd.append('file', file);
        fd.append('username', username);
        fd.append('email', email);
        fd.append('firstname', firstname);
        fd.append('lastname', lastname);
        fd.append('active', active);
        fd.append('code', code);
        fd.append('id',id);
        fd.append('select_admin',select_admin);
        fd.append('rule_admin', rule_admin);
        fd.append('select_help', select_help);
        fd.append('rule_help',rule_help);
        fd.append('select_asset', select_asset);
        fd.append('rule_asset',rule_asset);
        fd.append('tel', tel);
        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false}
        })
        .success(function () {
            console.log("สำเร็จ");
        })
        .error(function () {
            console.log("ล้มเหลว");
        });
    }
}]);

/*
 * Controller
 */
app.controller("ResUsersManageController", function ($scope, $http,
        $location, $window, fileUpload,
        $filter, uibDateParser, $routeParams) {
    console.log($location.search());
    var ctrl = this;
    ctrl.res_users = [];
    ctrl.usersearch = "";
    $scope.model = {};
    
    //
    ctrl.center_users = [];
    ctrl.dataUser = [];

    info();
    function info(){
         $http.get('index.php?r=resource/res-users/user-list-json')
        .then(function (response) {
            //console.log("response", response.data);
            ctrl.dataUser = response.data;
        });
    }
    
    ctrl.clickLine = function (line) {
        $scope.model = line;
        var params = {id: line.id}
        $http.get('index.php?r=resource/res-users/view-tab-json', {params: params})
            .then(function (response) {
                console.log(response.data);
                $scope.Provider = response.data;
            });
    }

    ctrl.edit = function (line){
        console.log(line);
        var params = {q: line.id}
        $http.get('index.php?r=resource/res-users/update',{params: params})
            .then(function (response) {
                console.log(response.data);
            });
    }

    ctrl.ResUserDelete = function (line) {
        var params = {q : line.id}
        var alert = confirm("คุณต้องการลบหรือไม่ ?");

        if(alert) {
            $http.get('index.php?r=resource/res-users/delete-res-users&id=' + line.id)
            .then(function (response) {
                console.log(response.data);
                info();
            });
        }
        
    }

    ctrl.ResUserEdit = function (line) {
        var _model = line;
        var params = {
            model: _model
        };
        console.log(params);
        var file = line.file;
        console.log('file is');
        var uploadUrl = "index.php?r=resource/res-users/edit-res-users";
        var username = line.username;
        var email = line.email;
        var code = line.code;
        var firstname = line.firstname
        var lastname = line.lastname;
        var active = line.active;
        var id = line.id;
        var select_admin = line.select_admin;
        var rule_admin = line.rule_admin;
        var select_help = line.select_help;
        var rule_help = line.rule_help;
        var select_asset = line.select_asset;
        var rule_asset = line.rule_asset;
        var tel = line.tel;
        fileUpload.uploadFileToUrl(file,uploadUrl, username, email, firstname, lastname, active, code ,id, select_admin,rule_admin, select_help,rule_help,select_asset, rule_asset, tel);
        /*$http.post('index.php?r=resource/res-users/edit-res-users', params)
            .then(function(response) {
                var data = response.data;
                console.log(data);
            });*/
    }
    
    /*ctrl.refreshResUsers = function () {
        var _params = {q: ctrl.usersearch};
        $http.get('/resource/res-users/res-user-list-json', {params: _params})
                .then(function (response) {
                    var data = response.data;
                    console.log(data);
                    ctrl.res_users = data;
                });
    };
    
    ctrl.refreshCenterUser = function(){
        var _params = {q: ctrl.usersearch};
        $http.get('/resource/res-users/center-user-list-json', {params: _params})
                .then(function (response) {
                    var data = response.data;
                    console.log(data);
                    ctrl.center_users = data;
                });
    };

    ctrl.refreshResUsers();
    ctrl.refreshCenterUser();

    ctrl.refresh = function(){
        ctrl.refreshResUsers();
        ctrl.refreshCenterUser();
    };
    
    ctrl.addToCenter = function(_user){
        console.log(_user);
        var _params = {id: _user.id};
        $http.get('/resource/res-users/add-to-res-user', {params: _params})
                .then(function (response) {
                    var data = response.data;
            console.log(data);
                    if(data.status==='success'){
                        ctrl.refresh();
                    } else {
                        bootbox.alert("ซ้ำ");
                    }
                },function errorCallback(response){
                    console.log(response);
                    bootbox.alert({message:response.data.name});
                });
    };
*/

});
