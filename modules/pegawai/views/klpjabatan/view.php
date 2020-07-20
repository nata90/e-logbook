<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\KlpJabatan */

$this->title = $model->id_klp_jabatan;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Klp Jabatans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="klp-jabatan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id_klp_jabatan], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id_klp_jabatan], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_klp_jabatan',
            'kode_klp_jabatan',
            'nama_klp_jabatan',
            'deskripsi:ntext',
            'status_klp_jabatan',
        ],
    ]) ?>

</div>
