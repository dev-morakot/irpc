'use strict';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var app = angular.module("bic.common", ['ngSanitize', 'ui.select',
    'ngAnimate', 'ui.bootstrap', 'checklist-model', 'ngRoute','ngResource'
]);

app.animation('.slide', function () {
    var NG_HIDE_CLASS = 'ng-hide';
    return {
        beforeAddClass: function (element, className, done) {
            if (className === NG_HIDE_CLASS) {
                element.slideUp(done);
            }
        },
        removeClass: function (element, className, done) {
            if (className === NG_HIDE_CLASS) {
                element.hide().slideDown(done);
            }
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
app.filter('uomFilter', function ($filter) {
    return function (uoms, _product) {
        if (_product) {
            var _category_id = $filter('filter')(uoms, {id: _product.uom_id})[0].category_id;
            console.log("uom category id",_category_id);
            return $filter('filter')(uoms, {category_id: _category_id});
        } else {
            return uoms;
        }
    };
});
/**
 * AngularJS default filter with the following expression:
 * "person in people | filter: {name: $select.search, age: $select.search}"
 * performs an AND between 'name: $select.search' and 'age: $select.search'.
 * We want to perform an OR.
 */
app.filter('propsFilter', function () {
    return function (items, props) {
        var out = [];

        if (angular.isArray(items)) {
            var keys = Object.keys(props);

            items.forEach(function (item) {
                var itemMatches = false;

                for (var i = 0; i < keys.length; i++) {
                    var prop = keys[i];
                    var text = props[prop].toLowerCase();
                    if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                        itemMatches = true;
                        break;
                    }
                }

                if (itemMatches) {
                    out.push(item);
                }
            });
        } else {
            // Let the output be the input untouched
            out = items;
        }

        return out;
    };
});

/*
 * Directive
 */
app.directive('convertToNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function (val) {
                //saves integer to model null as null
                return val == null ? null : parseInt(val, 10);
            });
            ngModel.$formatters.push(function (val) {
                //return string for formatter and null as null
                return val == null ? null : '' + val;
            });
        }
    };
});

app.directive('stringToNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function (value) {
                return value == null ? null : (parseFloat(value) || 0);
            });
            ngModel.$formatters.push(function (value) {
                return parseFloat(value) || null;
            });
        }
    };
});

app.directive('numberToString', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function (value) {
                console.log('parser.push ' + typeof (value));
                return value == null ? null : value.toString();
            });
            ngModel.$formatters.push(function (value) {
                console.log('formatter.push ' + typeof (value));
                return value.toString();
            });
        }
    };
});

// refresh ui-select เมื่อ active แก้ขัดไปก่อน รอ ตัวจริงออก
app.directive('refreshOnActive', refreshOnActive);
function refreshOnActive() {
    return {
        restrict: 'A',
        link: refreshOnActiveLink,
        scope: {
            refreshOnActive: '=',
            refresh: '@'
        }
    }

    function refreshOnActiveLink(scope, element) {
        if (! scope.refreshOnActive === true) {
            return;
        }
        var storedFunction = scope.$parent.$select.refresh;
        scope.$parent.$select.refresh = function() {};

        var element = angular.element(element).closest('.ui-select-container');
        var fn = function() {
            scope.$parent.$select.refresh = function() {
                storedFunction(scope.refresh);
            };
            scope.$parent.$select.refresh.call();
            element.unbind('click', fn);
        };
        element.bind('click', fn);
    }
};

/**
 * แปลง date obj เป็น string เก็บลง model
 * format ต้องตรงกันกับ uibDatepickerPopup
 */
app.directive('datepickerToString', function (){
    return {
        restrict: 'EAC',
        require: ['ngModel','uibDatepickerPopup'],
        link: function(scope, elem, attrs, ctrls) {
            //console.log('directive',ctrls);
            //console.log('attrs',attrs);
            var ngModel = ctrls[0],
                    uibDatepickerPopup = ctrls[1];
            //console.log('format',uibDatepickerPopup.datepickerConfig);
            ngModel.$parsers.push(function toModel(date) {
                //console.log('toModel',date);
                //var date_str = moment(date).format('DD/MM/YYYY');
                var format = 'DD/MM/YYYY';
                if(attrs.datepickerToString==""){
                    format = attrs.datepickerToString;
                }
                var date_str = moment(date).format(format);
                return date_str;
            });
        }
    }
});

//app.directive('bicDocState',function(){
//   return {
//     require:'A',
//     link: function (scope, element)
//   }; 
//});

app.directive('bicDemo', function () {
    return {
        restrict: 'A',
        templateUrl: '/angular-templates/bic-demo.html',
        controller: function () {
            this.message = 'How are you?';
        },
        controllerAs: 'ctrl'
    };
});

app.component('bicDemo2', {
    templateUrl: '/angular-templates/bic-demo.html',
    controller: function () {
        this.message = "Who am i ?";
    },
    bindings: {
        hero: '=',
        message: '='
    },
    controllerAs: 'ctrl'
});

/**
 * Factory
 */
app.factory('AccountProductGroup',function($resource){
    return $resource('/api/account-product-groups/:id?expand=accountStock');
});

app.factory('AccountAccount',function($resource){
    return $resource('/api/account-accounts/:id');
});

app.factory('StockMove',function($resource){
    return $resource('/api/stock-moves/:id?expand=lot');
});

app.factory('StockGoodsImport',function($resource){
    return $resource('/api/stock-goods-imports/:id');
});

app.factory('ResUsers',function($resource){
    return $resource('/api/res-users/:id');
});

app.factory('ResAddress',function($resource){
    return $resource('/api/res-addresses/:id',{id:'@id'},{
        query: {
                method: 'GET',
                url: '/api/res-addresses/search',
                isArray:true
            }
    });
});

app.factory('ResPartner',function($resource){
    return $resource('/api/res-partners/:id');
});
/**
 * 
 * BIC - Components
 * 
 */

/**
 * Product
 */
app.component('bicProductSelect', {
    templateUrl: '/angular-templates/bic-product-select.html',
    controller: function ($http, $scope) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.products = [];
        ctrl.type='stock,service,consu';

        ctrl.refreshProducts = function (product) {
            var params = {q: product, limit: 20,type:ctrl.type};
            console.log('bicProductSelect params',params);
            return $http.get('/product/product-product/product-list-json', {params: params}
            ).then(function (response) {
                ctrl.products = response.data;
            });
        };
        
        ctrl.openProductInfo = function(){
            if(ctrl.bicModel){
                var _product_id = ctrl.bicModel.id;
                console.log(_product_id);  
                window.open(
                    '/product/product-product/view?id='+_product_id,
                    '_blank' // <- This is what makes it open in a new window.
                );
            }
        };

        // EXAMPLE:: Component callback เรียก function ที่ listener
        // และส่ง parameter ให้ คือ parameter ชื่อ product
        ctrl.delete = function () {
            ctrl.onDelete({cProduct: ctrl.bicModel});
            ctrl.bicModel = null;
        };

        ctrl.update = function () {
            ctrl.onUpdate({cProduct: ctrl.bicModel});
        };

        ctrl.select = function (selected) {
            ctrl.onSelect({cProduct: selected});
        };
    },
    bindings: {
        bicModel: '=',
        name: '@',
        required: '@',
        onUpdate: '&',
        onDelete: '&',
        onSelect: '&',
        type:'@' // stock,service,consu,master
    },
    controllerAs: 'ctrl'
});

/*
 * Select Account size Medium
 */
app.component('bicJournalSelect', {
    transclude: false,
    templateUrl: '/angular-templates/bic-journal-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.journals = [];

        ctrl.refreshJournals = function (text) {
            var params = {q: text, limit: 20};
            return $http.get('/account/account-journal/journal-list-json', {params: params})
                .then(function (response) {
                    ctrl.journals = response.data;
                });
        };

        ctrl.select = function(selected){
            ctrl.onSelect({cJournal:selected});
        }
    },
    bindings: {
        bicModel: '=',
        name: '@', //อ่าน name จาก attribute
        required: '@', //@ bindings can be used when the input is a string.
        onSelect: '&'
    }
});

/*
 * Select Account size Small
 */
app.component('bicAccountSelect', {
    templateUrl: '/angular-templates/bic-account-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.accounts = [];
        ctrl.refreshAccounts = function (text) {
            var params = {q: text, limit: 20};
            return $http.get('/account/account-account/account-list-json', {params: params})
                    .then(function (response) {
                        ctrl.accounts = response.data;
                    });
        };
    },
    bindings: {
        bicModel: '=',
        name: '@',
        required: '@'
    },
    controllerAs: 'ctrl'
});

/*
 * Select Account size Medium
 */
app.component('bicAccountSelectMd', {
    transclude: false,
    templateUrl: '/angular-templates/bic-account-select-md.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.accounts = [];
        ctrl.refreshAccounts = function (text) {
            var params = {q: text, limit: 20};
            return $http.get('/account/account-account/account-list-json', {params: params})
                    .then(function (response) {
                        ctrl.accounts = response.data;
                        //console.log(ctrl.bicModel);
                    });
        };
    },
    bindings: {
        bicModel: '=',
        name: '@', //อ่าน name จาก attribute
        required: '@' //@ bindings can be used when the input is a string.
    }
});

/*
 * Select Account Invoice
 */
