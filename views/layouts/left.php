<?php 
    use app\modules\base\models\TbMenu;
    use app\modules\app\models\AppUser;
    use app\modules\pegawai\models\DataPegawai;
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php 
                $id_user = Yii::$app->user->id;
                $model = AppUser::findOne($id_user);

                if($model->photo_profile != '-'){
                    echo '<img src="'.Yii::$app->request->baseUrl.'/profpic/'.$model->photo_profile.'" class="img-circle" alt="User Image"/>';
                }else{
                    if($model->pegawai->gender == 0){
                        echo '<img src="'.Yii::$app->request->baseUrl.'/images/avatar5.png" class="img-circle" alt="User Image"/>';
                    }else{
                        echo '<img src="'.Yii::$app->request->baseUrl.'/images/avatar3.png" class="img-circle" alt="User Image"/>';
                    }
                }

                ?>
                
            </div>
            <div class="pull-left info">
                <p><?php echo $model->username;?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?php 
        $menu = TbMenu::getMenu();

        echo dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => $menu,
            ]
        ) ?>
        

    </section>

</aside>
