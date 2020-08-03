<?php

namespace app\modules\pegawai\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\base\models\TbMenu;
use app\modules\pegawai\models\Bagian;
use app\modules\pegawai\models\BagianSearch;
use app\modules\pegawai\models\Direktorat;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','update'],
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\HttpException(403, 'You are not allowed to perform this action');
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

    public function actionGetidbagian(){
        $direktorat = $_GET['direktorat'];

        $bagian = Bagian::find()->where(['id_direktorat'=>$direktorat])->orderBy('id_bagian DESC')->one();

        if($bagian != null){
            $str = str_split($bagian->id_bagian);
            $arr_reverse = array_reverse($str);
            $new_number = $arr_reverse[0] + 1;
            $rows['id_bag'] = $direktorat.$new_number;
        }else{
            $rows['id_bag'] = $direktorat.'0';
        }

        echo Json::encode($rows);
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
