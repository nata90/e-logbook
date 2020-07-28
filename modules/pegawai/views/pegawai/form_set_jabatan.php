<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
?>

<div>
	<?php $form = ActiveForm::begin([
            'options'=>[
                'layout' => 'horizontal',
                'class'=>'form-horizontal',
            ],
            'fieldConfig' => [
                'template' => '<label class="col-sm-3 control-label">{label}</label><div class="col-xs-8">{input}</div>',
            ]
    ]); ?>

    <?= $form->field($model, 'id_jabatan')->dropDownList(
                $list_jabatan,
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

    <?= $form->field($model, 'id_penilai')->dropDownList(
                $list_penilai,
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

    <?= $form->field($model, 'status_jbt')->dropDownList(
                [0=>'Non Aktif', 1=>'Aktif'],
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

    <?php 
        if($jabatan_aktif != null){
            echo '<div class="form-group field-jabatanpegawai-jabatan_aktif required"><label class="col-sm-3 control-label"><label class="control-label" for="jabatanpegawai-jabatan_aktif">Jabatan Aktif</label></label><div class="col-xs-8" style="padding-top:10px;"><span class="label label-success" >'.$jabatan_aktif->jabatan->nama_jabatan.'</span></div></div>';

            echo '<div class="form-group field-jabatanpegawai-penilai required"><label class="col-sm-3 control-label"><label class="control-label" for="jabatanpegawai-penilai">Penilai</label></label><div class="col-xs-8" style="padding-top:10px;"><span class="label label-success" >'.$jabatan_aktif->penilai->nama.'</span></div></div>';
        }
    ?>


    <?= $form->field($model, 'id_pegawai')->hiddenInput(['maxlength' => true])->label(false) ?>
    
    <?php ActiveForm::end(); ?>
</div>