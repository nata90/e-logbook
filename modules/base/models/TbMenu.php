<?php

namespace app\modules\base\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\app\models\AppUser;
use app\modules\app\models\AppUserGroup;
use app\modules\base\models\AppGroupMenu;

/**
 * This is the model class for table "tb_menu".
 *
 * @property int $id
 * @property string $menu_name
 * @property int $parent_id
 * @property string $url
 * @property int $active
 * @property int|null $type 1=menu,2=action
 */
class TbMenu extends \yii\db\ActiveRecord
{
    public $id_group;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu_name', 'url', 'active'], 'required'],
            [['parent_id', 'active', 'type','order','id_group'], 'integer'],
            [['menu_name','module'], 'string', 'max' => 200],
            [['url'], 'string', 'max' => 300],
            [['icon'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'menu_name' => Yii::t('app', 'Menu Name'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'url' => Yii::t('app', 'Url'),
            'active' => Yii::t('app', 'Active'),
            'type' => Yii::t('app', 'Type'),
            'module' => Yii::t('app', 'Module'),
            'icon' => Yii::t('app', 'Icon'),
        ];
    }

    public static function getMenu($parent = 0){
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $model = AppGroupMenu::find()
        ->leftJoin('tb_menu', 'tb_menu.id = app_group_menu.id_menu')
        ->where(['id_group'=>$user->id_group, 'tb_menu.active'=>1, 'tb_menu.type'=>1, 'tb_menu.parent_id'=>$parent])
        ->orderBy('tb_menu.order ASC')
        ->all();

        /*$model = TbMenu::find()
        ->where(['active'=>1, 'type'=>1, 'parent_id'=>$parent])
        ->orderBy('order ASC')
        ->all();*/

        $menu = array();

        if($model != null){
 
            foreach($model as $val){

                if($val->menu->url != '#'){
                    $url = [$val->menu->url];
                }else{
                    $url = '#';
                }

                $menu[] = [
                    'label'=>$val->menu->menu_name, 
                    'icon' => $val->menu->icon, 
                    'url' => $url,
                    'items' => TbMenu::getMenu($val->menu->id)
                ];

            }
        }

        return $menu;
    }

    public static function getListMenu($parent = 0){
        $model = TbMenu::find()
        ->where(['active'=>1, 'parent_id'=>$parent])
        ->orderBy('order ASC')
        ->all();

        if($parent === 0){
            $class = 'class="tree-structure"';
        }else{
            $class = '';
        }

        $html = '';

        if($model != null){
            $html = '<ol '.$class.'>';
            $no = 1;
            foreach($model as $val){
                $style = '';
                if($val->type == 2){
                    $style = 'style="font-color:red"';
                }

                $html .= '<li>
                         <span class="num"><input type="checkbox" id="'.$val->id.'" class="set-menu"></span>
                         <a href="#" '.$style.'>'.$val->menu_name.'</a>'.TbMenu::getListMenu($val->id).'
                      </li>';

                $no++;
            }
            $html .= '</ol>';
        }

        

        return $html;
    }

    /**
     * {@inheritdoc}
     * @return TbMenuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbMenuQuery(get_called_class());
    }

    public static function getAksesUser(){
        $id_user = Yii::$app->user->id;
        $user = AppUser::findOne($id_user);

        $arr_return = ['-'];
        if($user != null){
            $menu = AppGroupMenu::find()->where(['id_group'=>$user->id_group, 'active'=>1])->all();

        
            $controller = Yii::$app->controller->id;

            
            $list_menu = [];
            if($menu != null){
                foreach($menu as $val){
                    $explode = explode('/',$val->menu->url);
                    if(isset($explode) && count($explode) > 1){
                        if($val->menu->module == '-'){
                        
                            if($explode[1] === $controller){
                                $action = $explode[2];
                                $arr_return[$val->id_menu] = $action;
                            }
                        }else{
                            if($explode[2] === $controller){
                                $action = $explode[3];
                                $arr_return[$val->id_menu] = $action;
                            }    
                        }
                    }
    
                }
                
            }
        }
        

        return $arr_return;
    }

    public static function getAksesUser2($id_grup){
        //$id_user = Yii::$app->user->id;
        //$user = AppUser::findOne($id_user);

        //if(!Yii::$app->user->isGuest){
            $menu = AppGroupMenu::find()->where(['id_group'=>$id_grup, 'active'=>1])->all();
        /*}else{
            $menu = AppGroupMenu::find()->where(['active'=>1])->all();
        }*/
        
        $controller = Yii::$app->controller->id;

        $arr_return = [];
        $list_menu = [];
        if($menu != null){
            foreach($menu as $val){
                $explode = explode('/',$val->menu->url);
                if($val->menu->module == '-'){
                    if($explode[1] === $controller){
                        $action = $explode[2];
                        $arr_return[] = $action;
                    }
                }else{
                    if($explode[2] === $controller){
                        $action = $explode[3];
                        $arr_return[] = $action;
                   }
                    
                }

                
            }
            
        }

        return $arr_return;
    }

    public static function getGrupAkses(){
        $grup = AppUserGroup::find()->where(['active'=>1])->all();
        $return = array();
        if($grup != null){
            foreach($grup as $val){
                $return[] = [
                    'actions' => TbMenu::getAksesUser($val->id),
                    'allow' => true,
                    'matchCallback' => 'function ($rule, $action) {
                        $id_user = Yii::$app->user->id;
                        $user = AppUser::findOne($id_user);
                        return $user->id_group === $val->id;
                    }'
                ];
            }
        }

        return $return;
    }
}
