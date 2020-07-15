<?php

namespace app\modules\base\models;

use Yii;

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
            [['parent_id', 'active', 'type'], 'integer'],
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
        $model = TbMenu::find()
        ->where(['active'=>1, 'type'=>1, 'parent_id'=>$parent])
        ->all();

        $menu = array();

        if($model != null){
 
            foreach($model as $val){

                if($val->url != '#'){
                    $url = [$val->url];
                }else{
                    $url = '#';
                }

                $menu[] = [
                    'label'=>$val->menu_name, 
                    'icon' => $val->icon, 
                    'url' => $url,
                    'items' => TbMenu::getMenu($val->id)
                ];

            }
        }

        /*echo '<pre>';
        print_r($menu);
        echo '</pre>';*/

        return $menu;
    }

    /**
     * {@inheritdoc}
     * @return TbMenuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbMenuQuery(get_called_class());
    }
}
