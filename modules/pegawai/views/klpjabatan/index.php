<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pegawai\models\KlpJabatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Kelompok Jabatan');
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Kelompok Jabatan</h3>
            </div>
            <div class="box-body">
                <?php Pjax::begin([
                            'id'=>'grid-klpjabatan',
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

                        //'id_klp_jabatan',
                        'kode_klp_jabatan',
                        'nama_klp_jabatan',
                        'deskripsi:ntext',
                        //'status_klp_jabatan',
                        [
                            'label'=>'Status',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->status_klp_jabatan == 1){
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/active.png" alt="Active">';
                                }else{
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/no-active.png" alt="No Active">';
                                }
                            },
                            'contentOptions' => ['class' => 'text-center'],
                            'filter'=>Html::activeDropDownList($searchModel, 'status_klp_jabatan',[0=>'Non Aktif',1=>'Aktif'],['class'=>'form-control','prompt'=>''])
                        ],
                        ['class' => 'yii\grid\ActionColumn','template'=>'{update}'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
