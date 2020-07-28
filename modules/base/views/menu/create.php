<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\TbMenu */

$this->title = Yii::t('app', 'Create Tb Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tb Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-menu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
