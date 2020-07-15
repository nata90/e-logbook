<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\logbook\models\Kategori */

$this->title = Yii::t('app', 'Update Kategori {name}', [
    'name' => $model->nama_kategori,
]); ?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
