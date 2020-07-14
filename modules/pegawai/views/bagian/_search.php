<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\BagianSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bagian-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="form-group">
        <a class="btn btn-success btn-flat" href="<?php echo Url::to(['/pegawai/bagian/create']);?>">Tambah Bagian</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>
