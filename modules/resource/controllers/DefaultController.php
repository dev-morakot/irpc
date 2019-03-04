<?php

namespace app\modules\resource\controllers;

use yii\web\Controller;
use app\components\DocSequenceHelper;
use app\modules\resource\models\ResUsers;

/**
 * Default controller for the `resource` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	$id = Yii::$app->user->identity->id;
    	$model = ResUsers::findOne(['id' => $id]);

        return $this->render('index',['model' =>$model]);
    }
}