<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\modules\pegawai\models\UnitKerja;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\logbook\models\TugasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tugas');
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Unit Kerja</h3>
            </div>
            <div class="box-body">

                <?php Pjax::begin([
                            'id'=>'grid-tugas',
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

                        'id_tugas',
                        'nama_tugas',
                        [
                            'label'=>'Unit Kerja',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->unitkerja->nama_unit_kerja;
                            },
                            'filter'=>Html::activeDropDownList($searchModel, 'id_unit_kerja',$list_unit_kerja,['class'=>'form-control','prompt'=>''])
                        ],
                        [
                            'label'=>'Kategori',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->kategori->nama_kategori;
                            },
                            'filter'=>Html::activeDropDownList($searchModel, 'id_kategori',$list_kategori,['class'=>'form-control','prompt'=>''])
                        ],
                        //'id_kategori',
                        [
                            'label'=>'Akses',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->akses == 0){
                                    return 'Tertutup';
                                }else{
                                    return 'Terbuka';
                                }
                            },
                            'filter'=>Html::activeDropDownList($searchModel, 'akses',[0=>'Tertutup',1=>'Terbuka'],['class'=>'form-control','prompt'=>''])
                        ],
                        [
                            'label'=>'Status Tugas',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->status_tugas == 1){
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/active.png" alt="Active">';
                                }else{
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/no-active.png" alt="No Active">';
                                }
                            },
                            'contentOptions' => ['class' => 'text-center'],
                            //'filter'=>Html::activeDropDownList($searchModel, 'status_tugas',[0=>'Non Aktif',1=>'Aktif'],['class'=>'form-control','prompt'=>''])
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{update}&nbsp;{delete}',
                        ],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
        

    </div>
</div>

