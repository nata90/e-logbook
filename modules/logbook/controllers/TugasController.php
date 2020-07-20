<?php

namespace app\modules\logbook\controllers;

use Yii;
use app\modules\logbook\models\Tugas;
use app\modules\logbook\models\TugasSearch;
use app\modules\pegawai\models\UnitKerja;
use app\modules\logbook\models\Kategori;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Json;

/**
 * TugasController implements the CRUD actions for Tugas model.
 */
class TugasController extends Controller
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
     * Lists all Tugas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TugasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tugas model.
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
     * Creates a new Tugas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tugas();

        $list_unit_kerja = ArrayHelper::map(UnitKerja::find()->orderBy('nama_unit_kerja ASC')->all(),'id_unit_kerja','nama_unit_kerja');

        $list_kategori = ArrayHelper::map(Kategori::find()->orderBy('nama_kategori ASC')->all(),'id_kategori','nama_kategori');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Tugas ".$model->nama_tugas." berhasil ditambahkan");
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'list_unit_kerja'=>$list_unit_kerja,
            'list_kategori'=>$list_kategori
        ]);
    }

    /**
     * Updates an existing Tugas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $list_unit_kerja = ArrayHelper::map(UnitKerja::find()->orderBy('nama_unit_kerja ASC')->all(),'id_unit_kerja','nama_unit_kerja');

        $list_kategori = ArrayHelper::map(Kategori::find()->orderBy('nama_kategori ASC')->all(),'id_kategori','nama_kategori');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Tugas ".$model->nama_tugas." berhasil diupdate");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'list_unit_kerja'=>$list_unit_kerja,
            'list_kategori'=>$list_kategori
        ]);
    }

    /**
     * Deletes an existing Tugas model.
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

    public function actionGetidtugas(){
        $id_unit_kerja = $_GET['unit_ker'];

        $model = Tugas::find()->where(['id_unit_kerja'=>$id_unit_kerja])->orderBy('id_tugas DESC')->one();

        $rows = array();
        if($model != null){
            $subs = substr($model->id_tugas,-5);

            $next_number = (int)$subs + 1;
            $length = strlen($next_number);

            $nol = 5 - $length;

            $tot_nol = '';
            for($i=1;$i<=$nol;$i++){
                $tot_nol .= '0';
            }


            $last_number = $tot_nol.$next_number;
            $rows['nilai'] = $id_unit_kerja.$last_number;

        }else{
            $rows['nilai'] = $id_unit_kerja.'00001';
        }

        echo Json::encode($rows);
    }

    /**
     * Finds the Tugas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Tugas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tugas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
