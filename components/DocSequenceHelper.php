<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;
use Yii;
use \DateTime;
use app\modules\resource\models\ResDocSequence;
use app\models\User;
/**
 * Description of DateHelper
 *
 * @author morakot
 */
class DocSequenceHelper {
    
    const IT_DOC_NO = "it_doc_no";
    const AS_DOC_NO = "as_doc_no";
    const RE_DOC_NO = "re_doc_no";
   
    
    public static function genDocNo($code){
        $resDocSeq = ResDocSequence::findOne(['code'=>$code]);
        $doc_no = DocSequenceHelper::generateDocNo($resDocSeq);
        return $doc_no;
    }
    
    public static function genDocNoByCodePreview($code){
        $resDocSeq = ResDocSequence::findOne(['code'=>$code]);
        $doc_no = DocSequenceHelper::generateDocNo($resDocSeq,false);
        return $doc_no;
    }
    
    public static function genDocNoById($id){
        $resDocSeq = ResDocSequence::findOne(['id'=>$id]);
        $doc_no = DocSequenceHelper::generateDocNo($resDocSeq);
        return $doc_no;
    }
    
    public static function genDocNoPreview($doctype){
        $resDocSeq = ResDocSequence::findOne(['name'=>$doctype]);
        $doc_no = DocSequenceHelper::generateDocNo($resDocSeq,false);
        return $doc_no;
    }
    
    public static function genDocNoByIdPreview($id){
        $resDocSeq = ResDocSequence::findOne(['id'=>$id]);
        $doc_no = DocSequenceHelper::generateDocNo($resDocSeq,false);
        return $doc_no;
    }
    
    public static function listDocSeq($doc_type,$group_id=null){
        return ResDocSequence::find()->byType($doc_type,$group_id)->asArray()->all();
    }
    
    public static function listDocSeqForUser($doc_type,$user_id){
        return ResDocSequence::find()->byTypeForUser($doc_type, $user_id)->asArray()->all();
    }
    
    public static function listPrDocSeqForUser($user_id){
        return ResDocSequence::find()->byTypePrForUser($user_id)->asArray()->all();
    }
    
    private static function generateDocNo(&$resDocSeq,$save=true){
        $prefix = $resDocSeq->prefix;
        $date_include = (new \DateTime('now'))->format($resDocSeq->date_format);
        $running_length = $resDocSeq->running_length;
        $counter = $resDocSeq->counter;
        $suffix = str_pad($counter, $running_length, "0",STR_PAD_LEFT);
        $doc_no = $prefix.$date_include.$suffix;
        $resDocSeq->counter += 1;
        if($save){
            $resDocSeq->save(false);
        }
        return $doc_no;
    }
}