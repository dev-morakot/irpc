    <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ใบเบิกพัสดุ - ครุภัณฑ์</h4>
      </div>
      <div class="modal-body">
            
            <h4><b class="glyphicon glyphicon-plus"></b> ใบเบิกพัสดุ - ครุภัณฑ์ </h4>
<table class="table table-bordred table-striped" style="margin-top: 15px">
                <thead>
                    <tr>
                       
                        <th width="auto" style="text-align: center">	หมายเลขครุภัณฑ์</th>
                        <th width="auto" style="text-align: center">รายการ</th>
                        <th width="auto" style="text-align: center">ประเภททรัพย์สิน</th>
                        <th width="auto" style="text-align: center">จำนวนคงเหลือ	</th>
                        <th width="100px" style="text-align: center">จำนวน	</th>
                        <th width="auto" style="text-align: center">หน่วย	</th>
                        <th width="auto" style="text-align: center">	</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ modline.name }}</td>
                        <td>{{ modline.description }}</td>
                        <td>{{ modline.category.name }}</td>
                        <td align="right">{{ modline.qty | number }}</td>
                        <td align="right">
                            <input type="text" class="form-control" style="text-align: right" ng-model="modline.new_qty" />
                            <span style="color: red">{{ msg }}</span>    
                        </td>
                        <td align="right">{{ modline.unit.name }}</td>
                        <td align="center">
                            <button type="button" class="btn btn-primary btn-sm" ng-click="addItem(modline)">
                            <b class="glyphicon glyphicon-plus"></b> เพิ่ม
                            </button>
                        </td>
                    </tr>
                </tbody>
           </table>

           <hr />

           <h4><b class="glyphicon glyphicon-shopping-cart"></b> เพิ่มใบเบิกพัสดุ - ครุภัณฑ์ </h4>

           <table class="table table-bordred table-striped" style="margin-top: 15px">
                <thead>
                    <tr>
                       <th width="auto" style="text-align: center">#</th>
                        <th width="auto" style="text-align: center">	หมายเลขครุภัณฑ์</th>
                        <th width="auto" style="text-align: center">รายการ</th>
                        <th width="auto" style="text-align: center">ประเภททรัพย์สิน</th>
                        <th width="auto" style="text-align: center">จำนวน</th>
                        <th width="auto" style="text-align: center">หน่วย	</th>
                        <th width="auto" style="text-align: center">	</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="line in cart track by $index">
                       <td>{{ $index + 1 }}</td>
                       <td>{{ line.name }}</td>
                       <td>{{ line.description }}</td>
                       <td>{{ line.category.name }}</td>
                       <td align="right"> {{ line.new_qty | number}} </td>
                       <td align="right"> {{line.unit.name }}
                       <td align="center">
                            <button class="btn btn-danger btn-sm" ng-click="DelItem(line)"> <b class="glyphicon glyphicon-remove"></b> </button>
                       </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan='4'> รวม </th>
                        <th style="text-align: right"> {{ cart | sumOfValue:'new_qty'}} </th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
           </table>


        </div>
      <div class="modal-footer">
       
        <div class="pull-right">
            <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#form">ต่อไป
                <b class="glyphicon glyphicon-forward"></b>
            </button>
        </div>
        <div class="clearfix"></div>
        
      </div>
    </div>
  </div>
</div>