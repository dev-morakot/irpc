
<form class="form-horizontal" name="form1" id="form1" enctype="multipart/form-data">
	<div class="row">
		<div class="col-lg-6">
      	<div class="col-lg-9 col-lg-offset-2 detailed">
            <h4 class="mb"><b class="glyphicon glyphicon-user"></b> 
                แก้ไขข้อมูล
            </h4>
            <input type="hidden" ng-model="model.id">
            <div class="form-horizontal">
            	<div class="form-group">
                	<label class="col-sm-3 col-sm-3 control-label"></label>
                	<div class="col-sm-9">
                		<img ng-src="../web/img_com/{{ model.img }}" style="width: 100px" class="img-rounded">
              		</div>
            	</div>
            </div>

            <div class="form-horizontal">
            	<div class="form-group">
                	<label class="col-sm-3 col-sm-3 control-label">รูปภาพประจำตัว</label>
                	<div class="col-sm-9">
                		<input type="file" name="file" file-model="model.file" class="form-control">
              		</div>
            	</div>
            </div>

            <div class="form-horizontal">
            	<div class="form-group">
                	<label class="col-sm-3 col-sm-3 control-label">รหัสพนักงาน</label>
                	<div class="col-sm-9">
                		<input type="text" ng-model="model.code" class="form-control">
              		</div>
            	</div>
            </div>

            <div class="form-horizontal">
            	<div class="form-group">
                	<label class="col-sm-3 col-sm-3 control-label">Username</label>
                	<div class="col-sm-9">
                		<input type="text" required ng-model="model.username" class="form-control">
              		</div>
            	</div>
            </div>

            <div class="form-horizontal">
            	<div class="form-group">
                	<label class="col-sm-3 col-sm-3 control-label">Email</label>
                	<div class="col-sm-9">
                		<input type="text" required ng-model="model.email" class="form-control" ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/">
              		</div>
            	</div>
            </div>

            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <span style="color:Red" ng-show="form1.email.$dirty&&form1.email.$error.pattern">กรุณาใส่อีเมล์ที่ถูกต้อง</span>
                    </div>
                </div>
            </div>

            <div class="form-horizontal">
            	<div class="form-group">
                	<label class="col-sm-3 col-sm-3 control-label">ชื่อ</label>
                	<div class="col-sm-9">
                		<input type="text" ng-model="model.firstname" class="form-control">
              		</div>
            	</div>
            </div>

            <div class="form-horizontal">
            	<div class="form-group">
                	<label class="col-sm-3 col-sm-3 control-label">นามสกุล</label>
                	<div class="col-sm-9">
                		<input type="text" ng-model="model.lastname" class="form-control">
              		</div>
            	</div>
            </div>

			<div class="form-horizontal">
            	<div class="form-group">
                	<label class="col-sm-3 col-sm-3 control-label">เบอร์โทรศัพท์</label>
                	<div class="col-sm-9">
                		<input type="text" ng-model="model.tel" class="form-control">
              		</div>
            	</div>
            </div>

            <div class="form-horizontal">
            	<div class="form-group">
                	<label class="col-sm-3 col-sm-3 control-label">เข้าใช้ระบบ</label>
                	<div class="col-sm-9">
                		<input type="radio" ng-model="model.active" value="1"> อนุญาติ
                		&nbsp;&nbsp;
                		<input type="radio" ng-model="model.active" value="0"> ไม่อนุญาติ
              		</div>
            	</div>
            </div>

			</div>

			

        </div>
		<div class="col-lg-6">
				<table class="table table-striped" style="margin-top: 10px">
					<thead>
						<tr>
							<th width="40px">เลือก</th>
							<th>Modals</th>
							
							<th>Rule</th>
						</tr>
					</thead>
					<tbody>
						<tr>

							<td><input type="checkbox" ng-checked="model.select_admin === '1'" ng-model="model.select_admin" ng-true-value="1" ng-false-value="0"></td>
                            
							<td> การจัดการระบบ </td>
							<td>
								<select class="form-control" ng-model="model.rule_admin">
									
									<option value="admin"> ผู้ดูแลระบบ </option>
								</select>
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" ng-checked="model.select_help === '1'" ng-model="model.select_help" ng-true-value="1" ng-false-value="0"></td>
							<td> ระบบแจ้งซ่อมคอมพิวเตอร์ </td>
							<td>
								<select class="form-control" ng-model="model.rule_help">
									
									<option value="admin"> ผู้ดูแลระบบ </option>
									<option value="user"> ผู้ใช้งาน </option>
									<option value="it"> เจ้าหน้าที่สารสนเทศ</option>
									<option value="builder">ช่างซ่อมอาคาร</option>
									<option value="officer">เจ้าหน้าที่ธุรการ</option>
								</select>
							</td>
						</tr>
						<tr>
							<td><input type="checkbox" ng-checked="model.select_asset === '1'" ng-model="model.select_asset" ng-true-value="1" ng-false-value="0"></td>
							<td> ระบบเบิกพัสดุ-ครุภัณฑ์ </td>
							<td>
								<select class="form-control" ng-model="model.rule_asset">
									
									<option value="admin"> ผู้ดูแลระบบ </option>
									<option value="user"> ผู้ใช้งาน </option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			
			</div>
	</div>
</form>


