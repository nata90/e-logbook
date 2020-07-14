<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\modules\base\models\Backlog;
use yii\helpers\Json;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout','simpanbacklog'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSimpanbacklog(){
        $return['suceess'] = 0;

        $butir_keg = $_POST['butir_keg'];
        $deskripsi = $_POST['deskripsi'];
        $jumlah = $_POST['jumlah'];
        $rows = $_POST['rows'];

        $model = Backlog::find()->where(['tgl_entri'=>date('Y-m-d'), 'row'=>$rows])->one();

        if($model == null){
            $model = new Backlog;
        }
        
        $model->butir_kegiatan = $butir_keg;
        $model->deskripsi = $deskripsi;
        $model->tgl_entri = date('Y-m-d');
        $model->jumlah = $jumlah;
        $model->row = $rows;
        if($model->save()){
            $return['success'] = 1;
            $return['msg'] = '"'.$model->deskripsi.'" berhasil ditambahkan';
        }

        echo Json::encode($return);

    }

    public function actionDeletebacklog(){
        $row = $_POST['rows'];

        $model = Backlog::find()->where(['tgl_entri'=>date('Y-m-d'), 'row'=>$row])->one();
        $return['suceess'] = 0;
        if($model->delete()){
            $return['success'] = 1;
            $return['msg'] = '"'.$model->deskripsi.'" berhasil dihapus';
        }

        echo Json::encode($return);
    }
}
