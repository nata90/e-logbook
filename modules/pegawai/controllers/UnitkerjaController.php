<?php

namespace app\modules\pegawai\controllers;

use Yii;
use app\modules\pegawai\models\UnitKerja;
use app\modules\pegawai\models\UnitKerjaSearch;
use app\modules\pegawai\models\Bagian;
use app\modules\pegawai\models\DataPegawai;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Html;


/**
 * UnitkerjaController implements the CRUD actions for UnitKerja model.
 */
class UnitkerjaController extends Controller
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
     * Lists all UnitKerja models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UnitKerjaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UnitKerja model.
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
     * Creates a new UnitKerja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UnitKerja();

        $listData=ArrayHelper::map(Bagian::find()->all(),'id_bagian','nama_bagian');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             Yii::$app->session->setFlash('success', "Unit kerja ".$model->nama_unit_kerja." berhasil ditambahkan");

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'listData' => $listData
        ]);
    }

    /**
     * Updates an existing UnitKerja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $listData=ArrayHelper::map(Bagian::find()->all(),'id_bagian','nama_bagian');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Unit kerja ".$model->nama_unit_kerja." berhasil diupdate");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'listData'=>$listData
        ]);
    }

    /**
     * Deletes an existing UnitKerja model.
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

    public function actionGetidunit(){
        $bagian = $_GET['bagian'];

        $unit_kerja = UnitKerja::find()->where(['id_bagian'=>$bagian])->orderBy('id_unit_kerja DESC')->one();

        if($unit_kerja != null){

            $str = str_split($unit_kerja->id_unit_kerja);
            $arr_reverse = array_reverse($str);

            $last_number = $arr_reverse[1].$arr_reverse[0];
            $new_number = (int)$last_number + 1;

            if(strlen($new_number) == 1){
                $return = $bagian.'0'.$new_number;
            }else{
                $return = $bagian.$new_number;
            }

            $rows['id_unit'] = $return;
        }else{
            $rows['id_unit'] = $bagian.'00';
        }

        echo Json::encode($rows);
    }

    public function actionListpegawai($id){

        $model = $this->findModel($id);
        $data = DataPegawai::find()
        ->select(['nama as value', 'nama as label', 'id_pegawai as id'])
        ->asArray()
        ->all();

        return $this->render('form_unit_kerja', [
            'model' => $model,
            'data'=>$data
        ]);
        
    }

    /**
     * Finds the UnitKerja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return UnitKerja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UnitKerja::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
