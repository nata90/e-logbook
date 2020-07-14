<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\logbook\models\Kinerja */

$this->title = Yii::t('app', 'Create Kinerja');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kinerjas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kinerja-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
