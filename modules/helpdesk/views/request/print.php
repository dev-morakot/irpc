<?php 
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', true);
use app\assets\AppAsset;
AppAsset::register($this);

require_once '../pdf-watermark/autoload.php';
//include_once 'js/pdf-watermark/setasign/fpdi/pdf_parser.php';

try {

	

if(@$model->state == 'endjob'){
	@$date = Yii::$app->thaiFormatter->asDate($model->date_end_job,'php:d/m/Y');
	@$txt = $model->detail_work;
} elseif((@$model->state == "clame") OR ($model->state == "buy")){
	@$date = Yii::$app->thaiFormatter->asDate($model->date_clame,'php:d/m/Y');
	@$txt = $model->clame_detail;
} else if(@$model->state=='repair') {
	@$date = Yii::$app->thaiFormatter->asDate($model->date_repair, 'php:d/m/Y');
	@$txt = '';
}

if($model->state == "endjob"){
	$close = "<img src='../web/img_com/checkbox.png'>";
} else {
	$close = "<img src='../web/img_com/non.png'>";
}

if($model->comment_state == "improve"){
	$improve = "<img src='../web/img_com/checkbox.png'>";
} else {
	$improve = "<img src='../web/img_com/non.png'>";
}

if($model->comment_state == "fair"){
	$fair = "<img src='../web/img_com/checkbox.png'>";
} else {
	$fair = "<img src='../web/img_com/non.png'>";
}

if($model->comment_state == "medium"){
	$medium = "<img src='../web/img_com/checkbox.png'>";
} else {
	$medium = "<img src='../web/img_com/non.png'>";
}

if($model->comment_state == "good"){
	$good = "<img src='../web/img_com/checkbox.png'>";
} else {
	$good = "<img src='../web/img_com/non.png'>";
}

if($model->comment_state == "verygood"){
	$verygood = "<img src='../web/img_com/checkbox.png'>";
} else {
	$verygood = "<img src='../web/img_com/non.png'>";
}



if(($model->state == "clame") OR ($model->state == "buy")){
	$clame_buy = "<img src='../web/img_com/checkbox.png'>";
} else {
	$clame_buy = "<img src='../web/img_com/non.png'>";
}


if($model->state == "clame"){
	$clame = "<img src='../web/img_com/checkbox.png'>";
} else {
	$clame = "<img src='../web/img_com/non.png'>";
}

if($model->state == "buy"){
	$buy = "<img src='../web/img_com/checkbox.png'>";
} else {
	$buy = "<img src='../web/img_com/non.png'>";
}


if($model->problem == "computer"){
	$com = "<img src='../web/img_com/rdo2.png'>";
} else {
	$com = "<img src='../web/img_com/rdo3.png'>";
}


if($model->problem == "printer"){
	$printer = "<img src='../web/img_com/rdo2.png'>";
} else {
	$printer = "<img src='../web/img_com/rdo3.png'>";
}

if($model->problem == "network"){
	$network = "<img src='../web/img_com/rdo2.png'>";
} else {
	$network = "<img src='../web/img_com/rdo3.png'>";
}

if($model->problem == "other"){
	$other = "<img src='../web/img_com/rdo2.png'>";
} else {
	$other = "<img src='../web/img_com/rdo3.png'>";
}


$tel = ($model->user)?$model->user->tel:"";
$requested_by = isset($model->user)?$model->user->firstname. "   " . $model->user->lastname: "";
$repair = isset($model->repair)?$model->repair->firstname. "   " . $model->repair->lastname: "";
$date_comment = Yii::$app->thaiFormatter->asDate($model->date_comment,'php:d/m/Y');
$date_close = Yii::$app->thaiFormatter->asDate($model->date_close , 'php:d/m/Y');
$budget = $model->budget;
$html = "
	<html>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		<head>

		</head>
		<body>
	<table border='0' width='100%'> 
		<tr>
			<td colspan='2' style='font-size: 13px;font-weight: bold;padding: 10px;'>
				<table border='0' style='width: 100%'>
					<tr>
						<td align='right' style='width: 30%'>
							<img src='../web/img_com/irpct.jpg'/>
						</td>
						<td align='center' style='width: 80%;padding-right: 12%'>
							<strong> แผนก​พัฒนา​ระบบ​สาร​สน​เทศ​ วิทยาลัย​เทคโนโลยี​ไอ​อา​รพี​ซี <br />
							แบบ​ฟอรม​ใบ​แจง​ซอม​ / แกไข​ปัญหา​เครือ​ขาย</strong>
						</td>
					</tr>
				</table>
			</td>			
		<tr>
	</table>
	
	<table border='0' width='100%' >
		<tr>
			<td style='padding: 10px'>
			<p style='font-size: 10px;'> หัวข้อการแจ้งปัญหา &nbsp;&nbsp;&nbsp; $com &nbsp; ปัญหาคอมพิวเตอร์
			&nbsp;&nbsp;&nbsp; $network &nbsp; ปัญหาเครือข่าย &nbsp;&nbsp;&nbsp; $printer &nbsp; ปัญหาปริ้นเตอร์
			&nbsp;&nbsp;&nbsp; $other &nbsp; อื่นๆ (ระบุ) &nbsp;&nbsp;&nbsp; $model->other </p>
		</tr>
	</table>
	
	<hr>
	<table border='0' width='100%'>
		<tr>
			<td style='width: 10%;padding: 8px'> <p style='font-size: 10px;font-weight: bold'> เรียน </p> </td>
			<td style='width: 90%;padding: 8px'>
				<p style='font-size: 10px'> หัวหน้าแผนกพัฒนาระบบสารสนเทศ </p>
			</td>
		</tr>
		<tr>
			<td style='width: 10%;padding: 8px'></td>
			<td style='width: 90%;padding: 8px'>
				<p style='font-size: 10px'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ด้วยหน่วยงาน 
				
					&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; วิทยาลัย​เทคโนโลยี​ไอ​อา​รพี​ซี​ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				มีความประสงค์ใคร่ขอความอนุเคราะห์แผนกพัฒนาระบบสารสนเทศดำเนินการ  </p> 
			</td>
		</tr>
		
		<tr>
			<td style='width: 10%;padding: 8px'></td>
			<td style='width: 90%;padding: 8px'>
				<p style='font-size: 10px'>   ตรวจสอบเบื้องต้น (ระบุสาเหตุ/ปัญหาที่พบ)
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $model->description
				 </p>
			</td>
		</tr>
		
	</table>
	
	<table border='0' width='100%'>
		<tr>
			<td style='width: 48%;padding: 10px;font-size: 11px'>
		 		<p style='font-size: 11px'><strong> รายละเอียดของอุปกรณ์มีดังนี้ </strong> </p> <br />
		 		<p style='font-size: 11px'><strong> หมายเลขครุภัณฑ์ </strong> &nbsp;&nbsp;&nbsp; &nbsp; $model->sn_number
		 		 </p> <br />
		 		<p style='font-size: 11px'><strong> ยี่ห้อ </strong> &nbsp;&nbsp;&nbsp;  $model->brand &nbsp;&nbsp;&nbsp; <strong> Password </strong>..........................................</p> <br />
		 		<p style='font-size: 11px'><strong> เบอร์ติดต่อกลับ </strong>   &nbsp;&nbsp;&nbsp; $tel </p><br />
		 		<p style='font-size: 11px'><strong> จึงเรียนมาเพื่อทราบและพิจารณาดำเนินการต่อไป </strong></p> <br />

		 		<table border='0' width='100%' style='margin-top: 1%'>
		 			<tr>
		 				<td style='width:50%;text-align: center;padding: 10px;font-size: 11'>
		 					<p style='text-align: center;font-size: 11px'> (ลงชื่อ).......................................................... </p> <br/>
		 					<p style='text-align: center;font-size: 11px'> (&nbsp;&nbsp;&nbsp; $requested_by &nbsp;&nbsp;&nbsp;) </p><br />
		 					<p style='text-align: center;font-size: 11px'> (ผู้ส่งซ่อม) </p>
		 				</td>
		 			</tr>
		 		</table>
			</td>
			<td style='width: 3%;padding-right: 10px;text-align: left'><img src='../web/img_com/vertical_1.jpg'></td>
			<td style='width: 48%;padding: 10px;font-size: 11px'>
				<p style='font-size: 11px'><strong> หมายเหตุ </strong></p> <br />
		 		<p style='font-size: 11px'>1. กรุณาสำรองข้อมูลก่อนนำเครื่องส่ง กรณีข้อมูลสูญหาย  แผนกฯ จะไม่รับผิดชอบ </p> <br />
		 		<p style='font-size: 11px'>2. แผนกฯ จะติดตั้งเฉพาะโปรแกรมที่ถูกลิขสิทธิ์เท่านั้น</p> <br />
		 			<br />
		 			<br />
		 			<br />
		 		<table border='0' width='100%' style='margin-top: 1%'>
		 			<tr>
		 				<td style='width:50%;text-align: center;padding: 10px;font-size: 11'>
		 					<p style='text-align: center;font-size: 11px'> (ลงชื่อ)......................................................... </p> <br/>
		 					<p style='text-align: center;font-size: 11px'> (.............................................................) </p><br />
		 					<p style='text-align: center;font-size: 11px'> (หัวหน้า) </p>
		 				</td>
		 			</tr>
		 		</table>
			</td>
		</tr>
		<tr>
			<td colspan='3' style='padding: 10px;font-size: 11px'>
			<strong><u>หมายเหตุ</u></strong> &nbsp; หน่วยงานใดที่มีเจ้าหน้าที่/นักวิชาการคอมพิวเตอร์ประจำหน่วยงานสังกัด 
			<u>ใคร่ขอความกรุณา</u> ให้ใช้บริการจากเจ้าหน้าที่หน่วยงานสังกัดของท่าน และขอความร่วมมือนำเครื่องมาส่ง และรับที่
			แผนก​พัฒนา​ระบบ​สาร​สน​เทศ​ อาคาร 5 ชั้น 4 ห้อง 5414 <br />
			-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- <br />
			<p style='font-size: 11px;font-weight:bold'> (เฉพราะแผนกพัฒนาระบบสารสนเทศ) </p>
			</td>
		</tr>
	</table>

	<table border='0' width='100%'>
		<tr>
			<td style='width: 46%;padding: 10px;'>
		 		<p style='font-size: 10px'><strong><u> 1. มอบหมาย </u></strong> </p> <br />
		 		<p style='font-size: 10px'><strong> มอบ </strong>.........................................................................</p> <br />
		 		
		 		

		 		<table border='0' width='100%' style='margin-top: 1%'>
		 			<tr>
		 				<td style='width:50%;text-align: center;padding: 10px;'>
		 					<p style='text-align: center;font-size: 10px'> (ลงชื่อ)...............................................................(หัวหน้าแผนก) </p> <br/>
		 					<p style='text-align: center;font-size: 10px'> (..................................................) </p><br />
		 					<p style='text-align: center;font-size: 10px'> ...../........./......... </p>
		 				</td>
		 			</tr>
		 		</table>
			</td>
			<td style='width: 3%;text-align: left'><img src='../web/img_com/vertical.jpg'></td>
			<td style='width: 55%;padding: 10px;font-size: 10px;margin-bottom: 5%'>
				<p style='font-size: 10px'><strong><u> 2. ผลการดำเนินการ </u></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; วันที่ $date </p> <br />
		 		<p style='font-size: 10px'> $close เสร็จเรียบร้อย &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $clame_buy ส่งซ่อม / จัดซื้ออุปกรณ์ </p> <br />
		 		<p style='font-size: 10px'><strong> โดยได้ดำเนินการดังนี้ </strong>  $txt</p> <br />
		 		
		 			<br />
		 			
		 			
		 		<table border='0' width='100%'>
		 			<tr>
		 				<td style='width:50%;text-align: center;padding: 10px;font-size: 10px'>
		 					<p style='text-align: center;font-size: 10px'> (ลงชื่อ).........................................................(ผู้ดำเนินการ) </p> <br/>
		 					<p style='text-align: center;font-size: 10px'> (&nbsp;&nbsp;&nbsp; $repair &nbsp;&nbsp;&nbsp;) </p><br />
		 					<p style='text-align: center;font-size: 10px'> $date_close </p>
		 				</td>
		 			</tr>
		 		</table>
			</td>
		</tr>
	</table>

	<table border='0' width='100%'>
		<tr>
			<td style='width: 40%;padding: 10px;font-size: 11px'>
				<p style='font-size: 11px'><strong><u> 3.อนุมัติส่งซ่อม/จัดซื้ออุปกรณ์ </u> </strong> </p> <br />
		 		<p style='font-size: 11px'> เรียนผู้อำนวยการ </p> <br />

		 		<p style='font-size: 11px'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; เนื่องด้วยแผนกพัฒนาระบบสารสนเทศได้ดำเนินตรวจสอบ จำเป็นต้องดำเนินการ &nbsp;&nbsp; $clame&nbsp; ส่งซ่อม &nbsp;&nbsp; $buy &nbsp; จัดซื้ออุปกรณ์ </p> <br />
		 		

		 		<p style='font-size: 11px'>โดยใช้งบ &nbsp;&nbsp;&nbsp; $budget </p><br />
				<p style='font-size: 11px'>จึงเรียนมาเพื่อโปรดอนุมัติ</p>

				<p style='font-size: 11px'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ............................................. หน.พัฒนาระบบสารสนเทศ</p> <br />
				<p style='font-size: 11px'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;............................................. รองฯเทคโนโลยีสารสนเทศ</p>



				<p style='font-size: 11px'><img src='../web/img_com/non.png'> อนุมัติ  <img src='../web/img_com/non.png'> ไม่อนุมัติ...........................................................</p>
		 		<table border='0' width='100%'>
		 			<tr>
		 				<td style='width:50%;text-align: center;padding: 10px;font-size: 11'>
		 					<p style='text-align: center;font-size: 11px'> (ลงชื่อ).........................................................(ผู้อำนวยการ) </p> <br/>
		 					<p style='text-align: center;font-size: 11px'> (......................................................) </p><br />
		 					<p style='text-align: center;font-size: 11px'> ........./.........../........... </p>
		 				</td>
		 			</tr>
		 		</table>
		 		
			</td>
			<td style='width: 6%;text-align: center'><img src='../web/img_com/vertical_1.jpg'></td>
			<td style='width: 55%;padding: 10px'>
				<p style='font-size: 11px'><strong><u> 4. ประเมินผลโดยผู้ขอใช้บริการ </u> </strong> </p> <br />
		 		<p style='font-size: 11px'> ได้รับเครื่องคอมพิวเตอร์ หรือ อุปกรณ์ที่ส่งซ่อมเรียบร้อยแล้ว </p> <br />

		 		<p style='font-size: 11px'>
		 		 $improve &nbsp; ต้องปรับปรุง &nbsp;
		 		 $fair &nbsp; พอใช้ &nbsp;
		 		 $medium &nbsp; ปานกลาง &nbsp;
		 		 $good &nbsp; ดี &nbsp;
		 		 $verygood &nbsp; ดีมาก  &nbsp;
		 		</p> <br />

		 		<p style='font-size: 11px'>ข้อเสนอมแนะ &nbsp; $model->comment_detail </p><br />
			
		 		<table border='0' width='100%'>
		 			<tr>
		 				<td style='width:50%;text-align: center;padding: 10px;font-size: 11'>
		 					<p style='text-align: center;font-size: 11px'> (ลงชื่อ).........................................................(ผู้รับ) </p> <br/>
		 					<p style='text-align: center;font-size: 11px'> (&nbsp;&nbsp;&nbsp; $requested_by  &nbsp;&nbsp;&nbsp;) </p><br />
		 					<p style='text-align: center;font-size: 11px'> $date_comment </p>
		 				</td>
		 			</tr>
		 		</table>
			</td>
		</tr>
	</table>

	</body>
</html>
";

    $mpdf = new \Mpdf\Mpdf([
        'debug' => true,
        'default_font'=> 'THSarabun',
        'mode' => 'tha',
        'allow_output_buffering' => true,
       
    ]);
   	$mpdf->WriteHTML($html);
    $mpdf->allow_charset_conversion=true;  // Set by default to TRUE 
    ob_end_clean(); // คำสั่งในการ clean ค่าใน buffer
    $mpdf->Output();
    exit;

} catch (\Mpdf\MpdfException $e) {
    throw new CHttpException(404,$e->getMessage());
}