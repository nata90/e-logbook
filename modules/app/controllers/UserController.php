<?php

namespace app\modules\app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\base\models\TbMenu;
use app\modules\app\models\AppUser;
use app\modules\app\models\AppUserSearch;
use app\modules\pegawai\models\DataPegawai;
use app\modules\pegawai\models\PegawaiUnitKerja;
use app\modules\pegawai\models\JabatanPegawai;
use app\modules\logbook\models\Target;
use app\modules\app\models\AppUserGroup;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Html;

/**
 * UserController implements the CRUD actions for AppUser model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','update','updatepassword','savenewpassword','profile'],
                'denyCallback' => function ($rule, $action) {
                    if(Yii::$app->user->isGuest){
                        return $this->goHome();
                    }else{
                        throw new \yii\web\HttpException(403, 'You are not allowed to perform this action');
                    }
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
     * Lists all AppUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $list_group = ArrayHelper::map(AppUserGroup::find()->orderBy('nama_group ASC')->all(),'id','nama_group');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'list_group'=>$list_group
        ]);
    }

    /**
     * Displays a single AppUser model.
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
     * Creates a new AppUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AppUser();
        $model->scenario = AppUser::SCENARIO_ADD;
        $model->active = 1;

        $data = DataPegawai::find()
        ->select(['nama as value', 'nama as label', 'id_pegawai as id'])
        ->asArray()
        ->all();

        $list_group = ArrayHelper::map(AppUserGroup::find()->orderBy('nama_group ASC')->all(),'id','nama_group');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "User ".$model->username." berhasil ditambahkan");
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'data'=>$data,
            'list_group'=>$list_group
        ]);
    }

    /**
     * Updates an existing AppUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = AppUser::SCENARIO_UPDATE;

        $model->pegawai_nama = $model->pegawai->nama;
        $list_group = ArrayHelper::map(AppUserGroup::find()->orderBy('nama_group ASC')->all(),'id','nama_group');

        $data = DataPegawai::find()
        ->select(['nama as value', 'nama as label', 'id_pegawai as id'])
        ->asArray()
        ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "User ".$model->username." berhasil diupdate");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'data'=>$data,
            'list_group'=>$list_group
        ]);
    }

    /**
     * Deletes an existing AppUser model.
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

    public function actionUpdatepassword($id){

        $model = $this->findModel($id);

        $rows['title'] = 'UPDATE PASSWORD';
        $rows['html'] = $this->renderPartial('form_update_password', [
            'model' => $model
        ]);
        $rows['footer'] = Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right', 'id'=>'update-password']);

        echo Json::encode($rows);
    }

    public function actionSavenewpassword(){
        $id = $_GET['id'];
        $password = $_GET['new_password'];

        if($password != ''){
            $model = $this->findModel($id);
            $authkey = md5(time());
            $model->password = md5($password.$authkey);
            $model->authkey = $authkey;
            $rows['success'] = 0;
            if($model->save(false)){
                $rows['msg'] = "Password ".$model->username." berhasil diupdate";
                $rows['success'] = 1;
            }else{
                $rows['msg'] = "Password ".$model->username." gagal diupdate";
                $rows['success'] = 0;
            }
        }else{
            $rows['msg'] = "Password baru tidak boleh kosong";
            $rows['success'] = 0;
        }
        
        
        echo Json::encode($rows);
    }

    public function actionProfile(){
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $model = DataPegawai::findOne($user->pegawai_id);
        $model->update = 1;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data pegawai ".$model->nama." berhasil diupdate");

            return $this->redirect(['profile']);
        }

        //unit kerja
        $model_unit = PegawaiUnitKerja::find()->where(['id_pegawai'=>$user->pegawai_id, 'status_peg'=>1])->one();

        //jabatan
        $model_jabatan = JabatanPegawai::find()->where(['id_pegawai'=>$user->pegawai_id, 'status_jbt'=>1])->one();

        //target jabatan
        $model_target = Target::find()->where(['id_jabatan'=>$model_jabatan->id_jabatan, 'id_unit_kerja'=>$model_unit->id_unit_kerja, 'status_target'=>1])->one();

        return $this->render('profile', [
            'model' => $model,
            'model_unit' =>$model_unit,
            'model_jabatan'=>$model_jabatan,
            'model_target'=>$model_target,
            'user'=>$user
        ]);
    }


    public function actionCekprofile(){
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $model = DataPegawai::findOne($user->pegawai_id);

        if($model->update == 0){
            $rows['update'] = 0;
        }else{
            $rows['update'] = 1;
        }

        echo Json::encode($rows);
    }

    public function actionUploadphoto(){
        $id_user = Yii::$app->user->id;
        if(!empty($_FILES)){
            if(is_uploaded_file($_FILES['uploadFile']['tmp_name'])){
                $srcPath = $_FILES['uploadFile']['tmp_name'];
                $name = $id_user.'_'.$_FILES['uploadFile']['name'];
                $trgPath = 'profpic/'.$name;
                if(move_uploaded_file($srcPath, $trgPath)){                  
                    $user = AppUser::findOne($id_user);
                    $user->photo_profile = $name;
                    $user->save(false);

                    echo '<img src="'.Yii::$app->request->baseUrl.'/profpic/'.$name.'" class="profile-user-img img-responsive" alt="User Image"/>';
                }
            }
        }
    }

    /**
     * Finds the AppUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AppUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
