<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\logbook\models\Kinerja */

$this->title = Yii::t('app', 'Update Kinerja: {name}', [
    'name' => $model->id_kinerja,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kinerjas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_kinerja, 'url' => ['view', 'id' => $model->id_kinerja]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="kinerja-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
