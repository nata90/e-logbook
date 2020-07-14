<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\logbook\models\KinerjaSearch */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs(<<<JS
    $(document).on("change", "#kinerjasearch-tanggal_kinerja", function () {
        var tgl = $(this).val();
        
        alert(tgl);
    });

JS
);

?>

<div class="kinerja-search">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tanggal_kinerja')->widget(\yii\jui\DatePicker::class,[
                'options'=>['class'=>'form-control'],
                'clientOptions' => ['changeYear' => true, 'changeMonth'=>true]
            ]) ?>

    <?= $form->field($model, 'id_tugas')->dropDownList(
                $listData,
                ['prompt'=>'Pilih Salah Satu']
            ) ?>

    <?= $form->field($model, 'deskripsi')->textArea() ?>

    <?= $form->field($model, 'jumlah') ?>


    <div class="box-footer">
        <?= Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
