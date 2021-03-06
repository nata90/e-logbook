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
use app\modules\pegawai\models\PegawaiUnitKerja;
use app\modules\pegawai\models\LogPresensiSearch;
use app\modules\pegawai\models\PegawaiPresensi;
use app\modules\pegawai\models\TugasTest;
use app\modules\logbook\models\Kinerja;
use app\modules\logbook\models\Target;
//use app\modules\logbook\models\Tugas;
use app\modules\base\models\TbMenu;
use yii2tech\spreadsheet\Spreadsheet;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use kartik\mpdf\Pdf;

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
                'only' => ['logout','index','excelrekap','login','excellogbook','pdfrekaplogbook','pdfstaff'],
                'denyCallback' => function ($rule, $action) {
                    if(Yii::$app->user->isGuest){
                        return $this->goHome();
                    }else{
                        throw new \yii\web\HttpException(403, 'You are not allowed to perform this action');
                    }
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
        $range_date = Kinerja::RangePeriodeIki();

        if($user->id_group == 2 || $user->id_group == 3 || $user->id_group == 4){ //kepala unit kerja

            $searchModel = new KinerjaSearch();
            $searchModel->range_date = $range_date;
            $searchModel->id_pegawai = $user->pegawai_id;

            $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 1;
            $dataProvider2 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 0;
            $dataProvider3 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $dataProvider4 = $searchModel->searchHarikerja(Yii::$app->request->queryParams);

            $dataProvider5 = $searchModel->searchRekap(Yii::$app->request->queryParams);
            $dataProvider6 = $searchModel->searchTugas(Yii::$app->request->queryParams);
            $total_rekap = 0;
            $total_jumlah = 0;
            if($dataProvider5 != null){
                foreach($dataProvider5->models as $m)
                {

                   $total_rekap += $m->jumlah * $m->poin_kategori;;
                   $total_jumlah = $m->jumlah + $total_jumlah;
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

            if($total_logbook == 0){
                $persen_logbook = 0;
            }else{
                $persen_logbook = round(($approve_logbook/$total_logbook)*100);
            }

            $jab_pegawai = JabatanPegawai::find()->where(['id_pegawai'=>$user->pegawai_id,'status_jbt'=>1])->one();
            $peg_unit_kerja = PegawaiUnitKerja::find()->where(['id_pegawai'=>$user->pegawai_id,'status_peg'=>1])->one();
            if($jab_pegawai != null && $peg_unit_kerja != null){
                $model_target = Target::find()->where(['id_jabatan'=>$jab_pegawai->id_jabatan,'id_unit_kerja'=>$peg_unit_kerja->id_unit_kerja, 'status_target'=>1])->one();
            }else{
                $model_target = null;
            }
            

            if($model_target != null){
                $target = $model_target->nilai_target;
            }else{
                $target = '-';
            }

            if($target != '-'){
                $persen_capaian = round(($total_rekap/$target)*100,2);
            }else{
                $persen_capaian = 0;
            }

            $searchModelPresensi = new LogPresensiSearch();
            if(isset($_GET['KinerjaSearch']['range_date']) && $_GET['KinerjaSearch']['range_date'] != ''){
                $searchModelPresensi->range_date = $_GET['KinerjaSearch']['range_date'];
            }else{
                $searchModelPresensi->range_date = $range_date;
            }
            
            $searchModelPresensi->pin = $user->pegawai->pin;
            $dataProviderPresensi = $searchModelPresensi->search(Yii::$app->request->queryParams);

            return $this->render('index_staff_kaunit', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'dataProvider5'=>$dataProvider5,
                'total_logbook' => $total_logbook,
                'approve_logbook'=> $approve_logbook,
                'notapprove_logbook'=> $notapprove_logbook,
                'hari_kerja'=>$hari_kerja,
                'dataStaff'=>$dataStaff,
                'total_rekap'=>$total_rekap,
                'persen_logbook'=>$persen_logbook,
                'target'=>$target,
                'persen_capaian'=>$persen_capaian,
                'user'=>$user,
                'jab_pegawai'=>$jab_pegawai,
                'peg_unit_kerja'=>$peg_unit_kerja,
                'dataProvider6'=>$dataProvider6,
                'total_jumlah'=>$total_jumlah,
                'dataProviderPresensi'=>$dataProviderPresensi
            ]);
        }else{

            $searchModel = new KinerjaSearch();
            $searchModel->range_date = $range_date;
            $searchModel->id_pegawai = $user->pegawai_id;

            $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 1;
            $dataProvider2 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $searchModel->approval = 0;
            $dataProvider3 = $searchModel->searchStaff(Yii::$app->request->queryParams);

            $dataProvider4 = $searchModel->searchHarikerja(Yii::$app->request->queryParams);

            $dataProvider5 = $searchModel->searchRekap(Yii::$app->request->queryParams);
            $dataProvider6 = $searchModel->searchTugas(Yii::$app->request->queryParams);
            $total_rekap = 0;
            $total_jumlah = 0;

            if($dataProvider5 != null){
                foreach($dataProvider5->models as $m)
                {

                   $total_rekap += $m->jumlah * $m->poin_kategori;
                   $total_jumlah = $m->jumlah + $total_jumlah;
                }
            }
            

            $total_logbook = $dataProvider->getCount();
            $approve_logbook = $dataProvider2->getCount();
            $notapprove_logbook = $dataProvider3->getCount();

            $hari_kerja = $dataProvider4->getCount();

            $search_staff = new JabatanPegawaiSearch();
            $search_staff->status_jbt = 1;
            $search_staff->id_penilai = $user->pegawai_id;
            $dataStaff = $search_staff->search(Yii::$app->request->queryParams);

            $list_jabatan = ArrayHelper::map(Jabatan::find()->orderBy('nama_jabatan ASC')->all(),'id_jabatan','nama_jabatan');

            if($total_logbook == 0){
                $persen_logbook = 0;
            }else{
                $persen_logbook = round(($approve_logbook/$total_logbook)*100);
            }

            $jab_pegawai = JabatanPegawai::find()->where(['id_pegawai'=>$user->pegawai_id,'status_jbt'=>1])->one();
            $peg_unit_kerja = PegawaiUnitKerja::find()->where(['id_pegawai'=>$user->pegawai_id,'status_peg'=>1])->one();
            if($jab_pegawai != null && $peg_unit_kerja != null){
                $model_target = Target::find()->where(['id_jabatan'=>$jab_pegawai->id_jabatan,'id_unit_kerja'=>$peg_unit_kerja->id_unit_kerja, 'status_target'=>1])->one();
            }else{
                $model_target = null;
            }
           

            if($model_target != null){
                $target = $model_target->nilai_target;
            }else{
                $target = '-';
            }

            if($target != '-'){
                $persen_capaian = round(($total_rekap/$target)*100,2);
            }else{
                $persen_capaian = 0;
            }


            $searchModelPresensi = new LogPresensiSearch();
            if(isset($_GET['KinerjaSearch']['range_date']) && $_GET['KinerjaSearch']['range_date'] != ''){
                $searchModelPresensi->range_date = $_GET['KinerjaSearch']['range_date'];
            }else{
                $searchModelPresensi->range_date = $range_date;
            }
            
            $searchModelPresensi->pin = $user->pegawai->pin;
            $dataProviderPresensi = $searchModelPresensi->search(Yii::$app->request->queryParams);

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
                'list_jabatan'=>$list_jabatan,
                'persen_logbook'=>$persen_logbook,
                'target'=>$target,
                'persen_capaian'=>$persen_capaian,
                'user'=>$user,
                'jab_pegawai'=>$jab_pegawai,
                'peg_unit_kerja'=>$peg_unit_kerja,
                'dataProvider6'=>$dataProvider6,
                'total_jumlah'=>$total_jumlah,
                'dataProviderPresensi'=>$dataProviderPresensi
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

    public function actionDefault(){
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }else{
            return $this->redirect(['site/login']);
        }
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
        
        //echo Yii::$app->request->baseUrl;
        $fileName = '/var/www/html/elogbook/web/data/TUGAS_KOMKORDIK_IGD.xls';
        $data = \moonland\phpexcel\Excel::import($fileName, [
            'setFirstRecordAsKeys' => true,  
            'setIndexSheetByName' => true, 
            'getOnlySheet' => 'sheet1', 
        ]);

        foreach($data as $key=>$val){

            $id_tugas = str_replace('.', '', $val['kode']);

            $model = Tugas::findOne($id_tugas);
            if($model == null){
                $model = new Tugas;
            }
            
            $model->id_tugas = $id_tugas;
            $model->id_kategori = $val['kategori'];
            $model->nama_tugas = trim(ucfirst($val['tugas']));
            $model->akses = 0;
            $model->status_tugas = 1;
            $model->id_unit_kerja = trim($val['unitkerja']);
            if($model->save()){
                
            }else{
                print_r($model->getErrors());
                exit();
            }
        }
        
    }

    public function actionLoadchart(){
        if(isset($_GET['rangedate']) && $_GET['rangedate'] != null){
            $range_date = $_GET['rangedate'];
        }else{
            $range_date = Kinerja::RangePeriodeIki();
        }
        
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $searchModel = new KinerjaSearch();
        $searchModel->range_date = $range_date;
        $searchModel->id_pegawai = $user->pegawai_id;

        $dataProvider = $searchModel->searchRekap(Yii::$app->request->queryParams);
        $models = $dataProvider->getModels();

        $arr_data = [];
        $arr_nama = [];
        if($models != null){
            foreach($models as $val){
                $arr_data[] = $val->jumlah;
                $arr_nama[] = $val->nama_kategori;
            }
        }

        $return['data'] = $arr_data;
        $return['kategori'] = $arr_nama;

        return Json::encode($return);
    }

    public function actionPdfrekaplogbook(){

        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);
        $penilai = JabatanPegawai::find()->where(['id_pegawai'=>$user->pegawai_id, 'status_jbt'=>1])->one();

        $session = new Session;
        $session->open();
        $range_date = $session['rangedate'];

        $searchModel = new KinerjaSearch();
        $searchModel->range_date = $range_date;
        $searchModel->id_pegawai = $user->pegawai_id;

        $explode = explode(' - ',$range_date);

        $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);
        $dataProvider_2 = $searchModel->searchTugas(Yii::$app->request->queryParams);

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('pdf_report',[
            'dataProvider'=>$dataProvider,
            'dataProvider_2'=>$dataProvider_2,
            'user'=>$user,
            'penilai'=>$penilai
        ]);
        
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}
            @media all{
                .page-break {display: none;}
            }
            @media print{
                .page-break{display: block;page-break-before: always;}
            }', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['URAIAN KEGIATAN HARIAN(LOGBOOK) PERIODE '.date('d/m/Y', strtotime($explode[0])).' - '.date('d/m/Y', strtotime($explode[1]))], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionPdfstaff(){

        $id_pegawai = Yii::$app->request->get('id', 0);
        $user = AppUser::find()->where(['pegawai_id'=>$id_pegawai])->one();

        $penilai = JabatanPegawai::find()->where(['id_pegawai'=>$id_pegawai, 'status_jbt'=>1])->one();

        $session = new Session;
        $session->open();
        $range_date = $session['rangedate'];

        $searchModel = new KinerjaSearch();
        $searchModel->range_date = $range_date;
        $searchModel->id_pegawai = $id_pegawai;

        $explode = explode(' - ',$range_date);

        $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);
        $dataProvider_2 = $searchModel->searchTugasstaff(Yii::$app->request->queryParams);

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('pdf_report',[
            'dataProvider'=>$dataProvider,
            'dataProvider_2'=>$dataProvider_2,
            'user'=>$user,
            'penilai'=>$penilai
        ]);
        
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}
            @media all{
                .page-break {display: none;}
            }
            @media print{
                .page-break{display: block;page-break-before: always;}
            }', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['URAIAN KEGIATAN HARIAN(LOGBOOK) PERIODE '.date('d/m/Y', strtotime($explode[0])).' - '.date('d/m/Y', strtotime($explode[1]))], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    public function actionRekappresensi(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $session = new Session;
        $session->open();
        $range_date = $session['rangedate'];


        $id_peg = Yii::$app->request->get('id', 0);
        $model_peg = DataPegawai::find()->where(['id_pegawai'=>$id_peg])->one();

        $searchModelPresensi = new LogPresensiSearch();
        $searchModelPresensi->range_date = $range_date;

        $searchModelPresensi->pin = $model_peg->pin;
        $dataProviderPresensi = $searchModelPresensi->search(Yii::$app->request->queryParams);

        $return['title'] = 'Presensi '.$model_peg->nama;
        $return['html'] = $this->renderPartial('rekap_presensi',[
            'dataProviderPresensi'=>$dataProviderPresensi
        ]);

        return $return;      
    }

    public function actionError(){
        $error = Yii::app()->errorHandler->error;
        if ($error)
            $this->render('error', array('error'=>$error));
        else
            throw new CHttpException(404, 'Page not found.');
    }

    public function actionSinkronpegawai(){
        $model = PegawaiPresensi::find()->all();

        if($model != null){
            foreach($model as $val){
                $cek_nip = DataPegawai::find()->where(['nip'=>$val->pegawai_nip])->one();
                $cek_pin = DataPegawai::find()->where(['pin'=>$val->pegawai_pin])->one();

                if($cek_nip == null && $cek_pin == null){
                    $connection = \Yii::$app->db;

                    $transaction = $connection->beginTransaction();
                    try {
                        $new_pegawai = new DataPegawai;
                        $new_pegawai->nip = $val->pegawai_nip;
                        $new_pegawai->pin = $val->pegawai_pin;
                        $new_pegawai->nama = $val->pegawai_nama;
                        $new_pegawai->tmp_lahir = '-';
                        $new_pegawai->tgl_lahir = date('Y-m-d');
                        $new_pegawai->jenis_peg = 0;
                        $new_pegawai->status_peg = 0;

                        if($val->gender == 1){
                            $gender = 0;
                        }else{
                            $gender = 1;
                        }
                        $new_pegawai->gender = $gender;
                        $new_pegawai->email = '-';
                        if($new_pegawai->save()){
                            $model_user = new AppUser();
                            $model_user->scenario = AppUser::SCENARIO_ADD;
                            $model_user->username = $new_pegawai->nip;
                            $model_user->password = $new_pegawai->pin;
                            $model_user->pegawai_id = $new_pegawai->id_pegawai;
                            $model_user->pegawai_nama = $new_pegawai->nama;
                            $model_user->accessToken = '-';
                            $model_user->id_group = 2;
                            $model_user->active = 1;
                            $model_user->photo_profile = '-';
                            if($model_user->save()){
                                $transaction->commit();
                            }
                        }
                    } catch (\Exception $e) {
                        
                        $transaction->rollBack();
                        throw $e;
                    }
                }
                
            }
        }
        file_put_contents('/var/www/html/elogbook/log/cron_update_pegawai.txt','running');
    }


    public function actionSinkrontugas(){
        $model_tugas_test = TugasTest::find()->where(['id_unit_kerja'=>'02401'])->all();

        foreach($model_tugas_test as $val){
            echo $val->id_kategori.'----'.$val->nama_tugas.'<br/>';

            $model = Tugas::find()->where(['id_tugas'=>$val->id_tugas])->one();

            if($model != null){
                $model->id_kategori = $val->id_kategori;
                $model->save(false);
            }
        }
    }
}
