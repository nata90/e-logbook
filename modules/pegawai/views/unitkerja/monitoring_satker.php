<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\modules\logbook\models\Tugas;
use app\modules\pegawai\models\PegawaiUnitKerjaSearch;
use app\modules\app\models\AppUser;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pegawai\models\UnitKerjaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Monitoring Unit Kerja');
$this->registerJs(<<<JS
    
JS
);
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Monitoring Unit Kerja</h3>
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
                <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        //'id_unit_kerja',
                        'nama_unit_kerja',
                        [
                            'label'=>'Tugas',
                            'format'=>'raw',
                            'value'=>function($model){
                                $tugas = Tugas::find()->where(['id_unit_kerja'=>$model->id_unit_kerja, 'status_tugas'=>1])->count();
                                return $tugas;
                            }
                        ],
                        [
                            'label'=>'Pegawai',
                            'format'=>'raw',
                            'value'=>function($model){
                                $searchModel = new PegawaiUnitKerjaSearch();
                                $searchModel->id_unit_kerja = $model->id_unit_kerja;
                                $provider = $searchModel->search(Yii::$app->request->queryParams);

                                $models = $provider->getModels();

                                $html = '<ul class="todo-list ui-sortable">';
                                if($models != null){
                                    foreach($models as $val){
                                         if($val->pegawai->jabatanPegawais != null){
                                            foreach($val->pegawai->jabatanPegawais as $row){
                                                $jabatan = '<small class="label label-success">'.$row->jabatan->nama_jabatan.'</small>';
                                            }
                                        }else{
                                            $jabatan = '<small class="label label-danger">Not Set</small>';
                                        }

                                        $user = AppUser::find()->where(['pegawai_id'=>$val->id_pegawai])->one();

                                        if($user != null){
                                            $grup_user = '<small class="label label-info">'.$user->group->nama_group.'</small>';
                                        }else{
                                            $grup_user = '<small class="label label-danger">Not Set</small>';
                                        }

                                        $html .= '<li><span class="text">'.$val->pegawai->nama.' '.$jabatan.' '.$grup_user.'</span></li>';
                                    }
                                }else{
                                    $html .= '<li><span class="text">-</span></li>';
                                }
                                $html .= '</ul>';

                                return $html;
                            }
                        ],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
        

    </div>
</div>

