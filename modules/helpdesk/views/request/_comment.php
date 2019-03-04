<div class="modal fade" id="comment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ประเมินผลโดยผู้ขอใช้บริการ</h4>
      </div>
      <div class="modal-body">

        
				
			
				<div class="alert alert-warning" style='padding: 8px;'>
					ได้รับเครื่องคอมพิวเตอร์ หรืออุปกรณ์ที่ส่งซ่อมเรียบร้อยแล้ว
				</div>
				<div class="form-inline">
					<div class="form-group">
						<input type='radio' ng-model="model.comment_state" value="improve"  /> ต้องปรับปรุง &nbsp;&nbsp;
						<input type='radio' ng-model="model.comment_state" value="fair"  /> พอใช้ &nbsp;&nbsp;
						<input type='radio' ng-model="model.comment_state" value="medium" /> ปานกลาง &nbsp;&nbsp;
						<input type='radio' ng-model="model.comment_state" value="good" /> ดี &nbsp;&nbsp;
						<input type='radio' ng-model="model.comment_state" value="verygood" /> ดีมาก 
					</div>
				</div>
				<br />
				<div class="form-inline">
					<div class="form-group">
					
						<textarea placeholder='ข้อเสนอแนะ' class="form-control" ng-model="model.comment_detail" cols='50' rows='5'></textarea>
					</div>
				</div>
			

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
        <button type="button" class="btn btn-primary" ng-click="saveAsComment()" data-dismiss="model">บันทึก</button>
      </div>
    </div>
  </div>
</div>