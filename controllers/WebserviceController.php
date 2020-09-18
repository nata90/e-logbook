<?php 
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\modules\logbook\models\Tugas;
use app\modules\logbook\models\Kinerja;
use app\modules\logbook\models\KinerjaSearch;
use app\modules\logbook\models\Target;
use app\modules\pegawai\models\PegawaiUnitKerja;
use app\modules\pegawai\models\DataPegawai;
use app\modules\pegawai\models\JabatanPegawai;
use app\modules\app\models\AppUser;
use app\models\LoginForm;

class WebserviceController extends Controller
{
     public $enableCsrfValidation = false;

    protected function verbs()
    {
       return [
           'datatugas'=>['GET'],
           'simpankinerja' => ['POST'],
       ];
    }

	public function actionDatatugas($id)
    {
    	$model = Tugas::find()->where(['id_unit_kerja'=>$id])->orderBy('nama_tugas ASC')->all();

    	$arr_json = array();
    	if($model != null){
    		foreach($model as $val){
    			$arr_json['data'][] = ['id'=>$val->id_tugas, 'tugas'=>$val->nama_tugas];
    		}
    	}

    	echo Json::encode($arr_json);
    }

    public function actionGetrekap(){
        $id = $_GET['id'];
        $date = $_GET['date'];

        if(isset($_GET['date']) && $_GET['date'] != ''){
            $date = date('Y-m-d', strtotime($_GET['date']));
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
                $arr_json['data'][] = ['tugas'=>$value->tugas->nama_tugas, 'jumlah'=>$value->jumlah, 'status'=>$status];
            }
        }else{
            $arr_json['data'][] = ['tugas'=>'-', 'jumlah'=>'0', 'status'=>'-'];
        }

        return Json::encode($arr_json);
    }

    public function actionProfile($id){
        $arr_json = array();

        $data_pegawai = DataPegawai::findOne($id);

        $jab_pegawai = JabatanPegawai::find()->where(['id_pegawai'=>$id,'status_jbt'=>1])->one();
        $peg_unit_kerja = PegawaiUnitKerja::find()->where(['id_pegawai'=>$id,'status_peg'=>1])->one();
        $model_target = Target::find()->where(['id_jabatan'=>$jab_pegawai->id_jabatan,'id_unit_kerja'=>$peg_unit_kerja->id_unit_kerja, 'status_target'=>1])->one();

        $range_date = Kinerja::RangePeriodeIki();

        $searchModel = new KinerjaSearch();
        $searchModel->range_date = $range_date;
        $searchModel->id_pegawai = $id;

        $dataProvider = $searchModel->searchStaff(Yii::$app->request->queryParams);

        $searchModel->approval = 1;
        $dataProvider2 = $searchModel->searchStaff(Yii::$app->request->queryParams);

        $searchModel->approval = 0;
        $dataProvider3 = $searchModel->searchStaff(Yii::$app->request->queryParams);

        $dataProvider4 = $searchModel->searchHarikerja(Yii::$app->request->queryParams);

        /*$dataProvider5 = $searchModel->searchRekap(Yii::$app->request->queryParams);
        $total_rekap = 0;
        if($dataProvider5->models != null){
            foreach($dataProvider5->models as $m)
            {

               $total_rekap += $m->jumlah * $m->poin_kategori;;

            }
        }*/
        $total_rekap = Yii::$app->db->createCommand('SELECT SUM(b.`poin_kategori`*t.`jumlah`) AS poin FROM `kinerja` AS  t 
            LEFT JOIN tugas AS a ON t.`id_tugas` = a.`id_tugas`
            LEFT JOIN `kategori` AS b ON a.`id_kategori` = b.`id_kategori`
            WHERE t.id_pegawai = '.$id.' AND t.approval = 1')
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

        echo Json::encode($arr_json);
    }

    public function actionSimpankinerja(){
        //header('Content-Type: application/json');

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

            $model = new Kinerja;
            $model->tanggal_kinerja = date('Y-m-d', strtotime($tanggal));
            $model->id_pegawai = $pegawai_id;
            $model->id_tugas = trim($explode[0]);
            $model->jumlah = $jumlah;
            $model->deskripsi = $deskripsi;
            $model->approval = 0;
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

        echo Json::encode($return);
    }


    public function actionLogin(){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $return = array();

        $model = new LoginForm();
        $model->username = $username;
        $model->password = $password;
        if ($model->login()) {
            $user = AppUser::find()->where(['username'=>$model->username])->one();
            $peg_unit_kerja = PegawaiUnitKerja::find()->where(['id_pegawai'=>$user->pegawai_id, 'status_peg'=>1])->one();
            echo $user->pegawai->nama.'#'.$user->pegawai_id.'#'.$peg_unit_kerja->id_unit_kerja;
        }else{
            echo 'gagal';
        }

    }
}
?>