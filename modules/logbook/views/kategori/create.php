<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\logbook\models\Kategori */

$this->title = Yii::t('app', 'Buat Kategori');
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
