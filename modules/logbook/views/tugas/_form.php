<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\logbook\models\Tugas */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs('var url = "' . Url::to(['tugas/getidtugas']) . '";');
$this->registerJs(<<<JS
    $(document).on("change", "#tugas-id_unit_kerja", function () {
        var unit_ker = $(this).val();
        
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data:{'unit_ker':unit_ker},
            success: function(v){
                $('#tugas-id_tugas').val(v.nilai);
            }
        });
    });
JS
);
?>

<div class="col-md-8">
    <div class="box box-danger box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">
                <?php echo $this->title;?>
            </h3>
        </div>


            <?php $form = ActiveForm::begin([
                'options'=>[
                    'layout' => 'horizontal',
                    'class'=>'form-horizontal',
                ],
                'fieldConfig' => [
                    'template' => '<label class="col-sm-3 control-label">{label}</label><div class="col-xs-8">{input}</div>',
                ]
            ]); ?>

            <div class="box-body">
                <?= Html::errorSummary($model, ['encode' => false, 'class'=>'callout callout-danger']) ?>

                <?= $form->field($model, 'id_unit_kerja')->dropDownList(
                    $list_unit_kerja,
                    ['prompt'=>'Pilih Salah Satu']
                ) ?>

                <?= $form->field($model, 'id_tugas')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'id_kategori')->dropDownList(
                    $list_kategori,
                    ['prompt'=>'Pilih Salah Satu']
                ) ?>

                <?= $form->field($model, 'nama_tugas')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'akses')->dropDownList(
                    [0=>'Tertutup', 1=>'Terbuka'],
                    ['prompt'=>'Pilih Salah Satu']
                ) ?>

                <?= $form->field($model, 'status_tugas')->dropDownList(
                    [0=>'Non Aktif', 1=>'Aktif'],
                    ['prompt'=>'Pilih Salah Satu']
                ) ?>

            </div>
            <div class="box-footer">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
            </div>
            <?php ActiveForm::end(); ?>
    </div>

</div>
