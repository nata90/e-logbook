<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
    use yii\jui\AutoComplete;
    use yii\web\JsExpression;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'List pegawai unit kerja '.$model->nama_unit_kerja);
$this->registerJs('var url = "' . Url::to(['unitkerja/addpegawai']) . '";');
$this->registerJs('var update_grid = "' . Url::to(['unitkerja/listpegawai', 'id'=>$model->id_unit_kerja]) . '";');

$this->registerJs('var url_set = "' . Url::to(['pegawai/setjabatan']) . '";');
$this->registerJs('var url_tambah_jabatan = "' . Url::to(['pegawai/simpanjabatan']) . '";');
$this->registerJs(<<<JS
    $(document).on("click", "#add-pegawai", function () {
        var id_peg = $('#unitkerja-id_pegawai').val();
        var id_unit_ker = $('#unitkerja-id_unit_kerja').val();

        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data:{
                'id_peg':id_peg,
                'id_unit_ker':id_unit_ker
            },
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            success: function(v){
                if(v.error == 1){
                    notifikasi(v.msg,"error");
                }else{
                    notifikasi(v.msg,"success");
                    $.pjax.reload({container: "#peg-unit-kerja", url: update_grid});
                }
            },
            'complete':function(json)
            {
                SimpleLoading.stop();
            },
        });
        
    });

    $(document).on("click", ".set-jabatan", function () {
        var id_peg = $(this).attr('rel');
        $.ajax({
            type: 'get',
            url: url_set,
            dataType: 'json',
            data:{'id':id_peg},
            success: function(v){
                show_modal("<strong>"+v.title+"</strong>",v.html,v.footer);
            }
        });
        
    });

    $(document).on("click", "#set-jab-pegawai", function () {
        var id_jabatan = $('#jabatanpegawai-id_jabatan').val();
        var status = $('#jabatanpegawai-status_jbt').val();
        var id_peg = $('#jabatanpegawai-id_pegawai').val();
        var penilai = $('#jabatanpegawai-id_penilai').val();

        $.ajax({
            type: 'get',
            url: url_tambah_jabatan,
            dataType: 'json',
            data: {'id_jabatan':id_jabatan, 'status':status, 'id_peg':id_peg, 'penilai':penilai},
            success: function(v){
                
                if(v.success == 1){
                    $('#modal').modal('hide');
                    notifikasi(v.msg,"success");
                    $.pjax.reload({container: "#peg-unit-kerja", url: update_grid});
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
                <h3 class="box-title"><?php echo $model->nama_unit_kerja;?></h3>
            </div>
            
                <?php $form = ActiveForm::begin(); ?>

            <div class="box-body">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item"><b>Direktorat</b><a class="pull-right"><?php echo $model->bagian->direktorat->nama_direktorat;?></a></li>
                    <li class="list-group-item"><b>Bagian</b><a class="pull-right"><?php echo $model->bagian->nama_bagian;?></a></li>
                    <li class="list-group-item"><b>ID Unit Kerja</b><a class="pull-right"><?php echo $model->id_unit_kerja?></a></li>
                    <li class="list-group-item"><b>Nama Unit Kerja</b><a class="pull-right"><?php echo $model->nama_unit_kerja;?></a></li>
                    <li class="list-group-item"><b>Status Unit</b><a class="pull-right">
                        <?php 
                        if($model->status_unit == 1){
                           echo '<img src="'.Yii::$app->request->baseUrl.'/images/active.png" alt="Active" width="25" height="25">';
                        }else{
                            echo '<img src="'.Yii::$app->request->baseUrl.'/images/no-active.png" alt="No Active">';
                        }
                    ?></a></li>
                </ul>
                <div class="row">
                    <div class="col-xs-5">
                            <?= $form->field($model, 'list_pegawai')->widget(\yii\jui\AutoComplete::classname(), [
                                'options' => ['class' => 'form-control input-sm pull-right', 'placeholder'=>'Tulis Nama Pegawai'],
                                'clientOptions' => [
                                    'source' => $data,
                                    'minLength'=>'2', 
                                    'autoFill'=>true,
                                    'select' => new JsExpression("function( event, ui ) {
                                        $('#unitkerja-id_pegawai').val(ui.item.id);
                                     }")
                                ],
                            ])->label(false) ?>

                            <?= $form->field($model, 'id_pegawai')->hiddenInput()->label(false) ?>
                            <?= $form->field($model, 'id_unit_kerja')->hiddenInput()->label(false) ?>
                    </div>
                    <div class="col-xs-2">
                        <a id="add-pegawai" class="btn btn-block btn-success btn-sm btn-flat" style="margin-top:10px;">Tambah</a>
                    </div>
                </div>
                <div style="padding-top:20px;">
                    <?php Pjax::begin([
                        'id'=>'peg-unit-kerja',
                        'timeout'=>false,
                        'enablePushState'=>false,
                        'clientOptions'=>['method'=>'GET']

                    ]); ?>
                    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            //'filterModel' => $searchModel,
                            'summary'=>'',
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'label'=>'NIP / NIK',
                                    'format'=>'raw',
                                    'value'=>function($model){
                                        return $model->pegawai->nip;
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
                                    'label'=>'Jabatan',
                                    'format'=>'raw',
                                    'value'=>function($model){
                                        $html = '';
                                        if($model->pegawai->jabatanPegawais != null){
                                            foreach($model->pegawai->jabatanPegawais as $val){
                                                $html .= '<button rel="'.$model->id_pegawai.'" type="button" class="btn bg-olive btn-flat margin btn-xs set-jabatan">'.$val->jabatan->nama_jabatan.'</button>';
                                            }
                                        }else{
                                            $html .= '<button rel="'.$model->id_pegawai.'" type="button" class="btn bg-maroon btn-flat margin btn-xs set-jabatan">Set Jabatan</button>';
                                        }
                                        

                                        return $html;
                                    }
                                ],
                                [
                                    'label'=>'Nama Penilai',
                                    'format'=>'raw',
                                    'value'=>function($model){
                                        $html = '';
                                        if($model->pegawai->jabatanPegawais != null){
                                            foreach($model->pegawai->jabatanPegawais as $val){
                                                $html .= $val->penilai->nama;
                                            }
                                        }else{
                                            $html .= '-';
                                        }
                                        

                                        return $html;
                                    }
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template'=>'{delete}',
                                    'urlCreator' => function( $action, $model, $key, $index ){
                                        if ($action == "delete") {
                                            return Url::to(['unitkerja/deletepegawai', 'id' => $key]);
                                        }
                                    }
                                ],
                            ],
                        ]); ?>

                        <?php Pjax::end(); ?>
                </div>
                    

            </div>
            <div class="box-footer">
                <a id="add-pegawai" class="btn btn-info btn-sm btn-flat pull-right" href="<?php echo Url::to(['unitkerja/index'])?>">BACK</a>
            </div>
                
                <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