app.component('bicAccountInvoiceSelect', {
    transclude: false,
    templateUrl: '/angular-templates/bic-account-invoice-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.accountInvoices = [];
        ctrl.refreshAccountInvoices = function (text) {
            var params = {q: text, limit: 20};
            return $http.get('/account/account-invoice/account-invoice-list-json', {params: params})
                    .then(function (response) {
                        ctrl.accountInvoices = response.data;
                        //console.log(ctrl.bicModel);
                    });
        };
        
        ctrl.openAccountInvoiceInfo = function(){
            if (!ctrl.bicModel.id) {
                return;
            }
            window.open(
                    '/account/account-invoice/view?id=' + ctrl.bicModel.id,
                    '_blank' // <- This is what makes it open in a new window.
                    );
        }
        
        ctrl.select = function(accountInvoice){
            ctrl.onSelect({cAccountInvoice:accountInvoice});
        }
    },
    bindings: {
        bicModel: '=', //accountInvoice
        name: '@', //อ่าน name จาก attribute
        required: '@', //@ bindings can be used when the input is a string.
        type:'@', // in_invoice,out_invoice
        inv_type:'@', //invoice,credit,debit
        onSelect:'&'
    },
    controllerAs:'ctrl'
});

/**
 * Component ตาราง account invoices
 */
app.component('bicAccountInvoiceComp', {
    templateUrl: '/angular-templates/bic-account-invoice-comp.html',
    controller: function ($http,$scope,$uibModal,$log,$document) {
        var ctrl = this;
        ctrl.state = null;
        ctrl.items = [];
        ctrl.selectedItems = [];

        // prop for paging
        ctrl.totalItems = 0;
        ctrl.currentPage = 1;
        ctrl.itemsPerPage = 5;
   
        ctrl.$onInit = function(){
            //ctrl.refreshData();
            $http.get('/account/account-invoice/doc-states')
               .then(function(response){
                  ctrl.states = response.data;
                  return ctrl.states;
               })
               .then(function(states){
                   console.log('states',states);
                   //ctrl.refreshData(); auto load แล้วใน watch
               });
        }
   
       ctrl.refreshData = function(){
           ctrl.selectedItems = [];
           var params = {
                q: $scope.search,
                limit:null,
                order_by:$scope.order_by,
                sort:$scope.sort,
                itemsPerPage:ctrl.itemsPerPage,
                currentPage:ctrl.currentPage};
           //var qparams = $scope.params;
           $http.get('/account/account-invoice/account-invoice-list-json2', {params: params})
                        .then(function (response) {
                            console.log("invoices",response.data);
                             ctrl.items = response.data.items;
                             ctrl.totalItems = response.data.totalItems;
                        });

       }

        $scope.$watch('search',function(newVal,oldVal){
            console.log("search change",newVal,oldVal);
            ctrl.refreshData();
        });
   
       //paging function
        ctrl.pageChanged = function(){
            console.log("Page changed to",ctrl.currentPage);
            ctrl.refreshData();
        }
        

        /**
         * Page action
         */
         ctrl.onSelectItem = function(item){
            if(item.selected==null){
                // add to selectedItems
                ctrl.selectedItems.push(item);
                item.selected=true;
            } else {
                // remove from selectedItems
                var index = ctrl.selectedItems.indexOf(item);
                ctrl.selectedItems.splice(index, 1);
                item.selected=null;
            }
            console.log("selectedItems",ctrl.selectedItems);
         }

        ctrl.ok  = function(){
            ctrl.close({$value:ctrl.selectedItems});
        }

        ctrl.cancel = function(){
            ctrl.dismiss({$value:'cancel'});
        }

        ctrl.stateLabel = function(state){
            if(state=='done'){
                return 'label label-success';
            }
            if(state=='draft'){
                return 'label label-warning'
            }
            return 'label label-default';
        }
        
    },
    bindings: {
        close:'&',
        dismiss:'&',
        resolve:'<',
    },
    controllerAs: 'ctrl'
});

/**
 * Partner
 */
app.component('bicPartnerSelect', {
    templateUrl: '/angular-templates/bic-partner-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.partners = [];
        ctrl.refreshPartners = function (supplier) {
            var params = {q: supplier, limit: 20};
            return $http.get('/resource/res-partner/partner-list-json', {params: params})
                    .then(function (response) {
                        ctrl.partners = response.data;
                    });
        };

        ctrl.viewPartner = function (partner_id) {
            if (!partner_id) {
                return;
            }
            window.open(
                    '/resource/res-partner/view?id=' + partner_id,
                    '_blank' // <- This is what makes it open in a new window.
                    );
        };

        ctrl.select = function(partner){
            ctrl.onSelect({cPartner:partner});
        }
    },
    bindings: {
        bicModel: '=',
        name: '@',
        required: '@',
        onSelect:'&'
    }
});

app.component('bicResUserSelect', {
    templateUrl: '/angular-templates/bic-res-user-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.resUsers = [];
        ctrl.refreshResUsers = function (input) {
            var params = {q: input, limit: 20};
            return $http.get('/resource/res-users/res-user-list-json', {params: params})
                    .then(function (response) {
                        ctrl.resUsers = response.data;
                    });
        };

        ctrl.viewResUser = function (_id) {
            if (!_id) {
                return;
            }
            window.open(
                    '/resource/res-users/view?id=' + _id,
                    '_blank' // <- This is what makes it open in a new window.
                    );
        };
    },
    bindings: {
        bicModel: '=',
        name: '@',
        required: '@'
    }
});

app.component('bicSaleAreaSelect', {
    templateUrl: '/angular-templates/bic-sale-area-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.areas = [];
        ctrl.refreshSaleAreas = function (input) {
            var params = {q: input, limit: 20};
            console.log('bicSaleAreaSelect params', params);
            return $http.get('/sale/sale-area/sale-area-list-json', {params: params})
                    .then(function (response) {
                        ctrl.areas = response.data;
                    });
        };

       ctrl.delete = function (){
           ctrl.onDelete({cArea: ctrl.bicModel});
           ctrl.bicModel = null;
       };

        ctrl.select = function (selected) {
            ctrl.onSelect({cArea: selected});
        };
    },
    bindings: {
        bicModel: '=',
        name: '@',
        required: '@',
        onDelete: '&',
        onSelect: '&'
    },
    controllerAs: 'ctrl'
});

/**
 * Product Lot
 *  <bic-lot-select bic-model="model.lot" required="true" depend-product-id="model.product.id"></bic-lot-select>
 */
app.component('bicLotSelect', {
    templateUrl: '/angular-templates/bic-lot-select.html',
    controller: function ($http,$uibModal,$log,$document,uibDateParser) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.showView = 'true';
        ctrl.showAdd = 'true';
        ctrl.showTable = 'true';
        ctrl.lots = [];
        //
        ctrl.modalSearch = "";
        ctrl.sort = "asc";

        ctrl.createLot = function (_product_id) {
            console.log('createLot',_product_id);

            // if (!_product_id) {
            //     return;
            // }
            
            ctrl.openFormModal();
            //$window.location.href = '/stock/stock-lot/create?id=' + id;
        };

        ctrl.onLotOpenClose = function (isOpen, product_id) {
            console.log('lot open');
            if (isOpen) {
                ctrl.refreshLots(product_id, null);
            }
        };

        ctrl.refreshLots = function (product_id, lot) {
            console.log('refresh lot for product_id',product_id);
            // if (!product_id) {
            //     return;
            // }
            var params = {q: lot, limit: 20, product_id: product_id};
            return $http.get('/stock/stock-lot/lot-list-json', {params: params})
                    .then(function (response) {
                        console.log(response.data);
                        var lots = response.data;
                        angular.forEach(lots,function(line,key){
                            ctrl.parser(line);
                        });
                        ctrl.lots = lots;
                        
                    });
        };

        ctrl.parser = function(lot){
            lot.expired_date = uibDateParser.parse(lot.expired_date, 'yyyy-MM-dd');
            lot.mfg_date = uibDateParser.parse(lot.mfg_date, 'yyyy-MM-dd');
            lot.recieved_date = uibDateParser.parse(lot.receieved_date, 'yyyy-MM-dd HH:mm:ss');
        }
        
        ctrl.viewLot = function(lot_id){
            
            if (!lot_id) {
                return;
            }
            window.open(
                    '/stock/stock-lot/view?id=' + lot_id,
                    '_blank' // <- This is what makes it open in a new window.
                    );
        };

        ctrl.select = function($lot){
            ctrl.onSelect({cLot:$lot});
        }
        
        // แสดง Modal - Select
        ctrl.openModal = function(size,parentSelector){
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation:true,
                ariaLabelledBy:'modal-title',
                ariaDescribedBy:'modal-body',
                templateUrl:'myModalContent.html',
                controller:function($uibModalInstance){
                    var mctrl = this;
                    mctrl.lots = [];
                    
                    mctrl.sort = "asc";
                                        
                    mctrl.sortToggle = function(_order_by){
                        if(mctrl.sort=="asc"){
                            mctrl.sort="desc";
                        } else {
                            mctrl.sort="asc";
                        }
                        mctrl.loadData(_order_by);
                    }
        
                    mctrl.loadData = function(_order_by=null){
            
                        console.log("load data with product_id=",ctrl.dependProductId," _search=",mctrl.modalSearch," sort=",ctrl.sort);
                        var params = {
                            q: mctrl.modalSearch, 
                            limit:null, 
                            product_id: ctrl.dependProductId,
                            order_by:_order_by,
                            sort:mctrl.sort
                        };
            
                        return $http.get('/stock/stock-lot/lot-list-json', {params: params})
                                .then(function (response) {
                                    console.log(response.data);
                                    mctrl.lots = response.data;
                                });
                    }
        
                    mctrl.select = function(_lot){
                        // ปิดและเลือก _lot จะส่งไปยัง result.then ด้านล่าง
                        $uibModalInstance.close(_lot);
                    }
                    
                    mctrl.cancel = function(){
                        $uibModalInstance.dismiss('cancel');
                        console.log("modal compoent");
                    }
                    
                    //
                    mctrl.loadData();
                },
                controllerAs:'mctrl',
                size:'lg',
                appendTo:parentElem,
            });
            
            modalInstance.result.then(function (selectedItem) {
                ctrl.bicModel = selectedItem; // คืนค่าให้ ctrl หลัก
                ctrl.select(selectedItem);
                console.log("modal result selected",selectedItem);
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }


        // แสดง Modal - form
        ctrl.openFormModal = function(size,parentSelector){
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation:true,
                ariaLabelledBy:'modal-title',
                ariaDescribedBy:'modal-body',
                templateUrl:'lotFormModalContent.html',
                controller:function($uibModalInstance){
                    var fctrl = this;
                    fctrl.lot = {id:-1};
                    fctrl.product_id = ctrl.dependProductId;
                    fctrl.msgs;

                    fctrl.saveData = function(){
                        fctrl.lot.mfg_date_str = (fctrl.lot.mfg_date)?moment(fctrl.lot.mfg_date).format("DD/MM/YYYY"):null;
                        fctrl.lot.expired_date_str = (fctrl.lot.expired_date)?moment(fctrl.lot.expired_date).format("DD/MM/YYYY"):null;
                        fctrl.lot.product_id = fctrl.product_id;
                        return $http.post('/stock/stock-lot/lot-save-json', {model: fctrl.lot})
                                .then(function (response) {
                                    fctrl.msgs = "";
                                    console.log(response.data);
                                    if(response.data.success){
                                        response.data.model.mfg_date = uibDateParser.parse(response.data.model.mfg_date, 'yyyy-MM-dd');
                                        response.data.model.expired_date = uibDateParser.parse(response.data.model.expired_date, 'yyyy-MM-dd');
                                        response.data.model.received_date = uibDateParser.parse(response.data.model.mfg_date, 'yyyy-MM-dd hh:mm:ss');
                                        fctrl.lot = response.data.model;
                                        $uibModalInstance.close(fctrl.lot);
                                    } else {
                                        angular.forEach(response.data,function(value,key){
                                            console.log(value);
                                            fctrl.msgs += value[0];
                                        });
                                    }
                                    
                                });
                    }

                    fctrl.save = function(_lot){
                        // ปิดและเลือก _lot จะส่งไปยัง result.then ด้านล่าง
                        fctrl.saveData();
                    }

                    fctrl.cancel = function(){
                        $uibModalInstance.dismiss('cancel');
                        console.log("modal compoent");
                    }
                },
                controllerAs:'fctrl',
                size:'md',
                appendTo:parentElem,
            });
            
            modalInstance.result.then(function (selectedItem) {
                ctrl.bicModel = selectedItem; // คืนค่าให้ ctrl หลัก
                ctrl.select(selectedItem);
                console.log("modal result selected="+selectedItem);
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }


    },
    bindings: {
        bicModel: '=',
        dependProductId: '=',
        name: '@',
        required: '@',
        showView: '@', // แสดงปุ่มสำหรับเรียกดู Lot
        showAdd: '@', // แสดงปุ่มสำหรับเพิ่ม Lot
        showTable:'@', //แสดงปุ่มสำหรับแสดง Lot พร้อมรายละเอียด
        onSelect:'&'
    }
});

