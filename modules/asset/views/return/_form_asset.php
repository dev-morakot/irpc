




    <!-- Modal -->
<div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ใบคืนพัสดุ - ครุภัณฑ์</h4>
      </div>
      <div class="modal-body">
            <h4>
    <b class="glyphicon glyphicon-user"></b> ผู้ขอคืนพัสดุ - ครุภัณฑ์
</h4>
<form novalidate class="form-horizontal" name="form1">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group form-group-sm">
    				<label class="control-label col-sm-3" for="purchaseorder-name"> ผู้ขอคืน</label>
                    <div class="col-sm-9">
                        <input type="text" ng-model="model.full_name" class="form-control">       
                    </div>
                </div>

                <div class="form-group form-group-sm">
    				<label class="control-label col-sm-3" for="purchaseorder-name"> ฝ่าย/แผนก</label>
                    <div class="col-sm-9">
                        <ui-select ng-model="model.group_id" 
                            reset-search-input="true"
                            style="min-width: 300px;" theme="bootstrap" title="เลือกแผนก" uis-open-close="onGroupOpenClose(isOpen)">
                            <ui-select-match allow-clear='true' placeholder="ค้นหาชื่อแผนก">{{$select.selected.department}}</ui-select-match>
                            <ui-select-choices group-by="'name'" 
                            repeat="person in groups" 
                            refresh-on-active='true'
                            refresh-delay="0"
                            refresh="refreshGroup($select.search)">
                                <div ng-bind-html="person.department | highlight: $select.search"></div>
                            </ui-select-choices>
                        </ui-select>
                    </div>
                </div>

                <div class="form-group form-group-sm">
    				<label class="control-label col-sm-3" for="purchaseorder-name"> สถานที่ห้อง</label>
                    <div class="col-sm-9">
                       <ui-select ng-model="model.location_id" 
                            reset-search-input="true"
                            style="min-width: 300px;" theme="bootstrap" title="เลือกสถานที่/ห้อง" uis-open-close="onLocationOpenClose(isOpen)">
                            <ui-select-match allow-clear='true' placeholder="ค้นหาชื่อสถานที่/ห้อง">{{$select.selected.name}}</ui-select-match>
                            <ui-select-choices
                            repeat="locl in locations" 
                            refresh-on-active='true'
                            refresh-delay="0"
                            refresh="refreshLocation($select.search)">
                                <div ng-bind-html="locl.name | highlight: $select.search"></div>
                            </ui-select-choices>
                        </ui-select>
                    </div>
                </div>

                <div class="form-group form-group-sm">
    				<label class="control-label col-sm-3" for="purchaseorder-name"> รายละเอียด</label>
                    <div class="col-sm-9">
                        <textarea cols="30" rows="5" ng-model="model.notes" class="form-control"></textarea>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>

<hr />
<h4>
    <b class="glyphicon glyphicon-shopping-cart"></b> สินทรัพย์ที่ขอคืน
</h4>

<div class="table-responsive">
    <table class="table table-striped table-bordered" style="margin-top: 15px">
        <thead>
            <tr>
                <th>#</th>
                <th>หมายเลขครุภัณฑ์</th>
                <th>รายการ</th>
                <th>จำนวน</th>
                <th>หน่วย</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="line in return_cart track by $index">
                <td>{{ $index + 1 }}</td>
                <td>{{ line.name }}</td>
                <td>{{ line.description }}</td>
                <td align="right"> {{ line.new_qty | number}} </td>
                <td align="right"> {{line.unit.name }}
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">รวม</th>
                <th style="text-align: right"> {{ return_cart | sumOfValue:'new_qty'}} </th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

          
    </div>
      <div class="modal-footer">
       
        <div class="pull-right">
            <button type="button" ng-click="saveReturnCart()" class="btn btn-primary">ขออนุมัติใบคืน</button>
        </div>
        <div class="clearfix"></div>
        
      </div>
    </div>
  </div>
</div>