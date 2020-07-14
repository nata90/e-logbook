<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\app\models\AppUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-user-search">

    <?php /*$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]);*/ ?>


    <div class="form-group">
        <a class="btn btn-success btn-flat" href="<?php echo Url::to(['/app/user/create']);?>">Tambah User</a>
    </div>

    <?php //ActiveForm::end(); ?>

</div>
