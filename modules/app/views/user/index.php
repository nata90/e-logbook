<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\app\models\AppUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Data User');
$this->registerJs('var url_update_pass = "' . Url::to(['user/savenewpassword']) . '";');
$this->registerJs('var update_grid = "' . Url::to(['user/index']) . '";');
$this->registerJs(<<<JS
    $(document).on("click", ".update-pass", function () {
        var url = $(this).attr('url');
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            success: function(v){
                show_modal("<strong>"+v.title+"</strong>",v.html,v.footer);
            }
        });
        
    });

    $(document).on("click", "#update-password", function () {
        var new_password = $('#appuser-new_password').val();
        var id = $('#appuser-id').val();

        $.ajax({
            type: 'get',
            url: url_update_pass,
            dataType: 'json',
            data: {'new_password':new_password, 'id':id},
            success: function(v){
                
                if(v.success == 1){
                    $('#modal').modal('hide');
                    notifikasi(v.msg,"success");
                    $.pjax.reload({container: "#data-user", url: update_grid});
                }else{
                    notifikasi(v.msg,"error");
                }
            }
        });
        
    });
JS
);
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Data User</h3>
            </div>
            <div class="box-body">

                <?php Pjax::begin([
                    'id'=>'data-user',
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

                        //'id',
                        'username',
                       [
                            'label'=>'Password',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->password;
                            },
                        ],
                        
                        [
                            'label'=>'Aktif',
                            'format'=>'raw',
                            'value'=>function($model){
                                if($model->active == 1){
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/active.png" alt="Active">';
                                }else{
                                    return '<img src="'.Yii::$app->request->baseUrl.'/images/no-active.png" alt="No Active">';
                                }
                            },
                            'contentOptions' => ['class' => 'text-center'],
                            'filter'=>Html::activeDropDownList($searchModel, 'active',[0=>'Non Aktif',1=>'Aktif'],['class'=>'form-control','prompt'=>''])
                        ],
                        [
                            'label'=>'Nama Pegawai',
                            'format'=>'raw',
                            'value'=>function($model){
                                return $model->pegawai->nama;
                            },
                            'filter'=>Html::activeTextInput($searchModel, 'pegawai_nama',['class'=>'form-control'])
                        ],
                        [
                            'label'=>'',
                            'format'=>'raw',
                            'value'=>function($model){
                                return '<button class="btn bg-olive margin btn-flat btn-xs update-pass" url="'.Url::to(['user/updatepassword', 'id'=>$model->id]).'">Ganti Password</button>';
                            },
                        ],
                        ['class' => 'yii\grid\ActionColumn','template'=>'{update}&nbsp;{delete}'],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
        

    </div>
</div>
