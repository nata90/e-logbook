<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pegawai\models\DirektoratSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Direktorat');
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Direktorat</h3>
            </div>
            <div class="box-body">
                <?php Pjax::begin([
                            'id'=>'grid-direktorat',
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

                        'id_direktorat',
                        'nama_direktorat',
                        //'status',
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
                        //'tmt_aktif',
                        [
                            'label'=>'Tanggal Aktif',
                            'format'=>'raw',
                            'value'=>function($model){
                                return date('d-m-Y', strtotime($model->tmt_aktif));
                            },
                        ],

                        //['class' => 'yii\grid\ActionColumn','template'=>'{update}&nbsp;{delete}'],
                        ['class' => 'yii\grid\ActionColumn','template'=>'{update}'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>

