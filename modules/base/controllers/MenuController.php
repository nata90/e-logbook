<?php

namespace app\modules\base\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\base\models\TbMenu;
use app\modules\base\models\TbMenuSearch;
use app\modules\base\models\AppGroupMenuSearch;
use app\modules\base\models\AppGroupMenu;
use app\modules\app\models\AppUserGroup;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * MenuController implements the CRUD actions for TbMenu model.
 */
class MenuController extends Controller
{
   /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['setgroupmenu','simpansetting','loadsetting','deletesetting'],
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
     * Lists all TbMenu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TbMenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSetgroupmenu(){
        $searchModel = new TbMenuSearch();
        $dataProvider = $searchModel->searchMenu(Yii::$app->request->queryParams);
        $listgroup = ArrayHelper::map(AppUserGroup::find()->all(),'id','nama_group');

        return $this->render('set_group_menu', [
            'model' => $searchModel,
            'dataProvider' => $dataProvider,
            'listgroup'=>$listgroup
        ]);
    }

    /**
     * Displays a single TbMenu model.
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
     * Creates a new TbMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TbMenu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TbMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TbMenu model.
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

    public function actionSimpansetting(){
        $group = $_GET['group'];
        $idmenu = $_GET['idmenu'];

        $rows['success'] = 0;
        $model = new AppGroupMenu;
        $model->id_group = $group;
        $model->id_menu = $idmenu;
        $model->active = 1;
        if($model->save()){
            $rows['success'] = 1;
            $rows['msg'] = 'Menu '.$model->menu->menu_name.' berhasil ditambahkan';
        } 

        echo Json::encode($rows);
    }

    public function actionDeletesetting(){
        $group = $_GET['group'];
        $idmenu = $_GET['idmenu'];

        $model = AppGroupMenu::find()->where(['id_group'=>$group, 'id_menu'=>$idmenu])->one();
        $rows['success'] = 0;
        if($model != null){
            if($model->delete()){
                $rows['success'] = 1;
                $rows['msg'] = 'Menu '.$model->menu->menu_name.' berhasil dihapus';
            }
        }

        echo Json::encode($rows);
    }

    public function actionLoadsetting(){
        $group = $_GET['group'];

        $model = AppGroupMenu::find()->where(['id_group'=>$group])->all();

        $list = ArrayHelper::map($model,'id','id_menu');
        $return['value'] = $list;

         echo Json::encode($return);
    }

    /**
     * Finds the TbMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbMenu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
