<?php

namespace app\modules\pegawai\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\base\models\TbMenu;
use app\modules\pegawai\models\KlpJabatan;
use app\modules\pegawai\models\KlpJabatanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KlpjabatanController implements the CRUD actions for KlpJabatan model.
 */
class KlpjabatanController extends Controller
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
     * Lists all KlpJabatan models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KlpJabatanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KlpJabatan model.
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
     * Creates a new KlpJabatan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KlpJabatan();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "kelompok jabatan ".$model->nama_klp_jabatan." berhasil ditambahkan");
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KlpJabatan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "kelompok jabatan ".$model->nama_klp_jabatan." berhasil diupdate");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing KlpJabatan model.
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
     * Finds the KlpJabatan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KlpJabatan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KlpJabatan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
