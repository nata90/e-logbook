<?php

namespace app\modules\app\models;

use Yii;

/**
 * This is the model class for table "{{%app_user_group}}".
 *
 * @property int $id
 * @property string $nama_group
 * @property int|null $active
 */
class AppUserGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%app_user_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_group'], 'required'],
            [['active'], 'integer'],
            [['nama_group'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nama_group' => Yii::t('app', 'Nama Group'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AppUserGroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppUserGroupQuery(get_called_class());
    }
}
