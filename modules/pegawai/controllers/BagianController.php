<?php

namespace app\modules\pegawai\controllers;

use Yii;
use app\modules\pegawai\models\Bagian;
use app\modules\pegawai\models\BagianSearch;
use app\modules\pegawai\models\Direktorat;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * BagianController implements the CRUD actions for Bagian model.
 */
class BagianController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Bagian models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BagianSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $listData=ArrayHelper::map(Direktorat::find()->all(),'id_direktorat','nama_direktorat');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listData'=>$listData
        ]);
    }

    /**
     * Displays a single Bagian model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Bagian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bagian();
        $listData=ArrayHelper::map(Direktorat::find()->all(),'id_direktorat','nama_direktorat');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Bagian ".$model->nama_bagian." berhasil ditambahkan");
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'listData'=>$listData
        ]);
    }

    /**
     * Updates an existing Bagian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $listData=ArrayHelper::map(Direktorat::find()->all(),'id_direktorat','nama_direktorat');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Bagian ".$model->nama_bagian." berhasil diupdate");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'listData'=> $listData
        ]);
    }

    /**
     * Deletes an existing Bagian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Bagian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Bagian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bagian::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
