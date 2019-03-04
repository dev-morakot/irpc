<div class="modal fade" id="myRepair" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">รายการที่อยู่ระหว่างการซ่อม</h4>
      </div>
      <div class="modal-body">
      
        <table class="table table-striped" width="100%" style='padding: 8px'>
	
		<tr>
			<td width='15%' style='padding: 10px'> <strong> รับซ่อม </strong> </td>
			<td width='85%' style='padding: 10px'>  เจ้าหน้าที่ฝ่ายสารสนเทศ : <?= ($model->repair)?$model->repair->firstname. "  " . $model->repair->lastname:"-"; ?></td>
		</tr>
		<tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			
			<input type="radio" ng-model="model.state" value="close" /> ปิดงานซ่อม
			
		</td>
	</tr>
	<tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			<div class="form-inline">
				<label style="width: 200px"> ปัญหา/สาเหตุ</label>
				<input type="text" ng-model="model.answer" value="" class="form-control" style="width: 500px" />
			</div>
		</td>
	</tr>
	<tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			<div class="form-inline">
				<label style="width: 200px"> รายละเอียดการแก้ปัญหา</label>
				<input type="text" ng-model="model.detail_work" value="" class="form-control" style="width: 500px" />
			</div>
		</td>
	</tr>

	<tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			<div class="form-inline">
				<label style="width: 200px"> รายละเอียดบริการซ่อมอาคาร</label>
				<input type="text" ng-model="model.detail_building" value="" class="form-control" style="width: 500px" />
			</div>
		</td>
	</tr>

	<tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			<div class="form-inline">
				<label style="width: 200px"> รายละเอียดบริการงานธุรการ</label>
				<input type="text" ng-model="model.detail_officer" value="" class="form-control" style="width: 500px" />
			</div>
		</td>
	</tr>
</table>

<hr />

<table class="table table-striped" width="100%" style='padding: 8px'>
	
		<tr>
			<td width='15%' style='padding: 10px'> <strong> ส่งซ่อม/จัดซื้ออุปกรณ์ </strong> </td>
			<td width='85%' style='padding: 10px'>  เจ้าหน้าที่ฝ่ายสารสนเทศ : <?= ($model->repair)?$model->repair->firstname. "  " . $model->repair->lastname:"-"; ?></td>
		</tr>
		<tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			
			<input type="radio"  ng-model="model.state" value="clame" />  ส่งซ่อม / ส่งเคลม
			&nbsp;&nbsp;&nbsp;
			<input type="radio" ng-model="model.state" value="buy" /> จัดซื้ออุปกรณ์
		</td>
	</tr>
	<tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			<div class="form-inline">
				<label style="width: 130px"> โดยใช้งบ</label>
				<input type="text" ng-model="model.budget" value="" class="form-control" style="width: 500px" />
			</div>
		</td>
	</tr>
	
</table>


<hr />

<table class="table table-striped" width="100%" style='padding: 8px'>
	
		<tr>
			<td width='15%' style='padding: 10px'> <strong> ยกเลิกการซ่อม </strong> </td>
			<td width='85%' style='padding: 10px'>  เจ้าหน้าที่ฝ่ายสารสนเทศ : <?= ($model->repair)?$model->repair->firstname. "  " . $model->repair->lastname:"-"; ?></td>
		</tr>
		<tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			
			<input type="radio" ng-model="model.state" value="cancel" /> ยกเลิกการซ่อม
			
		</td>
	</tr>
	<tr>
		<td style="width: 10%"></td>
		<td style="width: 90%;padding: 10px">
			<div class="form-inline">
				<label style="width: 170px"> สาเหตุยกเลิกการซ่อม </label>
				<input type="text" ng-model="model.note_cancel" class="form-control" style="width: 500px" />
			</div>
		</td>
	</tr>
	
</table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
        <button type="button" class="btn btn-primary" ng-click="saveAll()" data-dismiss="model">บันทึก</button>
      </div>
    </div>
  </div>
</div>