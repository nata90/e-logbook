<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\TbMenuSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-menu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'menu_name') ?>

    <?= $form->field($model, 'parent_id') ?>

    <?= $form->field($model, 'module') ?>

    <?= $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'active') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'icon') ?>

    <?php // echo $form->field($model, 'order') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
