<?php

namespace app\controllers;

use sizeg\jwt\Jwt;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\rest\Controller;
use app\modules\app\models\AppUser;
use app\modules\logbook\models\Tugas;
use app\modules\logbook\models\Kinerja;
use app\modules\logbook\models\KinerjaSearch;
use app\modules\logbook\models\Target;
use app\modules\pegawai\models\PegawaiUnitKerja;
use app\modules\pegawai\models\DataPegawai;
use app\modules\pegawai\models\JabatanPegawai;
use yii\web\Response;
use yii\helpers\ArrayHelper;

class RestController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => [
                'login'
            ],
        ];

        return $behaviors;
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogin()
    {
        $username = Yii::$app->request->post('username','');
        $password = Yii::$app->request->post('password','');

        if($username == '' || $password == ''){
            return $this->asJson([
                'msg' => 'Parameter username dan password tidak boleh kosong',
            ]);
        }

        $user = AppUser::findByUsername($username);

        if (!$user || !$user->validatePassword($password,$user->authkey)) {
            return $this->asJson([
                'msg' => 'Incorrect username or password.',
            ]);
        }else{
            $jwt = Yii::$app->jwt;
            $signer = $jwt->getSigner('HS256');
            $key = $jwt->getKey();
            $time = time();

            $token = $jwt->getBuilder()
                ->issuedBy('http://example.com')
                ->permittedFor('http://example.org')
                ->identifiedBy('4f1g23a12aa', true)
                ->issuedAt($time)
                ->expiresAt($time + 3600)
                ->withClaim('uid', 100)
                ->getToken($signer, $key);

            return $this->asJson([
                'token' => (string)$token,
            ]);
        }

        
    }

    /**
     * @return \yii\web\Response
     */
    public function actionData()
    {
        
       return $this->asJson([
            'success' => 'OK',
        ]);
    }

    public function actionDatatugas($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = Tugas::find()->where(['id_unit_kerja'=>$id])->orderBy('nama_tugas ASC')->all();

        $arr_json = array();
        if($model != null){
            foreach($model as $val){
                $arr_json['data'][] = ['id'=>$val->id_tugas, 'tugas'=>$val->nama_tugas];
            }
        }

        return $arr_json;
    }

    public function actionGetrekap($id, $date){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        

        if(isset($date) && $date != ''){
            $date = date('Y-m-d', strtotime($date));
        }else{
            $date = date('Y-m-d');
        }

        $model = Kinerja::find()->where(['id_pegawai'=>$id, 'tanggal_kinerja'=>$date])->orderBy('id_kinerja DESC')->all();

        $arr_json = array();

        if($model != null){
            foreach ($model as $key => $value) {
                if($value->approval == 1){
                    $status = 'approved';
                }else{
                    $status = 'not approve';
                }
                $arr_json['data'][] = ['tugas'=>$value->deskripsi, 'jumlah'=>$value->jumlah, 'status'=>$status];
            }
        }else{
            $arr_json['data'][] = ['tugas'=>'-', 'jumlah'=>'0', 'status'=>'-'];
        }

        return $arr_json;
    }

    public function actionProfile($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $arr_json = array();

        $data_pegawai = DataPegawai::findOne($id);

        $jab_pegawai = JabatanPegawai::find()->where(['id_pegawai'=>$id,'status_jbt'=>1])->one();
        $peg_unit_kerja = PegawaiUnitKerja::find()->where(['id_pegawai'=>$id,'status_peg'=>1])->one();
        $model_target = Target::find()->where(['id_jabatan'=>$jab_pegawai->id_jabatan,'id_unit_kerja'=>$peg_unit_kerja->id_unit_kerja, 'status_target'=>1])->one();

        $range_date = Kinerja::RangePeriodeIki();

        $date = explode('-',$range_date);

        $searchModel = new KinerjaSearch();
        $searchModel->range_date = $range_date;
        $searchModel->id_pegawai = $id;

        $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);

        $searchModel->approval = 1;
        $dataProvider2 = $searchModel->searchStaff(Yii::$app->request->queryParams);

        $searchModel->approval = 0;
        $dataProvider3 = $searchModel->searchStaff(Yii::$app->request->queryParams);

        $dataProvider4 = $searchModel->searchHarikerja(Yii::$app->request->queryParams);

        
        $total_rekap = Yii::$app->db->createCommand('SELECT SUM(b.`poin_kategori`*t.`jumlah`) AS poin FROM `kinerja` AS  t 
            LEFT JOIN tugas AS a ON t.`id_tugas` = a.`id_tugas`
            LEFT JOIN `kategori` AS b ON a.`id_kategori` = b.`id_kategori`
            WHERE t.id_pegawai = '.$id.' AND t.approval = 1 AND tanggal_kinerja BETWEEN "'.date('Y-m-d', strtotime(trim($date[0]))).'" AND "'.date('Y-m-d', strtotime(trim($date[1]))).'"')
             ->queryScalar();

        $total_logbook = $dataProvider->getCount();
        $approve_logbook = $dataProvider2->getCount();
        $notapprove_logbook = $dataProvider3->getCount();

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

        $arr_json['data'][] = ['value'=>'NIP / NIK : '.$data_pegawai->nip];
        $arr_json['data'][] = ['value'=>'Jabatan : '.$jab_pegawai->jabatan->nama_jabatan];
        $arr_json['data'][] = ['value'=>'Unit Kerja : '.$peg_unit_kerja->unitKerja->nama_unit_kerja];
        $arr_json['data'][] = ['value'=>'Penilai : '.$jab_pegawai->penilai->nama];
        $arr_json['data'][] = ['value'=>'Periode : '.$range_date];
        $arr_json['data'][] = ['value'=>'Logbook Disetujui : '.$approve_logbook];
        $arr_json['data'][] = ['value'=>'Logbook belum disetujui : '.$notapprove_logbook];
        $arr_json['data'][] = ['value'=>'Target Poin : '.$target];
        $arr_json['data'][] = ['value'=>'Capaian Poin : '.round($total_rekap,2)];
        $arr_json['data'][] = ['value'=>'Persen Capaian : '.$persen_capaian.'%'];

        return $arr_json;
    }

    public function actionSimpankinerja(){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $return = array();

        $return['error'] = 1;
        $return['msg'] = 'Kinerja gagal disimpan';

        if(Yii::$app->request->post()){

            $tugas = $_POST['tugas'];
            $deskripsi = $_POST['deskripsi'];
            $jumlah = $_POST['jumlah'];
            $pegawai_id = $_POST['idpeg'];
            $tanggal = $_POST['tanggal'];

            $explode = explode('|',$tugas);

            $lastmodel = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d', strtotime($tanggal)), 'id_pegawai'=>$pegawai_id])->orderBy('row DESC')->one();
            if($lastmodel != null){
                $row = $lastmodel->row +1;
            }else{
                $row = 0;
            }

            $jabatan_pegawai = JabatanPegawai::find()->where(['id_pegawai'=>$id, 'status_jbt'=>1])->one();

            if($jabatan_pegawai != null){
                $penilai = $jabatan_pegawai->id_penilai;
            }else{
                $penilai = 1178;
            }

            $model = new Kinerja;
            $model->tanggal_kinerja = date('Y-m-d', strtotime($tanggal));
            $model->id_pegawai = $pegawai_id;
            $model->id_tugas = trim($explode[0]);
            $model->jumlah = $jumlah;
            $model->deskripsi = $deskripsi;
            $model->approval = 1;
            $model->user_approval = $penilai;
            $model->tgl_approval = date('Y-m-d');
            $model->create_date = date('Y-m-d');
            $model->row = $row;
            if($model->save()){
                $return['error'] = 0;
                $return['msg'] = 'Kinerja berhasil disimpan';
            }else{
                $return['error'] = 1;
                $return['msg'] = 'Kinerja gagal disimpan';
            }
        }

        return $return;
    }
}