<div class="modal fade" id="close" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ปิดงานซ่อม</h4>
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
				<textarea ng-model="model.detail_work"  class="form-control" rows="3" cols="50" />{{ model.detail_work }}</textarea>
			</div>
		</td>
	</tr>
</table>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
        <button type="button" class="btn btn-primary" ng-click="saveClose()" data-dismiss="model">บันทึก</button>
      </div>
    </div>
  </div>
</div>