<?php

namespace app\modules\logbook\controllers;

use Yii;
use app\modules\logbook\models\Kinerja;
use app\modules\logbook\models\KinerjaSearch;
use app\modules\logbook\models\Tugas;
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
        $searchModel = new KinerjaSearch();
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $listData = ArrayHelper::map(Tugas::find()->all(),'id_tugas','nama_tugas');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listData'=>$listData
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

        $model = Tugas::find()
        ->select(['nama_tugas as nama_tugas','id_tugas as id_tugas'])
        ->where(['id_unit_kerja'=>'03402'])
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

        $last_row = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d'), 'id_pegawai'=>3])->orderBy('row DESC')->one();

        $model = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d', strtotime($date)), 'id_pegawai'=>3, 'row'=>$rows])->one();

         
        if($last_row != null){
            $next_row = $last_row->row + 1;

            if($next_row == $rows){
                $rows = $rows;
            }else{
                $rows = $next_row;
            }
        }

        if($model == null){
            $model = new Kinerja;
        }
        
        $return['success'] = 0;

        $model->tanggal_kinerja = date('Y-m-d', strtotime($date));
        $model->id_pegawai = 3;
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

        echo Json::encode($return);
        
    }

    public function actionGetdatakinerja(){

        $tgl = $_GET['tgl'];
        $model = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d', strtotime($tgl)), 'id_pegawai'=>3])->all();

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

        $model = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d', strtotime($date)), 'id_pegawai'=>3, 'row'=>$row])->one();

        $return['suceess'] = 0;
        if($model->delete()){
            $models = Kinerja::find()->where('row > '.$row)->andFilterWhere(['tanggal_kinerja'=>date('Y-m-d', strtotime($date)), 'id_pegawai'=>3])->all();
            if($models != null){
               foreach ($models as $model_2) {
                    $model_2->row = $model_2->row - 1;
                    $model_2->update(false); // skipping validation as no user input is involved
                } 
            }
            
            $return['success'] = 1;
            $return['msg'] = '"'.$model->deskripsi.'" berhasil dihapus';
        }

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
