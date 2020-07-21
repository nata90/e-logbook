<?php

namespace app\modules\pegawai\controllers;

use Yii;
use app\modules\pegawai\models\Jabatan;
use app\modules\pegawai\models\JabatanSearch;
use app\modules\pegawai\models\GradeJabatan;
use app\modules\pegawai\models\KlpJabatan;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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
