<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
    use yii\jui\AutoComplete;
    use yii\web\JsExpression;
?>

<div class="col-md-8">
    <div class="box box-danger box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo $model->nama_unit_kerja;?></h3>
        </div>
        
        	<?php $form = ActiveForm::begin([
                'options'=>[
                    'layout' => 'horizontal',
                    'class'=>'form-horizontal',
                ],
                'fieldConfig' => [
                    'template' => '<label class="col-sm-2 control-label">{label}</label><div class="col-xs-8">{input}</div>',
                ]
            ]); ?>

        <div class="box-body">
            <ul class="list-group list-group-unbordered">
                <li class="list-group-item"><b>ID Unit Kerja</b><a class="pull-right"><?php echo $model->id_unit_kerja?></a></li>
                <li class="list-group-item"><b>Bagian</b><a class="pull-right"><?php echo $model->bagian->nama_bagian;?></a></li>
                <li class="list-group-item"><b>Nama Unit Kerja</b><a class="pull-right"><?php echo $model->nama_unit_kerja;?></a></li>
                <li class="list-group-item"><b>Status Unit</b><a class="pull-right">
                    <?php 
                    if($model->status_unit == 1){
                       echo '<img src="'.Yii::$app->request->baseUrl.'/images/active.png" alt="Active" width="25" height="25">';
                    }else{
                        echo '<img src="'.Yii::$app->request->baseUrl.'/images/no-active.png" alt="No Active">';
                    }
                ?></a></li>
            </ul>
            <?= $form->field($model, 'list_pegawai')->widget(\yii\jui\AutoComplete::classname(), [
                        'options' => ['class' => 'form-control input-md'],
                        'clientOptions' => [
                            'source' => $data,
                            'minLength'=>'2', 
                            'autoFill'=>true,
                            'select' => new JsExpression("function( event, ui ) {
                                $('#unitkerja-list_pegawai').val(ui.item.id);
                             }")
                        ],
                    ]) ?>
        </div>
            
            <?php ActiveForm::end(); ?>
    </div>

</div>