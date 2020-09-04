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
use app\modules\pegawai\models\PegawaiUnitKerja;
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

            $explode = explode('|',$tugas);

            $lastmodel = Kinerja::find()->where(['tanggal_kinerja'=>date('Y-m-d'), 'id_pegawai'=>$pegawai_id])->orderBy('row DESC')->one();
            if($lastmodel != null){
                $row = $lastmodel->row +1;
            }else{
                $row = 0;
            }

            $model = new Kinerja;
            $model->tanggal_kinerja = date('Y-m-d');
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