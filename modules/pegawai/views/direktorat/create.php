<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\Direktorat */

$this->title = Yii::t('app', 'Buat Direktorat');

?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
