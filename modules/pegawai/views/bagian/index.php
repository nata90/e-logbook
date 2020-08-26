<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pegawai\models\BagianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Bagian');
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Bagian</h3>
            </div>
            <div class="box-body">

                <?php Pjax::begin([
                            'id'=>'grid-bagian',
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

                        //'id_bagian',
                        //'id_direktorat',
                        [
                            'label'=>'ID Bagian',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->id_bagian;
                            },
                        ],
                        [
                            'label'=>'Direktorat',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->direktorat->nama_direktorat;
                            },
                            'filter'=>Html::activeDropDownList($searchModel, 'id_direktorat',$listData,['class'=>'form-control','prompt'=>''])
                        ],
                        'nama_bagian',
                        [
                            'label'=>'Status',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->status == 1){
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/active.png" alt="Active">';
                                }else{
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/no-active.png" alt="No Active">';
                                }
                            },
                            'contentOptions' => ['class' => 'text-center'],
                            'filter'=>Html::activeDropDownList($searchModel, 'status',[0=>'Non Aktif',1=>'Aktif'],['class'=>'form-control','prompt'=>''])
                        ],
                        [
                            'label'=>'Tanggal Aktif',
                            'format'=>'raw',
                            'value'=>function($model){
                                return date('d-m-Y', strtotime($model->tmt_aktif));
                            },
                        ],

                        //['class' => 'yii\grid\ActionColumn'],
                        ['class' => 'yii\grid\ActionColumn','template'=>'{update}'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
