<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\logbook\models\Kinerja */

$this->title = $model->id_kinerja;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kinerjas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="kinerja-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id_kinerja], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id_kinerja], [
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
            'id_kinerja',
            'tanggal_kinerja',
            'id_pegawai',
            'id_tugas',
            'jumlah',
            'deskripsi:ntext',
            'approval',
            'user_approval',
            'tgl_approval',
            'create_date',
        ],
    ]) ?>

</div>