/**
 * Lot แบบ input
 * ยังมีปัญหา คิดไม่ออก
 */
app.component('bicLotInput',{
    templateUrl:'/angular-templates/bic-lot-input.html',
    controller:function($http,$scope){
        var ctrl = this;
        ctrl.required = false;
        ctrl.text = "ใหม่";
        ctrl.input;
        ctrl.lot;
        
        ctrl.$onInit = function(){
            //ctrl.bicModel = {id:-1,name:ctrl.input};
        }

        ctrl.$onChanges = function(changesObj){
            console.log({log:'obj changes',msg:changesObj});
        }

        // $postLink() - Called after this controller's element and its children have been linked
        ctrl.$postLink = function(){
           console.log('post link'); 
           if(ctrl.bicModel){
               ctrl.text = "เดิม";
               ctrl.input = ctrl.bicModel.name;
           }
        }
        
        ctrl.refreshLots = function (product_id, lot) {
            console.log('refresh lot for product_id=',product_id);
//            if (!product_id) {
//                return;
//            }
            var params = {name: lot};
            return $http.get('/stock/stock-lot/find-lot-by-name-json', {params: params})
                    .then(function (response) {
                        console.log(response.data); 
                        ctrl.bicModel = response.data;
                        if(ctrl.bicModel){
                            ctrl.text = "เดิม";
                        } else {
                            ctrl.text = "ใหม่";
                    
                        }
                    });
        };
        
        ctrl.onInputChange = function(_input){
            console.log("input change=",_input);
            if(_input){
                ctrl.refreshLots(ctrl.dependProductId,_input);
            } else {
                ctrl.bicModel = null;
            }
        };
       
    },
    bindings: {
        bicModel: '=',
        dependProductId: '=',
        name: '@',
        required: '@'
    }
});
/**
 * Product Unit of measure
 */
app.component('bicUomSelect', {
    templateUrl: '/angular-templates/bic-uom-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.uoms = [];


        ctrl.onUomOpenClose = function (isOpen, product_id) {
            console.log('uom open');
            if (isOpen) {
                ctrl.refreshUoms(product_id, null);
            }
        };

        ctrl.refreshUoms = function (product_id, q) {
            console.log('refresh uom for product_id', product_id);
            var params = {q:q,product_id: product_id};
            return $http.get('/product/product-product/available-prod-uom-json', {params: params})
                    .then(function (response) {
                        console.log(response.data);
                        ctrl.uoms = response.data;
                    });
        };

        ctrl.select = function(selected){
            ctrl.onSelect({cUom: selected});
        }
    },
    bindings: {
        bicModel: '=',
        dependProductId: '=',
        name: '@',
        required: '@',
        onSelect: '&'
    }
});

/**
 *  Stock Picking Type 
 */
app.component('bicPickingTypeSelect',{
    templateUrl: '/angular-templates/bic-picking-type-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.pickingTypes = [];
        ctrl.showView = false;

        ctrl.whGroupFn = function(item){
            return item.warehouse.name;
        }

        ctrl.onPickingTypeOpenClose = function (isOpen, product_id) {
            console.log('picking type open');
            if (isOpen) {
                ctrl.refreshPickingTypes(product_id, null);
            }
        };

        ctrl.refreshPickingTypes = function (input) {
            var params = {q: input, limit: 20};
            var url = '/stock/stock-picking-type/picking-type-list-json';
            return $http.get(url, {params: params})
                    .then(function (response) {
                        ctrl.pickingTypes = response.data;
                    });
        };

        ctrl.select = function(selected){
            console.log("SELECTED");
            ctrl.onSelect({cPickingType: selected});
        }
    },
    bindings: {
        bicModel: '=',
        name: '@',
        required: '@',
        showView:'=?', //optional
        onSelect: '&'
    }
});

/**
 * Stock Location
 * เลือก Location ตาม picking Type จะสามารถแยกเรื่องต้นทาง ปลายทาง
 */
app.component('bicLocationSelect', {
    templateUrl: '/angular-templates/bic-location-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.locations = [];
        ctrl.showView = false;

        ctrl.onLocationOpenClose = function (isOpen, product_id) {
            console.log('location open');
            if (isOpen) {
                ctrl.refreshLocations(product_id, null);
            }
        };

        ctrl.refreshLocations = function (location) {
            var params = {q: location, limit: 20, picking_type_id: ctrl.pickingTypeId};
            var url;
            if(ctrl.type==='src'){
                url = '/stock/stock-location/location-src-list-json';
            } else {
                url = '/stock/stock-location/location-dest-list-json';
            }
            return $http.get(url, {params: params})
                    .then(function (response) {
                        ctrl.locations = response.data;
                    });
        };

        ctrl.select = function(location){
            console.log("SELECTED");
            ctrl.onSelect({cLocation: selected});
        };

    },
    bindings: {
        bicModel: '=',
        name: '@',
        required: '@',
        type: '@', // [src,dest]
        pickingTypeId:'=',
        onSelect: '&',
        showView:'=?' //optional
    }
});

/**
 * Stock Location
 * เลือก Location ทั้งหมด filter ตาม usages
 */
app.component('bicLocationAllSelect', {
    templateUrl: '/angular-templates/bic-location-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.locations = [];
        ctrl.showView = false;
        ctrl.usages = "internal"; //default
        
        ctrl.onLocationOpenClose = function (isOpen, product_id) {
            console.log('location open');
            if (isOpen) {
                ctrl.refreshLocations(product_id, null);
            }
        };

        ctrl.refreshLocations = function (location) {
            var params = {q: location, limit: 10,usages:ctrl.usages};
            var url = '/stock/stock-location/location-all-list-json';
            
            return $http.get(url, {params:params})
                    .then(function (response) {
                        ctrl.locations = response.data;
                    });
        };

        ctrl.select = function(location){
            console.log("SELECTED");
            ctrl.onSelect({cLocation: location});
        };
    },
    bindings: {
        bicModel: '=',
        usages:'@', //example internal,supplier
        name: '@',
        required: '@',
        onSelect: '&',
        showView:'=?' //optional
    }
});

/**
 * Related Model
 */
app.component('bicRelatedSelect', {
    templateUrl: '/angular-templates/bic-related-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        
        ctrl.bicRelatedTable = 'account_invoice';
        ctrl.bicRelatedId = 1;
        ctrl.related_tables = ['sale_order','purchase_order','account_invoice'];
        ctrl.related_models = [];
        
        ctrl.onTableOpenClose = function(isOpen){
            if(isOpen){
                
            }
        };
        
        ctrl.onTableSelect = function(table){
            console.log('select ='+table);
            ctrl.bicRelatedId = null;
        }
        
        ctrl.onModelOpenClose = function(isOpen,table){
            if(isOpen){
                ctrl.refreshModels(null);
            }
        };
        

        ctrl.refreshModels = function(q){
            console.log({log:'refreshModels with talbe',msg:ctrl.bicRelatedTable});
            if(ctrl.bicRelatedTable === null){
                return;
            }
            var _params = {table:ctrl.bicRelatedTable,q:q};
            $http.get('/related-model/load-models',{params:_params})
                    .then(function(response){
                        ctrl.related_models = response.data;
                    });
        };
        
        ctrl.refreshTables = function(){
            
        };
        
        ctrl.delete = function(){
            ctrl.bicRelatedTable = null;
            ctrl.bicRelatedId = null;
        }

        
    },
    bindings: {
        bicModel: '=',
        name: '@',
        required: '@'
    },
    controllerAs: 'ctrl'
});

