<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\logbook\models\KinerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Kinerja');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('var url_approve = "' . Url::to(['kinerja/approve']) . '";');
$this->registerJs('var update_grid = "' . Url::to(['kinerja/index']) . '";');
$this->registerJs('var daysago = "' . date('m/d/Y'). '";');
$this->registerJs('var daysnow = "' . date('m/d/Y') . '";');
$this->registerJs(<<<JS
    $(document).on("click", ".approve", function () {
        var id = $(this).attr('rel');
        var that = $(this);
        $.ajax({
              type: 'get',
              url: url_approve,
              dataType: 'json',
              'beforeSend':function(json)
                { 
                    SimpleLoading.start('gears'); 
                },
              data: {
                id:id,
              },
              success: function (v) {
                if(v.success == 1){
                    that.parents('td').html(v.button);
                    //$.pjax.reload({container: "#data-rekap-kinerja", url: update_grid});
                }
              },
              'complete':function(json)
                {
                    SimpleLoading.stop();
                },
          });
    });

    //Date range picker
    $('#reservation').daterangepicker();

    $(document).on("pjax:success", function(){
        $('#reservation').daterangepicker();
    });
JS
);
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Rekapitulasi Kinerja</h3>
            </div>
            <div class="box-body">
                
                <?php Pjax::begin([
                    'id'=>'data-rekap-kinerja',
                    'timeout'=>false,
                    'enablePushState'=>false,
                    'clientOptions'=>['method'=>'GET'],
                    'options'=>[
                        'class'=>'yii-gridview',
                    ],

                ]); ?>
                
                
                <?php echo $this->render('_search', ['model' => $searchModel, 'listData'=>$listData,'listPegawai'=>$listPegawai]); ?>

                 
                <div class="form-group">
                    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'summary' => '',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'label'=>'Tanggal Kinerja',
                            'format'=>'raw',
                            'value'=>function($model){
                                return date('d-m-Y', strtotime($model->tanggal_kinerja));
                            }
                        ],
                        [
                            'label'=>'Nama Pegawai',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->pegawai->nama;
                            }
                        ],
                        [
                            'label'=>'Tugas',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->tugas->nama_tugas;
                            }
                        ],
                        [
                            'label'=>'jumlah',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->jumlah;
                            }
                        ],
                        [
                            'label'=>'Deskripsi',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->deskripsi;
                            }
                        ],
                        [
                            'label'=>'Approval',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->approval == 1){
                                    return '<button rel="'.$model->id_kinerja.'" type="button" class="btn bg-olive btn-flat margin approve"> Approved</button>'; 
                                }else{
                                   return '<button rel="'.$model->id_kinerja.'" type="button" class="btn bg-maroon btn-flat margin approve">Not Approve</button>'; 
                                }
                                
                            }
                        ],

                        //['class' => 'yii\grid\ActionColumn','template'=>'{delete}'],
                    ],
                ]); ?>
                </div>
                

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
