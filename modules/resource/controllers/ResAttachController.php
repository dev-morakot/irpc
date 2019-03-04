<?php

namespace app\modules\resource\controllers;

use Yii;
use app\modules\resource\models\ResAttach;
use app\modules\resource\models\ResAttachModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ResAttachController implements the CRUD actions for ResAttach model.
 */
class ResAttachController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST', 'GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all ResAttach models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ResAttachModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ResAttach model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ResAttach model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate()
//    {
//        $model = new ResAttach();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
//    }

    public function actionCreateForModel($related_model, $related_id) {
        $rModel = (new \yii\db\Query())->select('id,name')->from($related_model)->where(['id' => $related_id])->one();
        $model = new ResAttach();
        $model->date = \app\components\DateHelper::nowStringDB();
        $isLoaded = $model->load(Yii::$app->request->post());
        if ($isLoaded) {
            $model->uploadFile = \yii\web\UploadedFile::getInstance($model, 'uploadFile');
            if ($model->uploadFile) {
                $id = Yii::$app->docAttach->attach(
                        $related_model, $related_id, $model->uploadFile,
                        $model->description);
                if ($id) {
                    Yii::$app->session->setFlash('success', 'upload success ' . $model->uploadFile->name);
                    $model->uploadFile = null;
                } else {
                    Yii::$app->session->setFlash('warning', 'upload error');
                }
            }
            return $this->redirect(['view', 'id' => $id]);
        } else {
            return $this->render('create', [
                        'rModel' => $rModel,
                        'model' => $model,
            ]);
        }
    }

    public function actionUploadAjax() {
        $allows = ['png','pdf','jpg'];
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new ResAttach();
        $isLoaded = $model->load(Yii::$app->request->post());
        if ($isLoaded) {
            $model->uploadFile = \yii\web\UploadedFile::getInstance($model, 'uploadFile');
            if ($model->uploadFile) {
                if(!in_array($model->uploadFile->extension, $allows)){
                    
                    return ['result'=>'fail','msg'=>'อนุญาตเฉพาะไฟล์ '. implode(',', $allows)];
                }
                try {
                    $id = Yii::$app->docAttach->attach(
                            $model->related_model, $model->related_id, $model->uploadFile,
                            $model->description);
                    if ($id) {
                        return ['result' => 'success', 'msg' => $id];
                    } else {
                        return ['result' => 'fail', 'msg' => 'cannot attach'];
                    }
                } catch (\Exception $ex) {
                    return ['result' => 'fail', 'msg' => $ex->getMessage()];
                }
            }
        } else {
            return ['result' => 'fail', 'msg' => 'model cannot load'];
        }
    }

    /**
     * Updates an existing ResAttach model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ResAttach model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteAjax($id) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result = $this->findModel($id)->delete();
        if ($result) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    public function actionDownload($id) {
        $model = ResAttach::findOne(['id' => $id]);
        $filepath = Yii::getAlias('@attach') . '/' . $model->related_model . '/' . $model->file;
        if (file_exists($filepath)) {
            Yii::$app->response->sendFile($filepath);
        } else {
            throw new \Exception("File not found");
        }
    }

    /**
     * Finds the ResAttach model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ResAttach the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ResAttach::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}