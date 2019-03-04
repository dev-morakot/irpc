<?php 
	class Util {

		public static function monthRange(){
			$monthRange = array(
				'1' => 'มกราคม',
            '2' => 'กุมภาพันธ์',
            '3' => 'มีนาคม',
            '4' => 'เมษายน',
            '5' => 'พฤษภาคม',
            '6' => 'มิถุนายน',
            '7' => 'กรกฏาคม',
            '8' => 'สิงหาคม',
            '9' => 'กันยายน',
            '10' => 'ตุลาคม',
            '11' => 'พฤศจิกายน',
            '12' => 'ธันวาคม',

			);

			return $monthRange;
		}

		public static function DateThai($strDate){
			$strYear = date("Y", strtotime($strDate)) + 543;
			$strMonth = date("n", strtotime($strDate));
			$strDay = date("j", strtotime($strDate));
			$strMonthCut = Util::monthRange();
			$strMonthThai = $strMonthCut[$strMonth];

			return "$strDay  $strMonthThai  $strYear";
		}
	}
?>
<?php 
error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', true);
use app\assets\AppAsset;
AppAsset::register($this);

require_once '../pdf-watermark/autoload.php';
$date = Util::DateThai($print->date_approve);
$full_name = $print->full_name;

$detail = $print->notes;
$department = $print->group->department;
$group = $print->group->name;
$create_at = Util::DateThai($print->date_order);
$employee_id = $print->create->firstname ."    " . $print->create->lastname;

$manager = $print->approve->firstname. "     " . $print->approve->lastname;

$html = "
		<style>
			* {
				font-size: 10px;
			}
			.cell-header {
				text-align: center;
				font-weight: bold;
                                font-size: 16px;
				border-bottom: #808080 3px double;
			}
            .cell-span{
                text-align: right;
                color: #000000;
                font-size: 10px;

            }
			.cell {
				padding: 5px;
                font-size: 15;
				border-bottom: #cccccc 1px solid;
			}
            .cells {
                padding: 5px;
                font-size: 15px;
            }

            .cellfooter{
                font-size: 15;
                padding: 5px;
            }

            .celltd {
                padding: 5px;
                font-size: 10;
                border-bottom: #cccccc 1px solid;
            }
                        
			.footer {
				border-bottom: #cccccc 3px double;
                font-size: 15;
				padding: 5px;
			}

            
		</style>
	<table width='100%' border='0'>

		<tr>
             
			<td valign='top' width='100px'>
				<div class='header-text bold'>
	      			<img style='margin: 2px; vertical-align: middle;' alt='004' src='../web/img_com/irpct.jpg' />
          		</td>
            <td valign='middle'>            
           		<span class='header-text' style='font-size: 10px'>
             		วิทยาลัยเทคโนโลยีไออาร์พีซี
             		<hr /> <br />309 หมู่ 5 ถนนสุขุมวิท ตำบลเชิงเนิน อำเภอเมือง จังหวัดระยอง 21000
                   
                               
				</span> 
			</td>
			<td class='cell-span'>
                เลขที่...............
            </td>
			
		</tr>
	</table>	
	<br />

		<div style='text-align: center'><strong> ใบเบิกพัสดุ - ครุภัณฑ์ </strong></div>

	<table border='0' width='100%'>
		<tr>
			<td colspan='2' style='text-align: right;font-size: 12px'> วันที่ &nbsp;&nbsp; $date  </td>
		</tr>
		<tr>
			<td class='cell' width='35%' style='text-align: left;font-size: 12px'> ผู้ขอเบิก &nbsp;&nbsp; $full_name</td>
			<td class='cell' width='65%' style='text-align: left;font-size: 12px'> แผนก &nbsp;&nbsp; $department </td>
		</tr>
		<tr>
			<td class='cell' width='35%' style='text-align: left;font-size: 12px'> ฝ่าย &nbsp; &nbsp; $group </td>
			<td class='cell' width='65%' style='text-align: left;font-size: 12px'> ใช้สำหรับ &nbsp;&nbsp; $detail </td>
		</tr>
		
	</table>


	<table border='1' width='100%'>
		<thead>
		<tr>
			<th style='text-align: center;padding: 7px;font-size: 11px'> ที่ </th>
			<th style='text-align: center;padding: 7px;font-size: 11px'> รายการ </th>
			<th style='text-align: center;padding: 7px;font-size: 11px'> หมายเลขพัสดุ </th>
			<th style='text-align: center;padding: 7px;font-size: 11px'> จำนวน </th>
			<th style='text-align: center;padding: 7px;font-size: 11px'> หน่วย </th>
			<th style='text-align: center;padding: 7px;font-size: 11px'> หมายเหตุ </th>
		</tr>
		</thead>

		<tbody>
