<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\logbook\models\KinerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Kinerjas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Kinerja</h3>
            </div>
            <div class="box-body">

                <?php Pjax::begin(); ?>
                <?php echo $this->render('_search', ['model' => $searchModel, 'listData'=>$listData]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'id_kinerja',
                        //'tanggal_kinerja',
                        [
                            'label'=>'Tanggal Kinerja',
                            'format'=>'raw',
                            'value'=>function($model){
                                return date('d-m-Y', strtotime($model->tanggal_kinerja));
                            }
                        ],
                        //'id_pegawai',
                        //'id_tugas',
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
                        //'approval',
                        //'user_approval',
                        //'tgl_approval',
                        //'create_date',

                        ['class' => 'yii\grid\ActionColumn','template'=>'{delete}'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
