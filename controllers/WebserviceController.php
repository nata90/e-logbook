<?php 
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\modules\logbook\models\Tugas;

class WebserviceController extends Controller
{
	public function actionDatatugas($id)
    {

    	$model = Tugas::find()->where(['id_unit_kerja'=>$id])->all();

    	$arr_json = array();
    	if($model != null){
    		foreach($model as $val){
    			$arr_json[] = ['id'=>$val->id_tugas, 'tugas'=>$val->nama_tugas];
    		}
    	}

    	echo Json::encode($arr_json);
    }
}
?>