/**
 * PO , Product Modal
 */
app.component('bicPoProductDialog', {
    templateUrl: '/angular-templates/bic-po-product-dialog.html',
    controller: function ($http,$scope) {
        var ctrl = this;
        ctrl.required = false;
        
        ctrl.purchaseOrder = null;
        ctrl.poLine = null;
        ctrl.purchaseOrders = [];
        ctrl.purchaseOrderLines = [];
        
        ctrl.refreshPurchaseOrders = function(_q){
            var params = {q: _q};
            var url = '/purchase/purchase-order/find-purchase-orders';
            return $http.get(url, {params: params})
                    .then(function (response) {
                        console.log(response);
                        ctrl.purchaseOrders = response.data.po;
                        //ctrl.purchaseOrderLines = response.data.po_lines;
                    });
        };
        
        ctrl.onPoSelect = function(){
            ctrl.poLine = null;
        };
        
        ctrl.done = function(){
            //ยืนยันการเลือก
            console.log("done");
            console.log({log:"เลือก po",msg:ctrl.purchaseOrder});
            console.log({log:"เลือก po_line",msg:ctrl.poLine});
            ctrl.onSelect({
                cPo: ctrl.purchaseOrder,
                cPoLine:ctrl.poLine
            }); //ยิง event ให้ listener
        };

        
    },
    bindings: {
        bicModel: '=',
        name: '@',
        onSelect: '&'
    },
    controllerAs: 'ctrl'
});

/**
 * Dialog เพื่อเลือก PO
 */
