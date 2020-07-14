<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pegawai\models\DataPegawaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Data Pegawai');

?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Data Pegawai</h3>
            </div>
            <div class="box-body">
            
                <?php Pjax::begin([
                    'id'=>'grid-pegawai',
                    'timeout'=>false,
                    'enablePushState'=>false,
                    'clientOptions'=>['method'=>'GET']

                ]); ?>
                <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'id_pegawai',
                        'nip',
                        'nama',
                        [
                            'label'=>'PIN',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->pin;
                            },
                        ],
                        [
                            'label'=>'Tempat Lahir',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->tmp_lahir;
                            },
                        ],
                        [
                            'label'=>'Tanggal Lahir',
                            'format'=>'raw',
                            'value'=>function($model){
                                return date('d-m-Y', strtotime($model->tgl_lahir));
                            },
                        ],
                        [
                            'label'=>'Jenis Pegawai',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->jenis_peg == 0){
                                    return 'PNS';
                                }elseif($model->jenis_peg == 1){
                                    return 'NON PNS';
                                }elseif($model->jenis_peg == 3){
                                    return 'Kontrak';
                                }
                            }
                        ],
                        [
                            'label'=>'Status Pegawai',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->status_peg == 0){
                                    return 'Masih Bekerja';
                                }elseif($model->status_peg == 1){
                                    return 'Pensiun';
                                }elseif($model->status_peg == 3){
                                    return 'Pindah ke Luar RS';
                                }elseif($model->status_peg == 4){
                                    return 'Meninggal';
                                }
                            }
                        ],
                        [
                            'label'=>'Jenis Kelamin',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->gender == 0){
                                    return 'Pria';
                                }elseif($model->gender == 1){
                                    return 'Wanita';
                                }
                            }
                        ],

                        ['class' => 'yii\grid\ActionColumn','template'=>'{update}&nbsp;{delete}'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
        

    </div>
</div>
