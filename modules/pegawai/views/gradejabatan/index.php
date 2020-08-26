<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pegawai\models\GradeJabatanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Grade Jabatan');
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Grade Jabatan</h3>
            </div>
            <div class="box-body">
                <?php Pjax::begin([
                            'id'=>'grid-gradejabatan',
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

                        //'id_grade',
                        [
                            'label'=>'Kelompok Jabatan',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->klpjabatan->nama_klp_jabatan;
                            },
                            'filter'=>Html::activeDropDownList($searchModel, 'id_klp_jabatan',$list_jabatan,['class'=>'form-control','prompt'=>''])
                        ],
                        'kode_grade',
                        'grade',
                        'deskripsi:ntext',
                        'nilai_jbt_max',
                        'nilai_jbt_min',
                        'nilai_jbt',
                        [
                            'label'=>'Status',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->status_grade == 1){
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/active.png" alt="Active">';
                                }else{
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/no-active.png" alt="No Active">';
                                }
                            },
                            'contentOptions' => ['class' => 'text-center'],
                            'filter'=>Html::activeDropDownList($searchModel, 'status_grade',[0=>'Non Aktif',1=>'Aktif'],['class'=>'form-control','prompt'=>''])
                        ],
                        ['class' => 'yii\grid\ActionColumn','template'=>'{update}'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
