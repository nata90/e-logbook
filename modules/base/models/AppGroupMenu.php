<?php

namespace app\modules\base\models;
use app\modules\base\models\TbMenu;

use Yii;

/**
 * This is the model class for table "{{%app_group_menu}}".
 *
 * @property int $id
 * @property int $id_group
 * @property int $id_menu
 * @property int $active 0 = non aktif, 1= aktif
 */
class AppGroupMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%app_group_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_group', 'id_menu'], 'required'],
            [['id_group', 'id_menu', 'active'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_group' => Yii::t('app', 'Id Group'),
            'id_menu' => Yii::t('app', 'Id Menu'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(TbMenu::className(), ['id' => 'id_menu']);
    }

    /**
     * {@inheritdoc}
     * @return AppGroupMenuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppGroupMenuQuery(get_called_class());
    }
}
