<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pegawai\models\UnitKerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Unit Kerja');
$this->registerJs(<<<JS
    
JS
);
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Unit Kerja</h3>
            </div>
            <div class="box-body">
                <?php Pjax::begin([
                            'id'=>'grid-unit-kerja',
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
                    'filterModel' => $filter,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id_unit_kerja',
                        //'id_bagian',
                        'nama_unit_kerja',
                        [
                            'label'=>'Bagian',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->bagian->nama_bagian;
                            }
                        ],
                        [
                            'label'=>'Direktorat',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->bagian->direktorat->nama_direktorat;
                            }
                        ],
                        //'status_unit',
                        [
                            'label'=>'Status Unit',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->status_unit == 1){
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/active.png" alt="Active">';
                                }else{
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/no-active.png" alt="No Active">';
                                }
                            },
                            'contentOptions' => ['class' => 'text-center'],
                            'filter'=>Html::activeDropDownList($searchModel, 'status_unit',[0=>'Non Aktif',1=>'Aktif'],['class'=>'form-control','prompt'=>''])
                        ],
                        [
                            'label'=>'Tanggal Aktif',
                            'format'=>'raw',
                            'value'=>function($model){
                                return date('d-m-Y', strtotime($model->tmt_aktif));
                            },
                        ],
                        [
                            'label'=>'',
                            'format'=>'raw',
                            'value'=>function($model){
                                return '<a class="btn bg-purple margin btn-flat btn-xs" href="'.Url::to(['unitkerja/listpegawai', 'id'=>$model->id_unit_kerja]).'">Daftar Pegawai</a>';
                            },
                        ],
                        ['class' => 'yii\grid\ActionColumn','template'=>'{update}'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
        

    </div>
</div>

