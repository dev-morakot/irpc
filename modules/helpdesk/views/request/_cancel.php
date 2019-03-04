<div class="modal fade" id="cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ยกเลิกการซ่อม</h4>
      </div>
      <div class="modal-body">
      
       
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
        <button type="button" class="btn btn-primary" ng-click="saveCancel()" data-dismiss="model">บันทึก</button>
      </div>
    </div>
  </div>
</div>