<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\DataPegawaiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-pegawai-search">

    <?php /*$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]);*/ ?>

    

    <div class="form-group">
        <a class="btn btn-success" href="<?php echo Url::to(['/pegawai/pegawai/create']);?>">Tambah Pegawai</a>
    </div>

    <?php //ActiveForm::end(); ?>

</div>
