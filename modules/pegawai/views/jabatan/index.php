<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\pegawai\models\Jabatan;
use app\modules\logbook\models\Target;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pegawai\models\JabatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Jabatan');

$this->registerJs(<<<JS
    
JS
);
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Daftar Jabatan</h3>
            </div>
            <div class="box-body">
                <?php Pjax::begin([
                    'id'=>'grid-jabatan',
                    'timeout'=>false,
                    'enablePushState'=>false,
                    'clientOptions'=>['method'=>'GET'],
                    'options'=>[
                        'class'=>'yii-gridview',
                    ],
                ]); ?>
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'id_jabatan',
                        //'id_grade',
                        [
                            'label'=>'Kelompok Jabatan',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->grade->klpjabatan->nama_klp_jabatan;
                            },
                            'filter'=>Html::activeDropDownList($searchModel, 'id_klp_jabatan', $list_klp_jabatan, ['class'=>'form-control','prompt'=>''])
                        ],
                        [
                            'label'=>'Grade',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->grade->kode_grade;
                            },
                            'filter'=>Html::activeDropDownList($searchModel, 'id_grade', $list_grade, ['class'=>'form-control','prompt'=>''])
                        ],
                        'nama_jabatan',
                        [
                            'label'=>'Level Jabatan',
                            'format'=>'raw',
                            'value'=>function($model){
                                return Jabatan::getLevelJabatan($model->level_jabatan);
                            },
                            'filter'=>Html::activeDropDownList($searchModel, 'level_jabatan',Jabatan::getLevelJabatan(),['class'=>'form-control','prompt'=>''])
                        ],
                        [
                            'label'=>'Peer Group',
                            'format'=>'raw',
                            'value'=>function($model){
                                return Jabatan::getPeerGroup($model->peer_grup);
                            },
                            'filter'=>Html::activeDropDownList($searchModel, 'peer_grup',Jabatan::getPeerGroup(),['class'=>'form-control','prompt'=>''])
                        ],
                        [
                            'label'=>'Target',
                            'format'=>'raw',
                            'value'=>function($model){
                                $html = '<a href="'.Url::to(['jabatan/listtarget', 'id'=>$model->id_jabatan]).'" class="btn bg-maroon btn-flat margin btn-xs set-target">TARGET</a>';

                                return $html;
                            },
                        ],
                        [
                            'label'=>'status',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->status_jabatan == 1){
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/active.png" alt="Active">';
                                }else{
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/no-active.png" alt="No Active">';
                                }
                            },
                            'filter'=>Html::activeDropDownList($searchModel, 'status_jabatan',[0=>'Non Aktif',1=>'Aktif'],['class'=>'form-control','prompt'=>''])
                        ],
                        
                        /*[
                            'label'=>'Tanggal Mulai Jabatan',
                            'format'=>'raw',
                            'value'=>function($model){
                                return date('d-m-Y', strtotime($model->tmt_jabatan));
                            },
                        ],*/

                        ['class' => 'yii\grid\ActionColumn','template'=>'{update}'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
        

    </div>
</div>
