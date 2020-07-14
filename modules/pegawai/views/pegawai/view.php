<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\pegawai\models\DataPegawai */

$this->title = $model->id_pegawai;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Data Pegawais'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="data-pegawai-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id_pegawai], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id_pegawai], [
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
            'id_pegawai',
            'nip',
            'pin',
            'nama',
            'tmp_lahir',
            'tgl_lahir',
            'jenis_peg',
            'status_peg',
            'gender',
            'username',
            'password',
        ],
    ]) ?>

</div>
