<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\app\models\AppUser */

$this->title = Yii::t('app', 'Profile');
$this->registerJs('var url_upload = "' . Url::to(['user/uploadphoto']) . '";');
$this->registerJs(<<<JS
    $(document).on("click", "#upload-profil", function () {
        $('#imgupload').trigger('click');
    });

    $(document).ready(function(){
        $('#imgupload').change(function(e){
            if($('#imgupload').val()){
                e.preventDefault();
                $('.field-datapegawai-progress-bar').show();
                $('#form-profile').ajaxSubmit({
                    url:url_upload,
                    target:'#target-layer',
                    beforeSubmit: function(){
                        $('.progress-bar').width('0%');
                    },
                    uploadProgress: function(event, position, total, percentComplete){
                        $('.progress-bar').width(percentComplete+'%');
                    },
                    success: function(){
                        $('.field-datapegawai-progress-bar').hide();
                        notifikasi("Update profile picture sukses","success");
                    },
                    resetForm:true
                })
                return false
            }
        })
    });
JS
);
?>
<div class="row">

    <div class="col-md-8">
    <div class="box box-danger box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">
                <?php echo $this->title;?>
            </h3>
        </div>

        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'options'=>[
                    'layout' => 'horizontal',
                    'class'=>'form-horizontal',
                    'id'=>'form-profile'
                ],
                'fieldConfig' => [
                    'template' => '<label class="col-sm-3 control-label">{label}</label><div class="col-xs-8">{input}</div>',
                ]
            ]); ?>

            <?= Html::errorSummary($model, ['encode' => false, 'class'=>'callout callout-danger']) ?>

            <div class="form-group field-datapegawai-photo-profile">
                <label class="col-sm-3 control-label">
                    <label class="control-label" for="datapegawai-photo_profile">Foto Profil</label>
                </label>
                <div class="col-xs-1">
                    <input type="file" id="imgupload" name="uploadFile" style="display:none"/> 
                    <button type="button" class="btn bg-purple margin" id="upload-profil">Upload</button>
                </div>
                <div class="col-xs-2" id="target-layer">
                    <?php if($user->photo_profile != '-'){ ?>
                        <img src="<?php echo Yii::$app->request->baseUrl;?>/profpic/<?php echo $user->photo_profile;?>" class="profile-user-img img-responsive" alt="User Image"/>
                    <?php }else{ 
                            if($user->pegawai->gender == 0){ ?>
                                <img src="<?php echo Yii::$app->request->baseUrl;?>/images/avatar5.png" class="profile-user-img img-responsive" alt="User Image"/>
                        <?php }else{ ?>
                                <img src="<?php echo Yii::$app->request->baseUrl;?>/images/avatar3.png" class="profile-user-img img-responsive" alt="User Image"/>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group field-datapegawai-progress-bar" style="display:none;">
                <label class="col-sm-3 control-label">
                </label>
                <div class="col-xs-8">
                    <div class="progress progress-sm active">
                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width:20%">
                          
                        </div>            
                    </div>
                </div>
            </div>
            

            <?= $form->field($model, 'nip')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'tmp_lahir')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'pin')->textInput() ?>

            <?= $form->field($model, 'tgl_lahir')->widget(\yii\jui\DatePicker::class,[
                        'options'=>['class'=>'form-control'],
                        'clientOptions' => ['changeYear' => true, 'changeMonth'=>true]
                    ]) ?>

            <?= $form->field($model, 'jenis_peg')->dropDownList(
                [0=>'PNS', 1=>'NON PNS', 2=>'Kontrak'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'status_peg')->dropDownList(
                [0=>'Masih Bekerja', 1=>'Pensiun', 2=>'Pindah ke Luar RS',3=>'Meninggal'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <?= $form->field($model, 'email')->textInput() ?>
            <?= $form->field($model, 'jam_masuk')->textInput() ?>

           <?= $form->field($model, 'gender')->radioList(
                [0=>'Pria', 1=>'Wanita'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

            <div class="form-group field-datapegawai-gender">
                <label class="col-sm-3 control-label">
                    <label class="control-label">Unit Kerja</label>
                </label>
                <div class="col-xs-8" style="padding-top:5px;">
                    <label class="control-label"><?php echo $model_unit->unitKerja->nama_unit_kerja==null?'<span class="label label-danger">NOT SET</span>':'<span class="label label-success">'.strtoupper($model_unit->unitKerja->nama_unit_kerja).'</span>';?></label>
                </div>
                
            </div>

            <div class="form-group field-datapegawai-gender">
                <label class="col-sm-3 control-label">
                    <label class="control-label">Jabatan</label>
                </label>
                <div class="col-xs-8" style="padding-top:5px;">
                    <label class="control-label"><?php echo $model_jabatan->jabatan->nama_jabatan==null?'<span class="label label-danger">NOT SET</span>':'<span class="label label-success">'.strtoupper($model_jabatan->jabatan->nama_jabatan).'</span>';?></label>
                </div>
            </div>

            <div class="form-group field-datapegawai-gender">
                <label class="col-sm-3 control-label">
                    <label class="control-label">Target</label>
                </label>
                <div class="col-xs-8" style="padding-top:5px;">
                    <label class="control-label"><?php echo $model_target->nilai_target==null?'<span class="label label-danger">NOT SET</span>':'<span class="label label-success">'.strtoupper($model_target->nilai_target).'</span>';?></label>
                </div>
            </div>

            <div class="form-group field-datapegawai-gender">
                <label class="col-sm-3 control-label">
                    <label class="control-label">Penilai</label>
                </label>
                <div class="col-xs-8" style="padding-top:5px;">
                    <label class="control-label"><?php echo $model_jabatan->penilai->nama==null?'<span class="label label-danger">NOT SET</span>':'<span class="label label-success">'.strtoupper($model_jabatan->penilai->nama).'</span>';?></label>
                </div>
                
            </div>

             <div class="box-footer">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
            </div>

            <?php ActiveForm::end(); ?>

    </div>

</div>

</div>