<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\base\models\TbMenu;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\TbMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Setting Menu Pergroup');
$this->registerCssFile(Yii::$app->request->BaseUrl."/css/treeview.css");
$this->registerJs('var url = "' . Url::to(['menu/simpansetting']) . '";');
$this->registerJs('var url_delete = "' . Url::to(['menu/deletesetting']) . '";');
$this->registerJs('var url_load_menu = "' . Url::to(['menu/loadsetting']) . '";');
$this->registerJs(<<<JS
    $(document).on("click", "input[type=checkbox].set-menu", function () {
        var group = $('#tbmenusearch-id_group').val();
        var idmenu = $(this).attr('id');

        if(group == ''){
            notifikasi("Group user tidak boleh kosong","error");
        }else{
            if($(this).prop('checked')) {
                $.ajax({
                    type: 'get',
                    url: url,
                    dataType: 'json',
                    data:{
                        'group':group,
                        'idmenu':idmenu
                    },
                    success: function(v){
                        if(v.success == 0){
                            notifikasi(v.msg,"error");
                        }else{
                            notifikasi(v.msg,"success");
                        }
                    }
                });
            } else {
                $.ajax({
                    type: 'get',
                    url: url_delete,
                    dataType: 'json',
                    data:{
                        'group':group,
                        'idmenu':idmenu
                    },
                    success: function(v){
                        if(v.success == 0){
                            notifikasi(v.msg,"error");
                        }else{
                            notifikasi(v.msg,"success");
                            $.pjax.reload({container: "#peg-unit-kerja", url: update_grid});
                        }
                    }
                });
            }
        }
        
    });


    $(document).on("change", "#tbmenusearch-id_group", function () {
        var group = $(this).val();
        $.ajax({
            type: 'get',
            url: url_load_menu,
            dataType: 'json',
            data:{
                'group':group,
            },
            success: function(v){
                $('ol.tree-structure').find('input.set-menu').prop( "checked", false );
                for(var i in v.value){
                    $('ol.tree-structure').find('input#'+v.value[i]).prop( "checked", true );
                }
            }
        });
    });
    
JS
);
?>
<div class="row">
    <div class="col-md-8">
        <div class="box box-danger box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Setting Menu Per Group</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'options'=>[
                    'layout' => 'horizontal',
                    'class'=>'form-horizontal',
                ],
                'fieldConfig' => [
                    'template' => '<label class="col-xs-2 control-label">{label}</label><div class="col-xs-5">{input}</div>',
                ]
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'id_group')->dropDownList(
                    $listgroup,
                    ['prompt'=>'Pilih Salah Satu']
                )->label('Group') ?>
                <div class="form-group field-tbmenusearch-id_group">
                    <label class="col-xs-2 control-label">
                        <label class="control-label">Menu</label>
                    </label>
                    <div class="col-xs-5">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8">
                                    
                                        <?php 
                                            echo TbMenu::getListMenu();
                                        ?>
                                     
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
