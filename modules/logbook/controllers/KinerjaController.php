<?php

namespace app\modules\logbook\controllers;

use Yii;
use app\modules\logbook\models\Kinerja;
use app\modules\logbook\models\KinerjaSearch;
use app\modules\logbook\models\Tugas;
use app\modules\pegawai\models\DataPegawai;
use app\modules\pegawai\models\PegawaiUnitKerja;
use app\modules\pegawai\models\JabatanPegawai;
use app\modules\app\models\AppUser;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * KinerjaController implements the CRUD actions for Kinerja model.
 */
class KinerjaController extends Controller
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
     * Lists all Kinerja models.
     * @return mixed
     */
    public function actionIndex()
    {
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);
        $list_pegawai_dinilai = JabatanPegawai::find()->select(['data_pegawai.id_pegawai','data_pegawai.nama'])->leftJoin('data_pegawai','jabatan_pegawai.id_pegawai = data_pegawai.id_pegawai')->where(['jabatan_pegawai.id_penilai'=>$user->pegawai_id, 'jabatan_pegawai.status_jbt'=>1])->orderBy('data_pegawai.nama ASC')->all();

        $searchModel = new KinerjaSearch();
        $searchModel->range_date = date('m/d/Y').' - '.date('m/d/Y');

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $listData = ArrayHelper::map(Tugas::find()->all(),'id_tugas','nama_tugas');


        $listPegawai = ArrayHelper::map($list_pegawai_dinilai,'id_pegawai','nama');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listData'=>$listData,
            'listPegawai'=>$listPegawai
        ]);
    }

    /**
     * Displays a single Kinerja model.
     * @param integer $id
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
     * Creates a new Kinerja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Kinerja();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_kinerja]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Kinerja model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_kinerja]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Kinerja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionCreatelogbook(){
        $model = new Kinerja();

        $model->tanggal_kinerja = date('Y-m-d');
        return $this->render('index_logbook',[
            'model'=>$model
        ]);
    }

    public function actionAutotugas(){
        $query = $_GET['query'];
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $unit_kerja = PegawaiUnitKerja::find()->where(['id_pegawai'=>$user->pegawai_id, 'status_peg'=>1])->one();

        $model = Tugas::find()
        ->select(['nama_tugas as nama_tugas','id_tugas as id_tugas'])
        ->where(['id_unit_kerja'=>$unit_kerja->id_unit_kerja, 'status_tugas'=>1])
        ->andFilterWhere(['like', 'nama_tugas', $query])
        ->orderBY('nama_tugas ASC')
        ->asArray()
        ->all();

        $arr_data = array();

        if($model != null){
            foreach($model as $val){
                $arr_data[] = $val['nama_tugas'].' | '.$val['id_tugas'];
            }
        }

        $return['data'] = $arr_data;

        echo Json::encode($return);
    }

    public function actionSimpanbacklog(){

        $tugas = $_POST['tugas'];
        $deskripsi = $_POST['deskripsi'];
        $jumlah = $_POST['jumlah'];
        $rows = $_POST['rows'];
        $date = $_POST['date'];

        $explode_tugas = explode("|",$tugas);
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $last_row = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d'), 'id_pegawai'=>$user->pegawai_id])->orderBy('row DESC')->one();

        $model = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d', strtotime($date)), 'id_pegawai'=>$user->pegawai_id, 'row'=>$rows])->one();

         
        if($last_row != null){
            $next_row = $last_row->row + 1;

            if($next_row == $rows){
                $rows = $rows;
            }else{
                if($model != null){ //jika posisi update
                    $rows = $rows;
                }else{
                    $rows = $next_row;
                }
                
            }
        }

        if($model == null){
            $model = new Kinerja;
        }
        
        $return['success'] = 0;

        $model->tanggal_kinerja = date('Y-m-d', strtotime($date));
        $model->id_pegawai = $user->pegawai_id;
        $model->id_tugas = trim($explode_tugas[1]);
        $model->jumlah = $jumlah;
        $model->deskripsi = $deskripsi;
        $model->row = $rows;
        $model->approval = 0;
        $model->create_date = date('Y-m-d');
        if($model->save()){
            $return['success'] = 1;
            $return['msg'] = '"'.$model->deskripsi.'" berhasil disimpan';
        }

        $load_kinerja = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d', strtotime($date)), 'id_pegawai'=>$user->pegawai_id])->all();

        $arr_data = array();
        if($load_kinerja != null){
            foreach($load_kinerja as $val){
                $arr_data[] = ['tugas'=>$val->tugas->nama_tugas.' | '.$val->id_tugas,'deskripsi'=>$val->deskripsi, 'jumlah'=>$val->jumlah];
            }
        }

        $return['data'] = $arr_data;

        echo Json::encode($return);
        
    }

    public function actionGetdatakinerja(){
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $tgl = $_GET['tgl'];
        $model = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d', strtotime($tgl)), 'id_pegawai'=>$user->pegawai_id])->all();

        $arr_data = array();
        if($model != null){
            foreach($model as $val){
                $arr_data[] = ['tugas'=>$val->tugas->nama_tugas.' | '.$val->id_tugas,'deskripsi'=>$val->deskripsi, 'jumlah'=>$val->jumlah];
            }
        }

        $data['data'] = $arr_data;

        echo Json::encode($data);

    }

    public function actionDeletebacklog(){
        $row = $_POST['rows'];
        $date = $_POST['date'];

        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $model = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d', strtotime($date)), 'id_pegawai'=>$user->pegawai_id, 'row'=>$row])->one();

        $return['suceess'] = 0;
        if($model->delete()){
            $models = Kinerja::find()->where('row > '.$row)->andFilterWhere(['tanggal_kinerja'=>date('Y-m-d', strtotime($date)), 'id_pegawai'=>$user->pegawai_id])->all();
            if($models != null){
               foreach ($models as $model_2) {
                    $model_2->row = $model_2->row - 1;
                    $model_2->update(false); // skipping validation as no user input is involved
                } 
            }
            
            $return['success'] = 1;
            $return['msg'] = '"'.$model->deskripsi.'" berhasil dihapus';
        }

        $load_kinerja = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d', strtotime($date)), 'id_pegawai'=>$user->pegawai_id])->all();

        $arr_data = array();
        if($load_kinerja != null){
            foreach($load_kinerja as $val){
                $arr_data[] = ['tugas'=>$val->tugas->nama_tugas.' | '.$val->id_tugas,'deskripsi'=>$val->deskripsi, 'jumlah'=>$val->jumlah];
            }
        }

        $return['data'] = $arr_data;

        echo Json::encode($return);
    }

    public function actionApprove(){
        $id = $_GET['id'];
        $return['success'] = 0;
        $model = Kinerja::findOne($id);
        $id_user = Yii::$app->user->id;
        if($model->approval == 0){
            $model->approval = 1;
            $model->user_approval = $id_user;
            $model->tgl_approval = date('Y-m-d');
            $model->save(false);

            $return['success'] = 1;
        }else{
            $model->approval = 0;
             $model->user_approval = null;
             $model->tgl_approval = null;
             $model->save(false);

            $return['success'] = 1;
        }

        echo Json::encode($return);
    }

    public function actionSearchkinerja(){
        $range_date = $_GET['tgl'];

        $date = explode('-',$range_date);
        $date_start = trim($date[0]);
        $date_end = trim($date[1]);

        $return['success'] = 1;
        $return['datestart'] = $date_start;
        $return['dateend'] = $date_end;

        echo Json::encode($return);

    }

    /**
     * Finds the Kinerja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Kinerja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kinerja::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