";


foreach($printList as $r){

	$a = $r->asset->description;
	$b = $r->asset->name;
	$c = $r->qty;
	$d = $r->asset->notes;
	$e = $r->asset->unit->name;
    

	$html .="
		<tr>
			<td style='padding: 7px;text-align: center;font-size: 11px'> $n </td>
			<td style='padding: 7px;text-align: left;font-size: 11px'> $a </td>
			<td style='padding: 7px;text-align: left;font-size: 11px'> $b </td>
			<td style='padding: 7px;text-align: center;font-size: 11px'> $c </td>
			<td style='padding: 7px;text-align: center;font-size: 11px'> $e </td>
			<td style='padding: 7px;text-align: center;font-size: 11px'> $d </td>
		</tr>
	";

	$n++;
}
	for($i = 0; $i <= $n; $i ++){

		$html .= "
		<tr>
			<td style='padding: 13px;text-align: center;font-size: 11px'>  </td>
			<td style='padding: 13px;text-align: left;font-size: 11px'>  </td>
			<td style='padding: 13px;text-align: left;font-size: 11px'>  </td>
			<td style='padding: 13px;text-align: center;font-size: 11px'>  </td>
			<td style='padding: 13px;text-align: center;font-size: 11px'>  </td>
			<td style='padding: 13px;text-align: center;font-size: 11px'>  </td>
		</tr>
		";
	}

 $html .= "</tbody>";
$html .=" </table>";

$html .="
		<br />
		<table border='1' width='100%' style='margin-top: 20px'>
			<tr>
				<td style='text-align: center;padding: 8px;font-size: 13px'>
					<br />
					<p style='text-align: center;font-size: 13px'> ลงชื่อ ................................................ </p> <br />
					<p style='text-align: center;font-size: 13px'> (&nbsp; $full_name &nbsp;) </p> <br />
					<p style='text-align: center;font-size: 13px'> ผู้ขอเบิก </p> <br />
					<p style='text-align: center;font-size: 13px;'> $create_at </p> <br />
					
				</td>
				<td style='text-align: center;padding: 8px;font-size: 13px'>
					<br />
					<p style='text-align: center;font-size: 13px'> ลงชื่อ ................................................ </p> <br />
					<p style='text-align: center;font-size: 13px'> ( &nbsp;$employee_id &nbsp;) </p> <br />
					<p style='text-align: center;font-size: 13px'> เจ้าหน้าที่พัสดุ </p> <br />
					<p style='text-align: center;font-size: 13px'> $date </p><br />
				</td>
				<td style='text-align: center;padding: 8px;font-size: 13px'>
					<br />
					<p style='text-align: center;font-size: 13px'> ลงชื่อ ................................................ </p> <br />
					<p style='text-align: center;font-size: 13px'> (&nbsp; $full_name &nbsp;) </p> <br />
					<p style='text-align: center;font-size: 13px'> ผู้รับของ </p> <br />
					<p style='text-align: center;font-size: 13px;'> $create_at </p> <br />
				</td>
			</tr>
			<tr>
				<td style='text-align: center;padding: 8px;font-size: 13px'>
					<br />
					<p style='text-align: center;font-size: 13px'> ลงชื่อ ................................................ </p> <br />
					<p style='text-align: center;font-size: 13px'> (............................................) </p> <br />
					<p style='text-align: center;font-size: 13px'> หัวหน้าแผนกผู้ขอเบิก </p> <br />
					<p style='text-align: center;font-size: 13px;'> วันที่................................. </p> <br />
				</td>
				<td style='text-align: center;padding: 8px;font-size: 13px'>
					<br />
					<p style='text-align: center;font-size: 13px'> ลงชื่อ ................................................ </p> <br />
					<p style='text-align: center;font-size: 13px'> (&nbsp; นางเสาวภา เชื้อบุญมี &nbsp;) </p> <br />
					<p style='text-align: center;font-size: 13px'> หัวหน้าแผนกพัสดุฯ </p> <br />
					<p style='text-align: center;font-size: 13px;'> $date </p> <br />
				</td>
				<td style='text-align: center;padding: 8px;font-size: 13px'>
					<br />
					<p style='text-align: center;font-size: 13px'> ลงชื่อ ................................................ </p> <br />
					<p style='text-align: center;font-size: 13px'> (&nbsp; $manager &nbsp;) </p> <br />
					<p style='text-align: center;font-size: 13px'> ผู้อนุมัติ </p> <br />
					<p style='text-align: center;font-size: 13px;'> $date </p> <br />
				</td>
			</tr>
		</table>
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
?>