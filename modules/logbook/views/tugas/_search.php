<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\logbook\models\TugasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tugas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="form-group">
         <a class="btn btn-success btn-flat" href="<?php echo Url::to(['/logbook/tugas/create']);?>">Tambah Tugas</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