app.component('bicPoDialog', {
    templateUrl: '/angular-templates/bic-po-dialog.html',
    controller: function ($http,$scope,$uibModal,$log,$document) {
        var ctrl = this;
        ctrl.state = null;

        ctrl.done = function(selectedPo){
            //ยืนยันการเลือก
            console.log("done");
            //console.log({log:"เลือก po",msg:ctrl.purchaseOrder});
            //console.log({log:"เลือก po_line",msg:ctrl.poLine});
            ctrl.onSelect({
                cPo: selectedPo,
                
            }); //ยิง event ให้ listener
        };
        
        // แสดง Modal
        ctrl.openModal = function(size,parentSelector){
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation:true,
                ariaLabelledBy:'modal-title',
                ariaDescribedBy:'modal-body',
                templateUrl:'poModalContent.html',
                controller:function($uibModalInstance){
                    var mctrl = this;
                    mctrl.purchaseOrders = [];
                    
                    mctrl.sort = "asc";
                                        
                    mctrl.sortToggle = function(_order_by){
                        if(mctrl.sort=="asc"){
                            mctrl.sort="desc";
                        } else {
                            mctrl.sort="asc";
                        }
                        mctrl.loadData(_order_by);
                    }
                     
                    mctrl.loadData = function(_order_by=null){
                        console.log("refresh PO");
                        var params = {
                            q: mctrl.modalSearch,
                            state:ctrl.state,
                            limit:null,
                            order_by:_order_by,
                            sort:mctrl.sort
                        };
                        var url = '/purchase/purchase-order/find-purchase-orders';
                        return $http.get(url, {params: params})
                                .then(function (response) {
                                    console.log(response);
                                    mctrl.purchaseOrders = response.data.po;    
                                });
                    };
        
                    mctrl.select = function(_po){
                        // ปิดและเลือก _po จะส่งไปยัง result.then ด้านล่าง
                        $uibModalInstance.close(_po);
                    }
                    
                    mctrl.cancel = function(){
                        $uibModalInstance.dismiss('cancel');
                        console.log("modal compoent");
                    }
                    
                    //
                    mctrl.loadData();
                },
                controllerAs:'mctrl',
                size:'lg',
                appendTo:parentElem,
            });
            
            modalInstance.result.then(function (selectedItem) {
                ctrl.done(selectedItem); //คืนค่าให้ ctrl หลัก
                console.log({log:"modal result selected",msg:selectedItem});
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

        
    },
    bindings: {
        onSelect: '&',
        state:'@' //draft,send,approved,done
    },
    controllerAs: 'ctrl'
});

/**
 * Dialog เพื่อเลือกรายการใน PO 
 */
app.component('bicPoItemsDialog', {
    templateUrl: '/angular-templates/bic-po-items-dialog.html',
    controller: function ($http,$scope,$uibModal,$log,$document) {
        var ctrl = this;
        ctrl.title = 'เลือกจาก PO x';
        ctrl.viewMode = 'account'; //default account view
        ctrl.done = function(selectedPO,selectedLines){
            //ยืนยันการเลือก
            console.log("done",selectedPO,selectedLines);
            //console.log({log:"เลือก po",msg:ctrl.purchaseOrder});
            //console.log({log:"เลือก po_line",msg:ctrl.poLine});
            ctrl.onSelect({
                cPo: selectedPO,
                cPoLines:selectedLines,
                
            }); //ยิง event ให้ listener
        };
        
        // แสดง Modal
        ctrl.openModal = function(size,parentSelector){
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation:true,
                ariaLabelledBy:'modal-title',
                ariaDescribedBy:'modal-body',
                templateUrl:'poItemsModalContent.html',
                controller:function($uibModalInstance,$filter,uibDateParser){
                    var mctrl = this;
                    mctrl.viewMode = ctrl.viewMode;

                    mctrl.purchaseOrders = [];
                    mctrl.purchaseOrderLines = [];

                    mctrl.selectedPO;
                    mctrl.selectedLines = [];

                    mctrl.sort = "asc";

                    mctrl.$onInit = function (){
                        mctrl.loadData();
                    }
                                        
                    mctrl.sortToggle = function(_order_by){
                        if(mctrl.sort=="asc"){
                            mctrl.sort="desc";
                        } else {
                            mctrl.sort="asc";
                        }
                        mctrl.loadData(_order_by);
                    }
                     
                    mctrl.loadData = function(_order_by=null){
                        console.log("refresh PO");
                        var params = {
                            q: mctrl.modalSearch,
                            limit:null,
                            order_by:_order_by,
                            sort:mctrl.sort,
                            state:ctrl.poState,
                            product_types:'stock'
                        };
                        var url = '/purchase/purchase-order/find-purchase-orders';
                        return $http.get(url, {params: params})
                                .then(function (response) {
                                    console.log(response);
                                    //parser date data
                                    var po_arr = response.data.po;
                                    angular.forEach(po_arr,function(item,key){
                                        item.date_order = uibDateParser.parse(item.date_order, 'yyyy-MM-dd');
                                        item.minimum_planned_date = uibDateParser.parse(item.minimum_planned_date, 'yyyy-MM-dd');
                                    });
                                    
                                    mctrl.purchaseOrders = po_arr;
                                }, function errorCallback(response) {
                                    //erroe
                                    // called asynchronously if an error occurs
                                    // or server returns response with an error status.
                                    bootbox.alert(response.data);
                                    });
                    };

                    mctrl.loadLine = function(id){
                        console.log("load line for PO",id);
                        var params = {
                            id:id
                        };
                        var url = '/purchase/purchase-order/find-purchase-order-lines';
                        return $http.get(url,{params:params})
                                .then(function(response){
                                    console.log('find-purchase-order-lines',response);
                                    mctrl.purchaseOrderLines = response.data.purchaseOrderLines;
                                }, function errorCallback(response) {
                                    //erroe
                                    // called asynchronously if an error occurs
                                    // or server returns response with an error status.
                                    bootbox.alert(response.data);
                                    });
                    }

                    mctrl.onSelectPO = function(_po){
                        // clear selected val
                        angular.forEach(mctrl.purchaseOrders,function(po,key){
                            po.selected = false;
                        });
                        mctrl.selectedLines = [];

                        _po.selected = true;
                        mctrl.selectedPO = _po;
                        //angular.copy(_so.saleOrderLines,mctrl.saleOrderLines);
                        mctrl.loadLine(_po.id);
                    }

                    mctrl.onSelectLine = function(_line){
                        var contains = $filter('filter')(mctrl.selectedLines,{'id':_line.id});
                        if(contains.length<=0){
                            mctrl.selectedLines.push(_line);
                            _line.selected = true;
                        } else {
                            var index = mctrl.selectedLines.indexOf(_line);
                            mctrl.selectedLines.splice(index, 1);
                            _line.selected = false;
                        }
                        
                    }
        
                    mctrl.done = function(){
                        // ปิดและเลือก _po จะส่งไปยัง result.then ด้านล่าง
                        console.log('xx done',mctrl.selectedLines);
                        $uibModalInstance.close({po:mctrl.selectedPO,lines:mctrl.selectedLines});
                    }
                    
                    mctrl.cancel = function(){
                        $uibModalInstance.dismiss('cancel');
                        console.log("modal compoent");
                    }
                    
                    //
                    
                },
                controllerAs:'mctrl',
                size:'lg',
                appendTo:parentElem,
            });
            
            modalInstance.result.then(function (data) {
                ctrl.done(data.po,data.lines); //คืนค่าให้ ctrl หลัก
                console.log('modal done',data.po,data.lines);
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

        
    },
    bindings: {
        onSelect: '&',
        title:'@',
        poState:'@',
        viewMode:'@' //stock,account
    },
    controllerAs: 'ctrl'
});

/**
 * Dialog เพื่อเลือกรายการใน PO 
 */
app.component('bicPoLinesDialog', {
    templateUrl: '/angular-templates/bic-po-lines-dialog.html',
    controller: function ($http,$scope,$uibModal,$log,$document) {
        var ctrl = this;
        ctrl.title = 'เลือกจาก PO x';
        ctrl.viewMode = 'account'; //default account view
        ctrl.done = function(selectedPO,selectedLines){
            //ยืนยันการเลือก
            console.log("done",selectedPO,selectedLines);
            //console.log({log:"เลือก po",msg:ctrl.purchaseOrder});
            //console.log({log:"เลือก po_line",msg:ctrl.poLine});
            ctrl.onSelect({
                cPo: selectedPO,
                cPoLines:selectedLines,
                
            }); //ยิง event ให้ listener
        };
        
        // แสดง Modal
        ctrl.openModal = function(size,parentSelector){
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation:true,
                ariaLabelledBy:'modal-title',
                ariaDescribedBy:'modal-body',
                templateUrl:'poLinesModalContent.html',
                controller:function($uibModalInstance,$filter,uibDateParser){
                    var mctrl = this;
                    mctrl.viewMode = ctrl.viewMode;

                    mctrl.purchaseOrders = [];
                    mctrl.purchaseOrderLines = [];

                    mctrl.selectedPO;
                    mctrl.selectedLines = [];

                    mctrl.sort = "asc";
                    // prop for paging
                    mctrl.totalItems = 0;
                    mctrl.currentPage = 1;
                    mctrl.itemsPerPage = 10;
                    
                    // other prop
                    mctrl.view_residual = true;

                    mctrl.$onInit = function (){
                        mctrl.loadData();
                    }
                                        
                    mctrl.sortToggle = function(_order_by){
                        if(mctrl.sort=="asc"){
                            mctrl.sort="desc";
                        } else {
                            mctrl.sort="asc";
                        }
                        mctrl.loadData(_order_by);
                    }
                     
                    mctrl.loadData = function(_order_by=null){
                        var params = {
                            q: mctrl.modalSearch,
                            limit:null,
                            order_by:_order_by,
                            sort:mctrl.sort,
                            state:ctrl.poState,
                            view_residual:mctrl.view_residual,
                            product_type:ctrl.productType,
                            itemsPerPage:mctrl.itemsPerPage,
                            currentPage:mctrl.currentPage
                        };
                        console.log("refresh PO",params);
                        var url = '/purchase/purchase-order-line/find-purchase-order-lines';
                        return $http.get(url, {params: params})
                                .then(function (response) {
                                    console.log(response);
                                    //parser date data
                                    
                                    mctrl.purchaseOrderLines = response.data.purchaseOrderLines;
                                    mctrl.totalItems = response.data.totalItems;
                                }, function errorCallback(response) {
                                    //erroe
                                    // called asynchronously if an error occurs
                                    // or server returns response with an error status.
                                    bootbox.alert('<tt>'+response.data.message+'</tt>');
                                    });
                    };

                    mctrl.onSelectLine = function(_line){
                        var contains = $filter('filter')(mctrl.selectedLines,{'id':_line.id});
                        if(contains.length<=0){
                            mctrl.selectedLines.push(_line);
                            _line.selected = true;
                        } else {
                            var index = mctrl.selectedLines.indexOf(_line);
                            mctrl.selectedLines.splice(index, 1);
                            _line.selected = false;
                        }
                        
                    }
                    
                    //paging function
                    mctrl.pageChanged = function(){
                        console.log("Page changed to",mctrl.currentPage);
                        mctrl.loadData(mctrl.orderBy);
                    }
        
                    //
                    mctrl.done = function(){
                        // ปิดและเลือก _po จะส่งไปยัง result.then ด้านล่าง
                        console.log('xx done',mctrl.selectedLines);
                        mctrl.selectedPO = mctrl.selectedLines[0].order;
                        $uibModalInstance.close({po:mctrl.selectedPO,lines:mctrl.selectedLines});
                    }
                    
                    mctrl.cancel = function(){
                        $uibModalInstance.dismiss('cancel');
                        console.log("modal compoent");
                    }
                    
                    //
                    
                },
                controllerAs:'mctrl',
                size:'lg',
                appendTo:parentElem,
            });
            
            modalInstance.result.then(function (data) {
                ctrl.done(data.po,data.lines); //คืนค่าให้ ctrl หลัก
                console.log('modal done',data.po,data.lines);
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

        
    },
    bindings: {
        onSelect: '&',
        title:'@',
        poState:'@',
        productType:'@', //stock,consu
        viewMode:'@' //stock,account
    },
    controllerAs: 'ctrl'
});

/**
 * Address Select
 */
app.component('bicAddressSelect', {
    templateUrl: '/angular-templates/bic-address-select.html',
    controller: function ($http,$scope) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.addresses = [];
        ctrl.dependPartnerId;
        ctrl.type = 'partner';

        ctrl.onAddressOpenClose = function(isOpen){
            if(isOpen){
                console.log({log:'refresh address with ID',msg:ctrl.dependPartnerId});
                ctrl.addresses = [];//clear data
                ctrl.refreshAddresses("");
            }
            
        }
        
        ctrl.refreshAddresses = function(_q){
            var params = {partner_id:ctrl.dependPartnerId,q: _q};
            var url = null;
            if(ctrl.type=='partner'){
                url = '/resource/res-partner/address-list-json';
            } else {
                url = '/resource/res-partner/address-we-list-json';
            }
            return $http.get(url, {params: params})
                    .then(function (response) {
                        console.log(response);
                        ctrl.addresses = response.data;
                
                    });
        };
        
    },
    bindings: {
        bicModel: '=',
        name: '@',
        required:'@',
        dependPartnerId:'=?',
        type: '@?' //['partner','we']

    }
});

/**
 * Sale Transport
 * เลือก Transport ทั้งหมด 
 */

app.component('bicTransportSelect', {
    templateUrl: '/angular-templates/bic-transport-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.transports = [];
        ctrl.showView = false;

        ctrl.onTransportOpenClose = function (isOpen) {
            console.log("transport open");
            if(isOpen) {
                ctrl.refreshTransport(null);
            }
        };

        ctrl.refreshTransport = function (transport) {
            var params = {q: transport, limit: 10};
            var url = '/sale/sale-transport/transport-all-list-json';

            return $http.get(url, {params: params})
                .then(function (response) {
                    ctrl.transports = response.data;
                });
        };
    },
    bindings: {
        bicModel: '=',
        name: '@',
        required: '@',
        showView: '=?'
    }
});

/**
 * Dialog เพื่อเลือก Product
 */
app.component('bicProductDialog', {
    templateUrl: '/angular-templates/bic-product-dialog.html',
    controller: function ($http,$scope,$uibModal,$log,$document) {
        var ctrl = this;
        
        ctrl.title = "เลือกสินค้า/วัตถุดิบ";
        ctrl.$onInit = function(){
            //console.log('bicProductDialog init',ctrl.myObject);
        }
        ctrl.done = function(selectedItems){
            //ยืนยันการเลือก
            console.log("done");
            
            ctrl.onSelect({
                cProducts: selectedItems,
                
            }); //ยิง event ให้ listener
        };
        
        // แสดง Modal
        ctrl.openModal = function(size,parentSelector){
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation:true,
                ariaLabelledBy:'modal-title',
                ariaDescribedBy:'modal-body',
                templateUrl:'productModalContent.html',
                controller:function($uibModalInstance,$filter, $scope){
                    var mctrl = this;
                    mctrl.products = [];
                    mctrl.selectedItems = [];
                    mctrl.sort = "asc";
                    mctrl.orderBy = null;

                    mctrl.totalItems = 0;
                    mctrl.currentPage = 1;
                    mctrl.itemsPerPage = 10;

                    mctrl.$onInit = function (){
                        mctrl.loadData();
                    }
                                        
                    mctrl.sortToggle = function(_order_by){
                        if(mctrl.sort=="asc"){
                            mctrl.sort="desc";
                        } else {
                            mctrl.sort="asc";
                        }
                        mctrl.loadData(_order_by);
                    }
                     
                    mctrl.loadData = function(_order_by=null){
                        console.log("refresh Products");
                        mctrl.selectedItems = []; // clear value;
                        var params = {
                            q: mctrl.modalSearch,
                            types:ctrl.types,
                            limit:null,
                            order_by:_order_by,
                            sort:mctrl.sort,
                            itemsPerPage: mctrl.itemsPerPage,
                            currentPage: mctrl.currentPage
                        };
                        var url = '/product/product-product/find-products-json';
                        console.log('bicProductDialog params',params);
                        return $http.get(url, {params: params})
                                .then(function (response) {
                                    console.log(response);
                                    mctrl.products = response.data.lines;  
                                    mctrl.totalItems = response.data.totalItems;  
                                });
                    };

                    mctrl.addSelect = function(p){
                        if(p.selected==null){
                            // add to selectedItems
                            mctrl.selectedItems.push(p);
                            p.selected=true;
                        } else {
                            // remove from selectedItems
                            var index = mctrl.selectedItems.indexOf(p);
                            mctrl.selectedItems.splice(index, 1);
                            p.selected=null;
                        }
                        console.log(mctrl.selectedItems);
                    }

                    // paging function 
                    mctrl.pageChanged = function (){
                        console.log("Page changed to", mctrl.currentPage);
                        mctrl.loadData(mctrl.order_by);
                    }
        
                    mctrl.done = function(){
                        // ปิดและเลือก products จะส่งไปยัง result.then ด้านล่าง
                        $uibModalInstance.close(mctrl.selectedItems);
                    }
                    
                    mctrl.cancel = function(){
                        $uibModalInstance.dismiss('cancel');
                        console.log("modal compoent");
                    }
                    
                    //
                    mctrl.loadData();
                },
                controllerAs:'mctrl',
                size:'lg',
                appendTo:parentElem,
            });
            
            modalInstance.result.then(function (selectedItems) {
                ctrl.done(selectedItems); //คืนค่าให้ ctrl หลัก
                console.log({log:"modal result selected",msg:selectedItems});
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

        
    },
    bindings: {
        title:'@', //ชื่อ ปุ่ม
        types:'@', // type stock,service,consu,master
        onSelect: '&', // return array of Product
    },
    controllerAs: 'ctrl'
});

/**
 * Dialog เพื่อเลือก Product พร้อม Lot
 */
app.component('bicProductLotDialog', {
    templateUrl: '/angular-templates/bic-product-lot-dialog.html',
    controller: function ($http,$scope,$uibModal,$log,$document,$resource) {
        var ctrl = this;
        
        ctrl.title = "เลือกสินค้า/วัตถุดิบ";

        ctrl.done = function(selectedItems){
            //ยืนยันการเลือก
            console.log("done");
            
            ctrl.onSelect({
                cProducts: selectedItems,
                
            }); //ยิง event ให้ listener
        };
        
        // แสดง Modal
        ctrl.openModal = function(size,parentSelector){
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation:true,
                ariaLabelledBy:'modal-title',
                ariaDescribedBy:'modal-body',
                templateUrl:'productLotModalContent.html',
                controller:function($uibModalInstance,$filter,$scope){
                    var mctrl = this;
                    mctrl.products = [];
                    mctrl.selectedItems = [];
                    mctrl.sort = "asc";
                    mctrl.orderBy = null;
                    mctrl.categories = null;
                    mctrl.selected_cat_id = null;
                    // prop for paging
                    mctrl.totalItems = 0;
                    mctrl.currentPage = 1;
                    mctrl.itemsPerPage = 10;
                    
                    mctrl.$onInit = function(){
                        mctrl.locationModel = ctrl.locationModel;
                        mctrl.loadData().then(function(response){
                            $http.get('/api/product-categories').then(function(response){
                                console.log("Category",response.data);
                                mctrl.categories = response.data;

                            });
                            
                        });
                    }
                                        
                    mctrl.sortToggle = function(_order_by){
                        if(mctrl.sort=="asc"){
                            mctrl.sort="desc";
                        } else {
                            mctrl.sort="asc";
                        }
                        mctrl.loadData(_order_by);
                    }
                    
                    mctrl.loadData = function(_order_by=null){
                        mctrl.orderBy = _order_by;
                        console.log("refresh Products");
                        mctrl.selectedItems = []; // clear value;
                        var location_id = (mctrl.locationModel)?mctrl.locationModel.id:null;
                        var params = {
                            q: mctrl.modalSearch,
                            category_id:mctrl.selected_cat_id,
                            location_id:location_id,
                            limit:null,
                            order_by:_order_by,
                            sort:mctrl.sort,
                            itemsPerPage:mctrl.itemsPerPage,
                            currentPage:mctrl.currentPage
                        };
                        var url = '/product/product-product/find-product-lots-json';
                        return $http.get(url, {params: params})
                                .then(function (response) {
                                    console.log(response);
                                    mctrl.products = response.data.items;  
                                    mctrl.totalItems = response.data.totalItems;
                                });
                    };
                    
                    mctrl.onRemoveLocation = function(){
                        mctrl.locationModel = null;
                        mctrl.loadData();
                    }

                    mctrl.addSelect = function(p){
                        if(p.selected==null){
                            // add to selectedItems
                            mctrl.selectedItems.push(p);
                            p.selected=true;
                        } else {
                            // remove from selectedItems
                            var index = mctrl.selectedItems.indexOf(p);
                            mctrl.selectedItems.splice(index, 1);
                            p.selected=null;
                        }
                        console.log(mctrl.selectedItems);
                    }
                    
                    //paging function
                    mctrl.pageChanged = function(){
                        console.log("Page changed to",mctrl.currentPage);
                        mctrl.loadData(mctrl.orderBy);
                    }
        
                    mctrl.done = function(){
                        // ปิดและเลือก products จะส่งไปยัง result.then ด้านล่าง
                        $uibModalInstance.close(mctrl.selectedItems);
                    }
                    
                    mctrl.cancel = function(){
                        $uibModalInstance.dismiss('cancel');
                        console.log("modal compoent");
                    }
                    
                    //
                    
                },
                controllerAs:'mctrl',
                size:'lg',
                appendTo:parentElem,
            });
            
            modalInstance.result.then(function (selectedItems) {
                ctrl.done(selectedItems); //คืนค่าให้ ctrl หลัก
                console.log({log:"modal result selected",msg:selectedItems});
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

        
    },
    bindings: {
        title:'@', //ชื่อ ปุ่ม
        onSelect: '&', // return array of Product
        locationModel:'='
    },
    controllerAs: 'ctrl'
});

/**
 * Dialog เพื่อเลือก SO
 */
app.component('bicSoDialog', {
    templateUrl: '/angular-templates/bic-so-dialog.html',
    controller: function ($http,$scope,$uibModal,$log,$document) {
        var ctrl = this;
        
        ctrl.done = function(selectedSO,selectedLines){
            //ยืนยันการเลือก
            console.log("done",selectedSO,selectedLines);
            //console.log({log:"เลือก po",msg:ctrl.purchaseOrder});
            //console.log({log:"เลือก po_line",msg:ctrl.poLine});
            ctrl.onSelect({
                cSo: selectedSO,
                cSoLines:selectedLines,
                
            }); //ยิง event ให้ listener
        };
        
        // แสดง Modal
        ctrl.openModal = function(size,parentSelector){
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation:true,
                ariaLabelledBy:'modal-title',
                ariaDescribedBy:'modal-body',
                templateUrl:'soModalContent.html',
                controller:function($uibModalInstance,$filter,uibDateParser){
                    var mctrl = this;
                    mctrl.saleOrders = [];
                    mctrl.saleOrderLines = [];

                    mctrl.selectedSO;
                    mctrl.selectedLines = [];

                    mctrl.sort = "asc";
                                        
                    mctrl.sortToggle = function(_order_by){
                        if(mctrl.sort=="asc"){
                            mctrl.sort="desc";
                        } else {
                            mctrl.sort="asc";
                        }
                        mctrl.loadData(_order_by);
                    }
                     
                    mctrl.loadData = function(_order_by=null){
                        console.log("refresh SO");
                        var params = {
                            q: mctrl.modalSearch,
                            limit:null,
                            order_by:_order_by,
                            sort:mctrl.sort
                        };
                        var url = '/sale/sale-order/find-sale-orders';
                        return $http.get(url, {params: params})
                                .then(function (response) {
                                    console.log(response);
                                    //parser date data
                                    var so_arr = response.data.saleOrders;
                                    angular.forEach(so_arr,function(item,key){
                                        item.date_order = uibDateParser.parse(item.date_order, 'yyyy-MM-dd HH:mm:ss');
                                        item.date_schedule = uibDateParser.parse(item.date_schedule, 'yyyy-MM-dd HH:mm:ss');
                                        
                                        var state = item.state;
                                        var txtState = [
                                            {txt: "approved", value: "อนุมัติ"},
                                            {txt: "draft", value: "ดราฟ"},
                                            {txt: "confirmed", value: "รออนุมัติ"},
                                            {txt: "manager", value: "รอผู้จัดการอนุมัติ"}
                                        ];
                                        if(state === txtState[0].txt) {                                            
                                            item.state = txtState[0].value;
                                        } else if(state === txtState[1].txt) {                                           
                                            item.state = txtState[1].value; 
                                        } else if(state === txtState[2].txt) {
                                            item.state = txtState[2].value;
                                        } else if(state === txtState[3].txt) {
                                            item.state = txtState[3].value;
                                        }
                                    });
                                    
                                    mctrl.saleOrders = so_arr;
                                });
                    };

                    mctrl.loadLine = function(id){
                        console.log("load line for SO",id);
                        var params = {
                            id:id
                        };
                        var url = '/sale/sale-order/find-sale-order-lines';
                        return $http.get(url,{params:params})
                                .then(function(response){
                                    console.log('find-sale-order-lines',response);
                                    mctrl.saleOrderLines = response.data.saleOrderLines;
                                    var cJson = Object.keys(mctrl.saleOrderLines).length;
                                    mctrl.countLines = cJson;
                                    console.log("find-count-order-lines", mctrl.countLines);
                        });
                    }

                    mctrl.onSelectSO = function(_so){
                        // clear selected val
                        angular.forEach(mctrl.saleOrders,function(so,key){
                            so.selected = false;
                        });
                        mctrl.selectedLines = [];

                        _so.selected = true;
                        mctrl.selectedSO = _so;
                        //angular.copy(_so.saleOrderLines,mctrl.saleOrderLines);
                        mctrl.loadLine(_so.id);
                    }

                    mctrl.onSelectLine = function(_line){
                        var contains = $filter('filter')(mctrl.selectedLines,{'id':_line.id});
                        if(contains.length<=0){
                            mctrl.selectedLines.push(_line);
                            _line.selected = true;
                        } else {
                            var index = mctrl.selectedLines.indexOf(_line);
                            mctrl.selectedLines.splice(index, 1);
                            _line.selected = false;
                        }
                        
                    }
        
                    mctrl.done = function(){
                        // ปิดและเลือก _po จะส่งไปยัง result.then ด้านล่าง
                        console.log('xx done',mctrl.selectedLines);
                        $uibModalInstance.close({so:mctrl.selectedSO,lines:mctrl.selectedLines});
                    }
                    
                    mctrl.cancel = function(){
                        $uibModalInstance.dismiss('cancel');
                        console.log("modal compoent");
                    }
                    
                    //
                    mctrl.loadData();
                },
                controllerAs:'mctrl',
                size:'lg',
                appendTo:parentElem,
            });
            
            modalInstance.result.then(function (data) {
                ctrl.done(data.so,data.lines); //คืนค่าให้ ctrl หลัก
                console.log('modal done',data.so,data.lines);
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

        
    },
    bindings: {
        onSelect: '&'
    },
    controllerAs: 'ctrl'
});


/**
 * Dialog เพื่อเลือก PR lines
 */
app.component('bicPrLinesDialog', {
    templateUrl: '/angular-templates/bic-pr-lines-dialog.html',
    controller: function ($http,$scope,$uibModal,$log,$document) {
        var ctrl = this;
        
        ctrl.title = "เลือกจาก PR";

        ctrl.done = function(selectedLines){
            //ยืนยันการเลือก
            console.log("done",selectedLines);
            //console.log({log:"เลือก po",msg:ctrl.purchaseOrder});
            //console.log({log:"เลือก po_line",msg:ctrl.poLine});
            ctrl.onSelect({
                cPrLines:selectedLines,
            }); //ยิง event ให้ listener
        };
        
        // แสดง Modal
        ctrl.openModal = function(size,parentSelector){
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation:true,
                ariaLabelledBy:'modal-title',
                ariaDescribedBy:'modal-body',
                templateUrl:'prLinesModalContent.html',
                controller:function($uibModalInstance,$filter,uibDateParser){
                    var mctrl = this;
                    
                    mctrl.lines = [];

                    mctrl.selectedLines = [];

                    mctrl.sort = "asc";
                                        
                    mctrl.sortToggle = function(_order_by){
                        if(mctrl.sort=="asc"){
                            mctrl.sort="desc";
                        } else {
                            mctrl.sort="asc";
                        }
                        mctrl.loadData(_order_by);
                    }
                     
                    mctrl.loadData = function(_order_by=null){
                        var params = {
                            q: mctrl.modalSearch,
                            request_state:ctrl.requestState,
                            purchase_state:ctrl.purchaseState,
                            limit:null,
                            order_by:_order_by,
                            sort:mctrl.sort
                        };
                        console.log("refresh PR Lines",params);
                        var url = '/purchase/purchase-request/find-purchase-request-lines';
                        return $http.get(url, {params: params})
                                .then(function (response) {
                                    console.log(response);
                                    //parser date data
                                    var arr = response.data.lines;
                                    angular.forEach(arr,function(item,key){
                                        item.date_required = uibDateParser.parse(item.date_required, 'yyyy-MM-dd');
                                        // item.date_schedule = uibDateParser.parse(item.date_schedule, 'yyyy-MM-dd HH:mm:ss');
                                    });
                                    
                                    mctrl.lines = arr;
                                });
                    };

                    mctrl.onSelectLine = function(_line){
                        var contains = $filter('filter')(mctrl.selectedLines,{'id':_line.id});
                        if(contains.length<=0){
                            mctrl.selectedLines.push(_line);
                            _line.selected = true;
                        } else {
                            var index = mctrl.selectedLines.indexOf(_line);
                            mctrl.selectedLines.splice(index, 1);
                            _line.selected = false;
                        }
                        
                    }
        
                    mctrl.done = function(){
                        // ปิดและเลือก lines จะส่งไปยัง result.then ด้านล่าง
                        console.log('xx done',mctrl.selectedLines);
                        $uibModalInstance.close(mctrl.selectedLines);
                    }
                    
                    mctrl.cancel = function(){
                        $uibModalInstance.dismiss('cancel');
                        console.log("modal compoent");
                    }
                    
                    //
                    mctrl.$onInit = function(){
                        mctrl.loadData();
                    }
                    
                },
                controllerAs:'mctrl',
                size:'lg',
                appendTo:parentElem,
            });
            
            modalInstance.result.then(function (data) {
                ctrl.done(data); //คืนค่าให้ ctrl หลัก
                console.log('modal done',data);
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

        
    },
    bindings: {
        onSelect: '&', //return as array
        title:'@',
        requestState:'@', //state done,draft,approved
        purchaseState:'@'

    },
    controllerAs: 'ctrl'
});


/**
 * Partner Sale Area Select
 */
app.component('bicPartnerAreaSelect', {
    templateUrl: '/angular-templates/bic-partner-area-select.html',
    controller: function ($http, $uibModal, $log, $document) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.partnerArea = [];
       
        ctrl.onPartnerAreaClose = function (isOpen, sale_area_id) {
            console.log('partner area');
            if(isOpen) {
                ctrl.refreshPartnerArea(sale_area_id, null);
            }
        };

        ctrl.refreshPartnerArea = function (sale_area_id,partner) {
            console.log('refresh partner for sale area =' + sale_area_id);
            if(!sale_area_id) {
                return;
            }   
            var params = {q: partner, limit: 20, sale_area_id: sale_area_id};
            return $http.get('/sale/sale-area/partner-area-list-json', {params: params})
                .then(function (response) {
                    console.log(response.data);
                    ctrl.partnerArea = response.data;
                })
        };

        ctrl.select = function($partner){
            ctrl.onSelect({cLot:$partner});
        }

    },
    bindings: {
        bicModel: '=',
        dependPartnerId: '=',
        name: '@',
        required: '@',
        onSelect: '&'
    }

});

/**
 * Account Picking Type Select
 */
app.component('bicAccStockPickingTypeSelect', {
    templateUrl: '/angular-templates/bic-acc-stock-picking-type-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.pickingTypes = [];

        ctrl.refreshPickingTypes = function (q) {
            var params = {q:q};
            return $http.get('/account/account-stock-picking-type/picking-type-list-json', {params: params})
                    .then(function (response) {
                        console.log(response.data);
                        ctrl.pickingTypes = response.data;
                    });
        };

        ctrl.select = function(selected){
            ctrl.onSelect({cPickingType: selected});
        }
    },
    bindings: {
        bicModel: '=',
        name: '@',
        required: '@',
        onSelect: '&'
    }
});

/**
 * Account Stock Location
 * เลือก Location ทั้งหมด filter ตาม usages
 */
app.component('bicAccLocationSelect', {
    templateUrl: '/angular-templates/bic-acc-location-select.html',
    controller: function ($http) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.locations = [];
        ctrl.showView = false;
        ctrl.usages = "internal"; //default
        
        ctrl.onLocationOpenClose = function (isOpen, product_id) {
            console.log('location open');
            if (isOpen) {
                ctrl.refreshLocations(product_id, null);
            }
        };

        ctrl.refreshLocations = function (location) {
            var params = {q: location, limit: 10,usages:ctrl.usages};
            var url = '/account/account-stock-location/location-all-list-json';
            
            return $http.get(url, {params:params})
                    .then(function (response) {
                        ctrl.locations = response.data;
                    });
        };

        ctrl.select = function(location){
            console.log("SELECTED");
            ctrl.onSelect({cLocation: location});
        };
    },
    bindings: {
        bicModel: '=',
        usages:'@', //example internal,supplier
        name: '@',
        required: '@',
        onSelect: '&',
        showView:'=?' //optional
    }
});

/**
 *  Dialog เพื่อเลือก ลูกค้า ของแต่ละ sale 
 */
app.component('bicSoPartnerDialog', {
    templateUrl: '/angular-templates/bic-so-partner-dialog.html',
    controller: function ($http, $scope, $uibModal, $log, $document) {
        var ctrl = this;

        ctrl.title = "เลือกรายชื่อลูกค้า";
        ctrl.done = function (selectedLines) {
            console.log("done", selectedLines);

            ctrl.onSelect({
                cSoPartner:selectedLines,
            });
        };
       

        // แสดง Modal 
        ctrl.openModal = function (size, parentSelector) {
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation:true,
                ariaLabelledBy: 'modal-title',
                ariaDescribedBy: 'modal-body',
                templateUrl: 'soPartnerModalContent.html',
                controller: function ($uibModalInstance, $filter, uibDateParser) {
                    var mctrl = this;
                    mctrl.lines = [];
                    mctrl.selectedLines = [];
                    mctrl.sort = "asc";
                    mctrl.num = null;
                    mctrl.orderBy = null;

                    mctrl.totalItems = 0;
                    mctrl.currentPage = 1;
                    mctrl.itemsPerPage = 10;

                    mctrl.$onInit = function (){
                        mctrl.saleAreaId = ctrl.saleAreaId;
                        mctrl.loadData();
                    }

                    mctrl.sortToggle = function (_order_by) {
                        if(mctrl.sort == "asc") {
                            mctrl.sort = "desc";
                        } else {
                            mctrl.sort = "asc";
                        }
                        mctrl.loadData(_order_by);
                    }

                    mctrl.loadData = function (_order_by=null) {
                        mctrl.orderBy = _order_by;
                        
                        var params = {
                            q: mctrl.modalSearch,
                            sale_area_id:ctrl.saleAreaId,
                            limit:null,
                            order_by:_order_by,
                            sort:mctrl.sort,
                            itemsPerPage: mctrl.itemsPerPage,
                            currentPage: mctrl.currentPage
                        };
                        console.log("refresh so partner", params);
                        var url = '/sale/sale-report/find-sale-partner-json';
                        return $http.get(url, {params: params})
                            .then(function (response) {
                                console.log(response.data);

                                var arr = response.data.lines;
                                mctrl.lines = arr;
                                mctrl.totalItems = response.data.totalItems;
                            });
                    };

                    mctrl.onSelectLine = function (_line,index) {
                      
                       var contains = $filter('filter')(mctrl.selectedLines, {'id': _line.id});
                        if(contains.length <= 0) {
                            mctrl.selectedPartner = _line;
                            mctrl.num = index;
                        } 
                    }

                    // paging function 
                    mctrl.pageChanged = function (){
                        console.log("Page changed to", mctrl.currentPage);
                        mctrl.loadData(mctrl.order_by);
                    }

                    mctrl.done = function (){
                        // ปิดและเลือก lines จะส่งไปยัง result.then ด้านล่าง
                        console.log('xx done', mctrl.selectedPartner);
                        $uibModalInstance.close(mctrl.selectedPartner);
                    }

                    mctrl.cancel = function (){
                        $uibModalInstance.dismiss('cancel');
                        console.log("modal compoent");
                    }

                    mctrl.$onInit = function (){
                        mctrl.loadData();
                    }
                },
                controllerAs: 'mctrl',
                size: 'lg',
                appendTo:parentElem,
            });

            modalInstance.result.then(function (data) {
                ctrl.done(data); // คึนค่่าให้ ctrl หลัก
                console.log("modal done", data);
            }, function () {
                $log.info('Modal dismissed at' + new Date());
            });
        }
    },
    bindings: {
        onSelect: '&', //return as array 
        title: '@',
        saleAreaId:'='
    },
    controllerAs: 'ctrl'
});


