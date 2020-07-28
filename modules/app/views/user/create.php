<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\app\models\AppUser */

$this->title = Yii::t('app', 'Buat User');

?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'data'=>$data,
        'list_group'=>$list_group
    ]) ?>

</div>
