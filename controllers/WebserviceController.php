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
    	$model = Tugas::find()->where(['id_unit_kerja'=>$id])->all();

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

            $explode = explode('|',$tugas);

            $model = new Kinerja;
            $model->tanggal_kinerja = date('Y-m-d');
            $model->id_pegawai = 3;
            $model->id_tugas = trim($explode[0]);
            $model->jumlah = $jumlah;
            $model->deskripsi = $deskripsi;
            $model->approval = 0;
            $model->create_date = date('Y-m-d');
            $model->row = 0;
            if($model->save()){
                $return['error'] = 0;
                $return['msg'] = 'Kinerja berhasil disimpan';
            }
        }

        echo Json::encode($return);
    }
}
?>