/**
 *  Use Sale ระบุผู้ขาย
 */
app.component('bicUseSaleSelect', {
    templateUrl: '/angular-templates/bic-use-sale-select.html',
    controller: function ($http, $uibModal, $log, $document) {
        var ctrl = this;
        ctrl.required = false;
        ctrl.useSale = [];

        ctrl.onUseSaleClose = function (isOpen, sale_area_id) {
            console.log("use sale");
            if(isOpen) {
                ctrl.refreshUseSale(sale_area_id, null);
            }
        };

        ctrl.refreshUseSale = function (sale_area_id,q) {
            console.log("refresh use sale =" + sale_area_id);
           
            var params = {q: q, sale_area_id: sale_area_id};
            return $http.get("/sale/sale-order/use-sale-list-json", {params: params})
                .then(function (response) {
                    console.log(response.data);
                    ctrl.useSale = response.data;
                });
        };

        ctrl.select = function (selected) {
            ctrl.onSelect({cUseSale: selected});
        }
    },
    bindings: {
        bicModel: '=',
        dependUseSaleId: '=',
        name:'@',
        required: '@',
        onSelect: '&'
    }
});

/**
 *  เลือก Partner ใน sale order 
 */
app.component("bicReportSoPartnerDialog", {
    templateUrl: '/angular-templates/bic-report-so-partner-dialog.html',
    controller: function ($http, $scope, $uibModal, $log, $document) {
        var ctrl = this;

        ctrl.title = "เลือกรายชื่อลูกค้า";
        ctrl.$onInit = function (){

        }
        ctrl.done = function (selectedItems) {
            // ยืนยันการเลือก
            console.log("done");
            ctrl.onSelect({
                cPartner: selectedItems,
            }); // ยิง event ให้ listener
        };

        // แสดง MOdel
        ctrl.openModal = function (size, parentSelector) {
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title',
                ariaDescribedBy: 'modal-body',
                templateUrl: 'reportSalePartner.html',
                controller: function($uibModalInstance, $filter, $scope) {
                    var mctrl = this;
                    mctrl.partners = [];
                    mctrl.selectedItems = [];
                    mctrl.sort = "asc";
                    mctrl.orderBy = null;
                    
                    mctrl.totalItems = 0;
                    mctrl.currentPage = 1;
                    mctrl.itemsPerPage = 10;

                    mctrl.$onInit = function (){
                        mctrl.loadData();
                    }

                    mctrl.sortToggle = function (_order_by) {
                        if(mctrl.sort == "asc") {
                            mctrl.sort = "desc";
                        } else {
                            mctrl.sort = "asc";
                        }
                        mctrl.loadData(_order_by);
                    }

                    mctrl.loadData = function (_order_by = null) {
                        console.log("refresh Partner");
                        mctrl.selectedItems = []; // clear value
                        var params = {
                            q: mctrl.modalSearch,
                            limit: null,
                            order_by: _order_by,
                            sort: mctrl.sort,
                            itemsPerPage: mctrl.itemsPerPage,
                            currentPage: mctrl.currentPage
                        };
                        var url = '/resource/res-partner/find-partner-json';
                        console.log("bicReportSalePartner params", params);
                        return $http.get(url, {params: params})
                            .then(function (response) {
                                console.log(response);
                                mctrl.partners = response.data.lines;
                                mctrl.totalItems = response.data.totalItems;
                            });
                    };

                    mctrl.addSelect = function (p) {
                        if(p.selected == null) {
                            // add to selecteditems
                            mctrl.selectedItems.push(p);
                            p.selected = true;
                        } else {
                            // remove from selectedItems
                            var index = mctrl.selectedItems.indexOf(p);
                            mctrl.selectedItems.splice(index, 1);
                            p.selected = null;
                        }
                        console.log(mctrl.selectedItems);
                    };

                    // paging function
                    mctrl.pageChanged = function () {
                        console.log("page changed to", mctrl.currentPage);
                        mctrl.loadData(mctrl.order_by);
                    }

                    mctrl.done = function (){
                        // ปิดและเลือก partners จะส่งไปยัง result.then ด้านล่าง
                        $uibModalInstance.close(mctrl.selectedItems);
                    }

                    mctrl.cancel = function (){
                        $uibModalInstance.dismiss("cancel");
                        console.log("modal component");
                    }

                    //
                    mctrl.loadData();
                },
                controllerAs: 'mctrl',
                size: 'lg',
                appendTo:parentElem,
            });

            modalInstance.result.then(function(selectedItems) {
                ctrl.done(selectedItems); // คืนค่าให้ ctrl หลัก
                console.log({log: "modal result selected", msg: selectedItems});
            }, function () {
                $log.info("MOdal dismissed at:" + new Date());
            });
        };
    },
    bindings: {
        title: '@', // ชื่อ ปุ่ม
        onSelect: '&' // return array of partner 
    },
    controllerAs: 'ctrl'

});

