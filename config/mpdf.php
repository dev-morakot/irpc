<?php

return [
    'class' => app\modules\mpdf\Pdf::className(), //kartik\mpdf\Pdf::className(),
    'mode' => app\modules\mpdf\Pdf::MODE_UTF8, //kartik\mpdf\Pdf::MODE_UTF8,
    'format' => app\modules\mpdf\Pdf::FORMAT_A4, //kartik\mpdf\Pdf::FORMAT_A4,
    'orientation' => app\modules\mpdf\Pdf::ORIENT_PORTRAIT, //kartik\mpdf\Pdf::ORIENT_PORTRAIT,
    'destination' => app\modules\mpdf\Pdf::DEST_BROWSER, //kartik\mpdf\Pdf::DEST_BROWSER,
    //'cssFile' => "@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css",
    'cssFile'=>'@app/web/css/pdf.css',
    //'cssInline' => "body {font-family:'Garuda'; font-size:10pt;} .container{border-collapse: collapse;border-spacing:0;}"
];
