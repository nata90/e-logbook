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
use app\modules\app\models\AppSetting;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Html;

/**
 * UserController implements the CRUD actions for AppUser model.
 */
class SettingController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['settingapp'],
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


    public function actionSettingapp(){
        $model = AppSetting::findOne(1);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Setting aplikasi berhasil diupdate");

            return $this->redirect(['settingapp']);
        }

        return $this->render('setting_app', [
            'model' => $model
        ]);
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
