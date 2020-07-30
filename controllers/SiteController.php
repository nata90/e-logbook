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
use yii\helpers\ArrayHelper;
use app\modules\logbook\models\KinerjaSearch;
use app\modules\app\models\AppUser;
use app\modules\logbook\models\Tugas;
use app\modules\pegawai\models\JabatanPegawai;
use app\modules\pegawai\models\JabatanPegawaiSearch;
use app\modules\pegawai\models\DataPegawai;
use app\modules\logbook\models\Kinerja;
use yii2tech\spreadsheet\Spreadsheet;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\Session;

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
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
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
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);


        /*if($user->id_group == 2 || $user->id_group == 3){ //grup staff & admin unit kerja
            $searchModel = new KinerjaSearch();
            $searchModel->range_date = date('m/d/Y').' - '.date('m/d/Y');
            $searchModel->id_pegawai = $user->pegawai_id;

            $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 1;
            $dataProvider2 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 0;
            $dataProvider3 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $dataProvider4 = $searchModel->searchHarikerja(Yii::$app->request->queryParams);

            $total_logbook = $dataProvider->getCount();
            $approve_logbook = $dataProvider2->getCount();
            $notapprove_logbook = $dataProvider3->getCount();

            $hari_kerja = $dataProvider4->getCount();


            return $this->render('index_staff_admin', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'total_logbook' => $total_logbook,
                'approve_logbook'=> $approve_logbook,
                'notapprove_logbook'=> $notapprove_logbook,
                'hari_kerja'=>$hari_kerja
            ]);
        }else*/if($user->id_group == 2 || $user->id_group == 3 || $user->id_group == 4){ //kepala unit kerja
            $searchModel = new KinerjaSearch();
            $searchModel->range_date = date('m/d/Y').' - '.date('m/d/Y');
            $searchModel->id_pegawai = $user->pegawai_id;

            $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 1;
            $dataProvider2 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 0;
            $dataProvider3 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $dataProvider4 = $searchModel->searchHarikerja(Yii::$app->request->queryParams);

            $total_logbook = $dataProvider->getCount();
            $approve_logbook = $dataProvider2->getCount();
            $notapprove_logbook = $dataProvider3->getCount();

            $hari_kerja = $dataProvider4->getCount();

            $search_staff = new JabatanPegawaiSearch();
            $search_staff->id_penilai = $user->pegawai_id;
            $dataStaff = $search_staff->search(Yii::$app->request->queryParams);


            return $this->render('index_staff_kaunit', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'total_logbook' => $total_logbook,
                'approve_logbook'=> $approve_logbook,
                'notapprove_logbook'=> $notapprove_logbook,
                'hari_kerja'=>$hari_kerja,
                'dataStaff'=>$dataStaff
            ]);
        }else{
            $searchModel = new KinerjaSearch();
            $searchModel->range_date = date('m/d/Y').' - '.date('m/d/Y');

            $list_pegawai_dinilai = JabatanPegawai::find()
            ->select(['data_pegawai.id_pegawai','data_pegawai.nama'])
            ->leftJoin('data_pegawai','jabatan_pegawai.id_pegawai = data_pegawai.id_pegawai')
            ->where(['jabatan_pegawai.id_penilai'=>$user->pegawai_id, 'jabatan_pegawai.status_jbt'=>1])
            ->orderBy('data_pegawai.nama ASC')
            ->all();

            if($list_pegawai_dinilai != null){
                $listPegawai = ArrayHelper::map($list_pegawai_dinilai,'id_pegawai','id_pegawai');

                $searchModel->list_pegawai = $listPegawai;
            }
            

            $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);


            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
        }
        
    }

    public function actionExcelrekap(){
        $session = new Session;
        $session->open();
        $range_date = $session['rangedate'];
        if (isset($_GET['id'])) {
            $pegawai = DataPegawai::findOne($_GET['id']);
            $id_pegawai = $_GET['id'];
            $nama_pegawai = $pegawai->nama;
        }else{
            $id_user = Yii::$app->user->id;
            $user = AppUser::findOne($id_user);
            $id_pegawai = $user->pegawai_id;
            $nama_pegawai = $user->pegawai->nama;
        }
        

        $explode = explode('-',$range_date);
        $date_start = date('Y-m-d', strtotime(trim($explode[0])));
        $date_end = date('Y-m-d', strtotime(trim($explode[1])));

        $exporter = new Spreadsheet([
            'dataProvider' => new ActiveDataProvider([
                'query' => Kinerja::find()
                ->select(['kategori.nama_kategori AS nama_kategori','SUM(kinerja.jumlah) AS jumlah', 'kategori.poin_kategori AS poin_kategori'])
                ->leftJoin('tugas', 'kinerja.id_tugas = tugas.id_tugas')
                ->leftJoin('kategori', 'tugas.id_kategori = kategori.id_kategori')
                ->andWhere(['kinerja.approval'=>1])
                ->andWhere(['kinerja.id_pegawai'=>$id_pegawai])
                ->andFilterWhere(['between', 'tanggal_kinerja', $date_start, $date_end])
                ->groupBy('tugas.id_kategori')
            ]),
            'columns' => [
                [
                    'label'=>'Kategori',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->nama_kategori;
                    },
                ],
                [
                    'label'=>'Jumlah',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->jumlah;
                    },
                ],
                [
                    'label'=>'Poin',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->poin_kategori;
                    },
                ],
                [
                    'label'=>'Total',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->jumlah * $model->poin_kategori;
                    },
                ],
            ],
        ]);

        $exporter->title = 'LAPORAN REKAP BACKLOG';
        $exporter->headerColumnUnions = 
        [
            [
                'header' => 'Rekap backlog '.$nama_pegawai.' '.date('d/m/Y', strtotime($date_start)).' - '.date('d/m/Y', strtotime($date_end)),
                'offset' => 0,
                'length' => 4,
            ]
        ];

        return $exporter->send('rekap-backlog-'.$nama_pegawai.'.xls');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //return $this->goBack();
            return $this->redirect(['site/index']);
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

        return $this->redirect(['site/login']);
        //return $this->goHome();
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
