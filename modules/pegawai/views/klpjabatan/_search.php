<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\KlpJabatanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="klp-jabatan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="form-group">
        <a class="btn btn-success btn-flat" href="<?php echo Url::to(['/pegawai/klpjabatan/create']);?>">Tambah Kelompok Jabatan</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
