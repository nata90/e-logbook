<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
    use yii\jui\AutoComplete;
    use yii\web\JsExpression;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'List target jabatan '.$model->nama_jabatan);
$this->registerJs('var url = "' . Url::to(['unitkerja/addpegawai']) . '";');
$this->registerJs('var update_grid = "' . Url::to(['jabatan/listtarget', 'id'=>$model->id_jabatan]) . '";');

$this->registerJs('var url_set = "' . Url::to(['pegawai/setjabatan']) . '";');
$this->registerJs('var url_set_target = "' . Url::to(['jabatan/simpantarget']) . '";');
$this->registerJs(<<<JS

    $(document).on("click", ".save-target", function () {
        var id_jabatan = $('#targetsearch-id_jabatan').val();
        var id_unit_kerja = $('#targetsearch-id_unit_kerja').val();
        var target = $('#targetsearch-nilai_target').val();

        $.ajax({
            type: 'post',
            url: url_set_target,
            dataType: 'json',
            data: {'id_jabatan':id_jabatan, 'id_unit_kerja':id_unit_kerja, 'target':target},
            success: function(v){
                
                if(v.success == 1){
                    $('#modal').modal('hide');
                    notifikasi(v.msg,"success");
                    $.pjax.reload({container: "#list-target-jabatan", url: update_grid});
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
    <div class="col-md-10">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Set Target <?php echo $model->nama_jabatan;?></h3>
            </div>
            
                

            <div class="box-body">
                <?php $form = ActiveForm::begin([
                    'options'=>[
                        'layout' => 'horizontal',
                        'class'=>'form-horizontal',
                    ],
                    'fieldConfig' => [
                        'template' => '<label class="col-sm-3 control-label">{label}</label><div class="col-xs-8">{input}</div>',
                    ]
                ]); ?>
                <div style="padding-top:20px;">
                    <?php Pjax::begin([
                        'id'=>'list-target-jabatan',
                        'timeout'=>false,
                        'enablePushState'=>false,
                        'clientOptions'=>['method'=>'GET']

                    ]); ?>
                    <div class="col-xs-11">
                            <div class="form-group field-targetsearch-jabatan">
                                <label class="col-sm-3 control-label">
                                    <label class="control-label" for="targetsearch-id_unit_kerja">Jabatan</label>
                                </label>
                                <div class="col-xs-8" style="padding-top:10px;">
                                    <span class="label label-success"><?php echo $model->nama_jabatan;?></span>
                                </div>
                            </div>
                            <?= $form->field($searchModel, 'id_unit_kerja')->dropDownList(
                                $list_unit_kerja,
                                ['prompt'=>'Pilih Salah Satu']
                            )->label('Unit Kerja') ?>

                            <?= $form->field($searchModel, 'nilai_target')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($searchModel, 'id_jabatan')->hiddenInput()->label(false) ?>
                             <div class="form-group">
                                <?= Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right save-target']) ?>
                            </div>
                    </div>
                   

                    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            //'filterModel' => $searchModel,
                            'summary'=>'',
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'label'=>'Jabatan',
                                    'format'=>'raw',
                                    'value'=>function($model){
                                        return $model->jabatan->nama_jabatan;
                                    }
                                ],
                                [
                                    'label'=>'Unit Kerja',
                                    'format'=>'raw',
                                    'value'=>function($model){
                                        return $model->unitKerja->nama_unit_kerja;
                                    }
                                ],
                                [
                                    'label'=>'Nilai Target',
                                    'format'=>'raw',
                                    'value'=>function($model){
                                        return $model->nilai_target;
                                    }
                                ],
                                
                            ],
                        ]); ?>

                        <?php Pjax::end(); ?>
                </div>
                    

            </div>
            <div class="box-footer">
                <a id="add-pegawai" class="btn btn-info btn-sm btn-flat pull-right" href="<?php echo Url::to(['jabatan/index'])?>">BACK</a>
            </div>
                
                <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
