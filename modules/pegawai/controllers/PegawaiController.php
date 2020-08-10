<?php

namespace app\modules\pegawai\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\base\models\TbMenu;
use app\modules\app\models\AppUser;
use app\modules\pegawai\models\DataPegawai;
use app\modules\pegawai\models\DataPegawaiSearch;
use app\modules\pegawai\models\JabatanPegawai;
use app\modules\pegawai\models\Jabatan;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\db\Query;

/**
 * PegawaiController implements the CRUD actions for DataPegawai model.
 */
class PegawaiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','update'],
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\HttpException(403, 'You are not allowed to perform this action');
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
     * Lists all DataPegawai models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new DataPegawaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DataPegawai model.
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
     * Creates a new DataPegawai model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DataPegawai();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data pegawai ".$model->nama." berhasil ditambahkan");

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DataPegawai model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data pegawai ".$model->nama." berhasil diupdate");

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DataPegawai model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', "Data pegawai ".$model->nama." berhasil dihapus");

        return $this->redirect(['index']);
    }

    public function actionSetjabatan($id){
        $model = new JabatanPegawai;
        $model->id_pegawai = $id;

        $jabatan_aktif = JabatanPegawai::find()->where(['id_pegawai'=>$id, 'status_jbt'=>1])->one();

        $pegawai = $this->findModel($id);
        $list_jabatan=ArrayHelper::map(Jabatan::find()->all(),'id_jabatan','nama_jabatan');
        $list_penilai=ArrayHelper::map(DataPegawai::find()->leftJoin('app_user', 'data_pegawai.id_pegawai = app_user.pegawai_id')->where(['IN', 'app_user.id_group', [4,6]])->orderBy('data_pegawai.nama ASC')->all(),'id_pegawai','nama');

        $rows['title'] = 'SET JABATAN '.$pegawai->nama;
        $rows['html'] = $this->renderPartial('form_set_jabatan', [
            'model' => $model,
            'list_jabatan'=>$list_jabatan,
            'jabatan_aktif'=>$jabatan_aktif,
            'list_penilai'=>$list_penilai
        ]);
        $rows['footer'] = Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right', 'id'=>'set-jab-pegawai']);

        echo Json::encode($rows);
    }

    public function actionSimpanjabatan(){
        $id_jabatan = $_GET['id_jabatan'];
        $status = $_GET['status'];
        $id_peg = $_GET['id_peg'];
        $id_penilai = $_GET['penilai'];

        $model = JabatanPegawai::find()->where(['id_pegawai'=>$id_peg, 'status_jbt'=>1])->one();

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {
            if($model != null){
                $model->status_jbt = 0;
                $model->save(false);
            }

            $new_model = new JabatanPegawai;
            $new_model->id_jabatan = $id_jabatan;
            $new_model->id_pegawai = $id_peg;
            $new_model->status_jbt = $status;
            $new_model->id_penilai = $id_penilai;
            $new_model->tmt_jbt = date('Y-m-d');
            if($new_model->save()){
                $transaction->commit();
                $rows['msg'] = "Jabatan ".$new_model->jabatan->nama_jabatan." berhasil ditambahkan";
                $rows['success'] = 1;
            }else{
                $rows['msg'] = "Jabatan gagal diupdate ! semua field harus diisi";
                $rows['success'] = 0;
            }
        }catch(\Exception $e) {

            $transaction->rollBack();

            throw $e;

        }

        echo Json::encode($rows);

    }

    /**
     * Finds the DataPegawai model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DataPegawai the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DataPegawai::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
