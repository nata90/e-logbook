<?php

namespace app\modules\pegawai\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\base\models\TbMenu;
use app\modules\app\models\AppUser;
use app\modules\pegawai\models\UnitKerja;
use app\modules\pegawai\models\UnitKerjaSearch;
use app\modules\pegawai\models\Bagian;
use app\modules\pegawai\models\DataPegawai;
use app\modules\pegawai\models\PegawaiUnitKerjaSearch;
use app\modules\pegawai\models\PegawaiUnitKerja;
use app\modules\pegawai\models\PegawaiUnitKerjaQuery;
use app\modules\pegawai\models\JabatanPegawai;
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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','update','listpegawai','addpegawai','getidunit','deletepegawai','getidunit','monitoringsatker'],
                'denyCallback' => function ($rule, $action) {
                    if(Yii::$app->user->isGuest){
                        return $this->goHome();
                    }else{
                        throw new \yii\web\HttpException(403, 'You are not allowed to perform this action');
                    }
                },
                'rules' => [
                    [
                        'actions' => TbMenu::getAksesUser(),
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            
                            return true;
                        }
                    ],
                    [
                        'actions' => [''],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $peg_unit_kerja = PegawaiUnitKerja::find()->where(['id_pegawai'=>$user->pegawai_id, 'status_peg'=>1])->one();
        $searchModel = new UnitKerjaSearch();
        $filter = $searchModel;
        if($user->id_group == 3){
            if($peg_unit_kerja != null){
                $searchModel->id_unit_kerja = $peg_unit_kerja->id_unit_kerja;
            }else{
                $searchModel->id_unit_kerja = '0';
            }
            
            $filter = false;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter'=>$filter
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

    public function actionDeletepegawai($id){
        $model = PegawaiUnitKerja::findOne($id);
        $id_unit_ker = $model->id_unit_kerja;

        Yii::$app->session->setFlash('success', "Data pegawai ".$model->pegawai->nama." berhasil dihapus dari unit kerja ".$model->unitKerja->nama_unit_kerja);
        $model->status_peg = 0;
        $model->save(false);

        //non aktifkan jabatan
        $pegawai_jabatan = JabatanPegawai::find()->where(['id_pegawai'=>$model->id_pegawai, 'status_jbt'=>1])->one();
        if($pegawai_jabatan != null){
            $pegawai_jabatan->status_jbt = 0;
            $pegawai_jabatan->save(false);
        }
        

        return $this->redirect(['listpegawai','id'=>$id_unit_ker]);
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
            $rows['id_unit'] = $bagian.'01';
        }

        echo Json::encode($rows);
    }

    public function actionListpegawai($id){

        $model = $this->findModel($id);
        $data = DataPegawai::find()
        ->select(['nama as value', 'nama as label', 'id_pegawai as id'])
        ->asArray()
        ->all();

        $searchModel = new PegawaiUnitKerjaSearch();
        $searchModel->id_unit_kerja = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('form_unit_kerja', [
            'model' => $model,
            'data'=>$data,
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
        
    }

    public function actionAddpegawai(){
        $id_peg = $_GET['id_peg'];
        $id_unit = $_GET['id_unit_ker'];

        $model = PegawaiUnitKerja::find()->where(['id_pegawai'=>$id_peg,'status_peg'=>1])->one();

        if($model != null){
            $rows['error'] = 1;
            $rows['msg'] = $model->pegawai->nama." masih aktif di unit kerja ".$model->unitKerja->nama_unit_kerja;
        }else{
            $model2 = new PegawaiUnitKerja;
            $model2->id_unit_kerja = $id_unit;
            $model2->id_pegawai = $id_peg;
            $model2->status_peg = 1;
            $model2->tmt_peg = date('Y-m-d');
            $model2->save(false);

            $rows['error'] = 0;
            $rows['msg'] = $model2->pegawai->nama." berhasil ditambahkan di unit kerja ".$model2->unitKerja->nama_unit_kerja;
        }

        echo Json::encode($rows);
    }

    public function actionMonitoringsatker(){
        $searchModel = new UnitKerjaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('monitoring_satker', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
