<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
//use app\models\XmlParser;
use app\modules\base\models\Backlog;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\modules\logbook\models\KinerjaSearch;
use app\modules\app\models\AppUser;
use app\modules\logbook\models\Tugas;
use app\modules\pegawai\models\JabatanPegawai;
use app\modules\pegawai\models\JabatanPegawaiSearch;
use app\modules\pegawai\models\DataPegawai;
use app\modules\pegawai\models\Jabatan;
use app\modules\logbook\models\Kinerja;
use app\modules\base\models\TbMenu;
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
                'only' => ['logout','index','excelrekap','login','excellogbook'],
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
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        if($user->id_group == 2 || $user->id_group == 3 || $user->id_group == 4){ //kepala unit kerja
            $searchModel = new KinerjaSearch();
            $searchModel->range_date = date('m/d/Y').' - '.date('m/d/Y');
            $searchModel->id_pegawai = $user->pegawai_id;

            $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 1;
            $dataProvider2 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 0;
            $dataProvider3 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $dataProvider4 = $searchModel->searchHarikerja(Yii::$app->request->queryParams);

            $dataProvider5 = $searchModel->searchRekap(Yii::$app->request->queryParams);
            $total_rekap = 0;
            if($dataProvider5 != null){
                foreach($dataProvider5->models as $m)
                {

                   $total_rekap += $m->jumlah * $m->poin_kategori;;

                }
            }
            

            $total_logbook = $dataProvider->getCount();
            $approve_logbook = $dataProvider2->getCount();
            $notapprove_logbook = $dataProvider3->getCount();

            $hari_kerja = $dataProvider4->getCount();

            $search_staff = new JabatanPegawaiSearch();
            $search_staff->id_penilai = $user->pegawai_id;
            $search_staff->status_jbt = 1;
            $dataStaff = $search_staff->search(Yii::$app->request->queryParams);


            return $this->render('index_staff_kaunit', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'dataProvider5'=>$dataProvider5,
                'total_logbook' => $total_logbook,
                'approve_logbook'=> $approve_logbook,
                'notapprove_logbook'=> $notapprove_logbook,
                'hari_kerja'=>$hari_kerja,
                'dataStaff'=>$dataStaff,
                'total_rekap'=>$total_rekap
            ]);
        }else{
            $searchModel = new KinerjaSearch();
            $searchModel->range_date = date('m/d/Y').' - '.date('m/d/Y');
            $searchModel->id_pegawai = $user->pegawai_id;

            $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 1;
            $dataProvider2 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 0;
            $dataProvider3 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $dataProvider4 = $searchModel->searchHarikerja(Yii::$app->request->queryParams);

            $dataProvider5 = $searchModel->searchRekap(Yii::$app->request->queryParams);
            $total_rekap = 0;
            if($dataProvider5 != null){
                foreach($dataProvider5->models as $m)
                {

                   $total_rekap += $m->jumlah * $m->poin_kategori;;

                }
            }
            

            $total_logbook = $dataProvider->getCount();
            $approve_logbook = $dataProvider2->getCount();
            $notapprove_logbook = $dataProvider3->getCount();

            $hari_kerja = $dataProvider4->getCount();

            $search_staff = new JabatanPegawaiSearch();
            $search_staff->status_jbt = 1;
            $dataStaff = $search_staff->search(Yii::$app->request->queryParams);

            $list_jabatan = ArrayHelper::map(Jabatan::find()->orderBy('nama_jabatan ASC')->all(),'id_jabatan','nama_jabatan');

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'dataProvider5'=>$dataProvider5,
                'total_logbook' => $total_logbook,
                'approve_logbook'=> $approve_logbook,
                'notapprove_logbook'=> $notapprove_logbook,
                'hari_kerja'=>$hari_kerja,
                'dataStaff'=>$dataStaff,
                'total_rekap'=>$total_rekap,
                'search_staff'=>$search_staff,
                'list_jabatan'=>$list_jabatan
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
                        return round($model->poin_kategori,2);
                    },
                ],
                [
                    'label'=>'Total',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->jumlah * $model->poin_kategori;
                    },
                ],
                [],
                [],
                [],
            ],
        ]);

        $exporter->title = 'LAPORAN REKAP BACKLOG';
        $exporter->headerColumnUnions = 
        [
            [
                'header' => 'REKAP BACKLOG : '.strtoupper($nama_pegawai).' PERIODE : '.date('d/m/Y', strtotime($date_start)).' - '.date('d/m/Y', strtotime($date_end)),
                'offset' => 0,
                'length' => 7,
            ],
            
        ];

        return $exporter->send('rekap-backlog-'.$nama_pegawai.'.xls');
    }

    public function actionExcellogbook(){
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
                ->leftJoin('tugas', 'kinerja.id_tugas = tugas.id_tugas')
                ->andWhere(['kinerja.approval'=>1])
                ->andWhere(['kinerja.id_pegawai'=>$id_pegawai])
                ->andFilterWhere(['between', 'kinerja.tanggal_kinerja', $date_start, $date_end])
                ->orderBy('kinerja.tanggal_kinerja ASC')
            ]),
            'columns' => [
                [
                    'label'=>'Tanggal',
                    'format'=>'raw',
                    'value'=>function($model){
                        return date('d-m-Y', strtotime($model->tanggal_kinerja));
                    },
                ],
                [
                    'label'=>'Tugas',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->tugas->nama_tugas;
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
                    'label'=>'Deskripsi',
                    'format'=>'raw',
                    'value'=>function($model){
                        return $model->deskripsi;
                    },
                ],
                [],
                [],
                [],
            ],
        ]);

        $exporter->title = 'LAPORAN REKAP BACKLOG';
        $exporter->headerColumnUnions = 
        [
            [
                'header' => 'REKAP LOGBOOK : '.strtoupper($nama_pegawai).' PERIODE : '.date('d/m/Y', strtotime($date_start)).' - '.date('d/m/Y', strtotime($date_end)),
                'offset' => 0,
                'length' => 7,
            ],
            
        ];

        return $exporter->send('rekap-logbook-'.$nama_pegawai.'.xls');
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

    public function actionTes()
    {
        $model = DataPegawai::find()->limit(5000)->all();
        if($model != null){
            $no = 1;
            foreach ($model as $key => $value) {
                $new_model = new AppUser();
                $new_model->scenario = AppUser::SCENARIO_ADD;
                $new_model->username = $value->nip;
                $new_model->password = $value->pin;
                $new_model->active = 1;
                $new_model->pegawai_id = $value->id_pegawai;
                $new_model->accessToken = '-';
                $new_model->id_group = 2;
                $new_model->pegawai_nama = $value->nama;
                if(!$new_model->save()){
                    print_r($new_model->getErrors());
                }
            }
        }
        //echo Yii::$app->request->baseUrl;
        /*$fileName = '/var/www/html/elogbook/web/data/data-pegawai-sin.xls';
        $data = \moonland\phpexcel\Excel::import($fileName, [
            'setFirstRecordAsKeys' => true,  
            'setIndexSheetByName' => true, 
            'getOnlySheet' => 'sheet1', 
        ]);*/

        /*echo '<pre>';
        print_r($data);
        echo '</pre>';*/
        /*foreach($data as $key=>$val){
            $model = DataPegawai::find()->where(['nip'=>trim($val['nip'])])->one();

            if($model != null){
                $model->nama = ucfirst(trim($val['nama_pegawai']));
                $model->tmp_lahir = trim($val['tmp_lahir']);
                $model->tgl_lahir = date('Y-m-d', strtotime($val['tgl_lahir']));

                if($val['id_status_pegawai'] == 1){
                    $jenis_peg = 0;
                }else{
                    $jenis_peg = 1;
                }

                if($val['jk'] == 1){
                    $jk = 0;
                }else{
                    $jk = 1;
                }

                $model->jenis_peg = $jenis_peg;
                $model->status_peg = 0;
                $model->gender = $jk;
                $model->save(false);
            }
        }*/
        
    }


}
