<?php

namespace app\modules\pegawai\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\base\models\TbMenu;
use app\modules\pegawai\models\GradeJabatan;
use app\modules\pegawai\models\GradeJabatanSearch;
use app\modules\pegawai\models\KlpJabatan;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * GradejabatanController implements the CRUD actions for GradeJabatan model.
 */
class GradejabatanController extends Controller
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
     * Lists all GradeJabatan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GradeJabatanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $list_jabatan = ArrayHelper::map(KlpJabatan::find()->orderBy('nama_klp_jabatan ASC')->all(),'id_klp_jabatan','nama_klp_jabatan');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'list_jabatan'=>$list_jabatan
        ]);
    }

    /**
     * Displays a single GradeJabatan model.
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
     * Creates a new GradeJabatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GradeJabatan();
        $list_jabatan = ArrayHelper::map(KlpJabatan::find()->orderBy('nama_klp_jabatan ASC')->all(),'id_klp_jabatan','nama_klp_jabatan');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Grade jabatan ".$model->kode_grade." berhasil ditambahkan");
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'list_jabatan' => $list_jabatan
        ]);
    }

    /**
     * Updates an existing GradeJabatan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $list_jabatan = ArrayHelper::map(KlpJabatan::find()->orderBy('nama_klp_jabatan ASC')->all(),'id_klp_jabatan','nama_klp_jabatan');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Grade jabatan ".$model->kode_grade." berhasil diupdate");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'list_jabatan'=>$list_jabatan
        ]);
    }

    /**
     * Deletes an existing GradeJabatan model.
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

    /**
     * Finds the GradeJabatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GradeJabatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GradeJabatan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
