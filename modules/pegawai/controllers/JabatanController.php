<?php

namespace app\modules\pegawai\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\base\models\TbMenu;
use app\modules\pegawai\models\Jabatan;
use app\modules\pegawai\models\JabatanSearch;
use app\modules\pegawai\models\GradeJabatan;
use app\modules\pegawai\models\KlpJabatan;
use app\modules\logbook\models\Target;
use app\modules\logbook\models\TargetSearch;
use app\modules\pegawai\models\DataPegawai;
use app\modules\pegawai\models\UnitKerja;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * JabatanController implements the CRUD actions for Jabatan model.
 */
class JabatanController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','update','settarget','listtarget','simpantarget'],
                'denyCallback' => function ($rule, $action) {
                    throw new \Exception('You are not authorized to access this page');
                },
                'rules' => [
                    [
                        //'actions' => ['logout','index','excelrekap'],
                        'actions' => TbMenu::getAksesUser(),
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            
        ];
    }

    /**
     * Lists all Jabatan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JabatanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $list_grade = ArrayHelper::map(GradeJabatan::find()->orderBy('kode_grade ASC')->all(),'id_grade','kode_grade');
        $list_klp_jabatan = ArrayHelper::map(KlpJabatan::find()->orderBy('nama_klp_jabatan ASC')->all(),'id_klp_jabatan','nama_klp_jabatan');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'list_grade'=>$list_grade,
            'list_klp_jabatan'=>$list_klp_jabatan
        ]);
    }

    /**
     * Displays a single Jabatan model.
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
     * Creates a new Jabatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Jabatan();

        $list_grade = ArrayHelper::map(GradeJabatan::find()->orderBy('kode_grade ASC')->all(),'id_grade','kode_grade');

        $list_level = Jabatan::getLevelJabatan();
        $list_peer = Jabatan::getPeerGroup();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Jabatan ".$model->nama_jabatan." berhasil ditambahkan");
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'list_grade'=>$list_grade,
            'list_level'=>$list_level,
            'list_peer'=>$list_peer
        ]);
    }

    /**
     * Updates an existing Jabatan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

         $list_grade = ArrayHelper::map(GradeJabatan::find()->orderBy('kode_grade ASC')->all(),'id_grade','kode_grade');

        $list_level = Jabatan::getLevelJabatan();
        $list_peer = Jabatan::getPeerGroup();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Jabatan ".$model->nama_jabatan." berhasil diupdate");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'list_grade'=>$list_grade,
            'list_level'=>$list_level,
            'list_peer'=>$list_peer
        ]);
    }

    public function actionSettarget($id){

        $jabatan = Jabatan::findOne($id);
        $model = Target::find()->where(['id_jabatan'=>$id, 'status_target'=>1])->one();
        if($model == null){
            $model = new Target;
        }
        $model->id_jabatan = $id;
        $rows['title'] = 'SET TARGET '.$jabatan->nama_jabatan;
        $rows['html'] = $this->renderPartial('form_set_target', [
            'model' => $model
        ]);
        $rows['footer'] = Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right', 'id'=>'set-target-jabatan']);

        echo Json::encode($rows);
    }

    public function actionListtarget($id){

        $model = Jabatan::findOne($id);

        $data = DataPegawai::find()
        ->select(['nama as value', 'nama as label', 'id_pegawai as id'])
        ->asArray()
        ->all();

        $list_unit_kerja = ArrayHelper::map(UnitKerja::find()->orderBy('nama_unit_kerja ASC')->all(),'id_unit_kerja','nama_unit_kerja');

        $searchModel = new TargetSearch();
        $searchModel->id_jabatan = $id;
        $searchModel->status_target = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        

        return $this->render('form_list_target', [
            'model' => $model,
            'data'=>$data,
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'list_unit_kerja'=>$list_unit_kerja
        ]);
        
    }

    /**
     * Deletes an existing Jabatan model.
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

    public function actionSimpantarget(){
        $id_jabatan = $_POST['id_jabatan'];
        $id_unit_kerja = $_POST['id_unit_kerja'];
        $target = $_POST['target'];

        $model = Target::find()->where(['id_jabatan'=>$id_jabatan, 'id_unit_kerja'=>$id_unit_kerja, 'status_target'=>1])->one();

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();


        try {
            if($model != null){
                $model->status_target = 0;
                $model->save(false);
            }

            $new_model = new Target;
            $new_model->id_jabatan = $id_jabatan;
            $new_model->id_unit_kerja = $id_unit_kerja;
            $new_model->nilai_target = $target;
            $new_model->status_target = 1;
            if($new_model->save()){
                $transaction->commit();
                $rows['msg'] = "Target ".$new_model->jabatan->nama_jabatan." berhasil ditambahkan";
                $rows['success'] = 1;
            }else{
                $rows['msg'] = "Target gagal diupdate ! semua field harus diisi";
                $rows['success'] = 0;
            }
        }catch(\Exception $e) {

            $transaction->rollBack();

            throw $e;

        }

        echo Json::encode($rows);
    }

    /**
     * Finds the Jabatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Jabatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Jabatan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
