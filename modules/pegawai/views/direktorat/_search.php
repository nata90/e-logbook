<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\DirektoratSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="direktorat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="form-group">
        <a class="btn btn-success" href="<?php echo Url::to(['/pegawai/direktorat/create']);?>">Tambah Direktorat</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
