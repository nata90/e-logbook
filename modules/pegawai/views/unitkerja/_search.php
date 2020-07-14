<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\UnitKerjaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unit-kerja-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="form-group">
        <a class="btn btn-success" href="<?php echo Url::to(['/pegawai/unitkerja/create']);?>">Tambah Unit Kerja</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