/**
 * Dialog เพื่อเลือกรายการใน Good import 
 */
app.component('bicGoodsImportDialog', {
    templateUrl: '/angular-templates/bic-goods-import-dialog.html',
    controller: function ($http,$scope,$uibModal,$log,$document) {
        var ctrl = this;
        ctrl.title = 'เลือกจาก PO x';
        ctrl.viewMode = 'account'; //default account view
        ctrl.done = function(selectedLines){
            //ยืนยันการเลือก
            console.log("done",selectedLines);
            //console.log({log:"เลือก po",msg:ctrl.purchaseOrder});
            //console.log({log:"เลือก po_line",msg:ctrl.poLine});
            ctrl.onSelect({
                cDocs:selectedLines,
                
            }); //ยิง event ให้ listener
        };
        
        // แสดง Modal
        ctrl.openModal = function(size,parentSelector){
            var parentElem = parentSelector?
            angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation:true,
                ariaLabelledBy:'modal-title',
                ariaDescribedBy:'modal-body',
                templateUrl:'goodsImportModalContent.html',
                controller:function($uibModalInstance,$filter,uibDateParser){
                    var mctrl = this;
                    mctrl.viewMode = ctrl.viewMode;

                    mctrl.docs = [];

                    mctrl.selectedLines = [];

                    mctrl.sort = "asc";
                                        
                    mctrl.sortToggle = function(_order_by){
                        if(mctrl.sort=="asc"){
                            mctrl.sort="desc";
                        } else {
                            mctrl.sort="asc";
                        }
                        mctrl.loadData(_order_by);
                    }
                     
                    mctrl.loadData = function(_order_by=null){
                        console.log("refresh Docs");
                        var params = {
                            q: mctrl.modalSearch,
                            limit:null,
                            order_by:_order_by,
                            sort:mctrl.sort,
                            state_inv:ctrl.docStateInv
                        };
                        var url = '/stock/stock-goods-import/find-goods-import';
                        return $http.get(url, {params: params})
                                .then(function (response) {
                                    console.log(response);
                                    //parser date data
                                    var docs = response.data.docs;
                                    angular.forEach(docs,function(item,key){
                                        item.doc_date = uibDateParser.parse(item.doc_date, 'yyyy-MM-dd');
                                    });
                                    
                                    mctrl.docs = docs;
                                });
                    };


                    mctrl.onSelectLine = function(_line){
                        var contains = $filter('filter')(mctrl.selectedLines,{'id':_line.id});
                        if(contains.length<=0){
                            mctrl.selectedLines.push(_line);
                            _line.selected = true;
                        } else {
                            var index = mctrl.selectedLines.indexOf(_line);
                            mctrl.selectedLines.splice(index, 1);
                            _line.selected = false;
                        }
                        
                    }
        
                    mctrl.done = function(){
                        // ปิดและเลือก _po จะส่งไปยัง result.then ด้านล่าง
                        console.log('xx done',mctrl.selectedLines);
                        $uibModalInstance.close({lines:mctrl.selectedLines});
                    }
                    
                    mctrl.cancel = function(){
                        $uibModalInstance.dismiss('cancel');
                        console.log("modal compoent");
                    }
                    
                    //
                    mctrl.loadData();
                },
                controllerAs:'mctrl',
                size:'lg',
                appendTo:parentElem,
            });
            
            modalInstance.result.then(function (data) {
                ctrl.done(data.lines); //คืนค่าให้ ctrl หลัก
                console.log('modal done',data.lines);
            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        }

        
    },
    bindings: {
        onSelect: '&',
        title:'@',
        docStateInv:'@',
        viewMode:'@' //stock,account
    },
    controllerAs: 'ctrl'
});