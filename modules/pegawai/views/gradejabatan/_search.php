<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\GradeJabatanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grade-jabatan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="form-group">
        <a class="btn btn-success btn-flat" href="<?php echo Url::to(['/pegawai/gradejabatan/create']);?>">Tambah Grade Jabatan</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
