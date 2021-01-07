<?php

namespace app\modules\app\models;

use Yii;

/**
 * This is the model class for table "app_setting".
 *
 * @property int $id
 * @property string $mail_admin
 * @property int $tgl_periode_awal
 * @property int $tgl_periode_akhir
 */
class AppSetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mail_admin', 'tgl_periode_awal', 'tgl_periode_akhir'], 'required'],
            [['tgl_periode_awal', 'tgl_periode_akhir'], 'integer'],
            [['mail_admin'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mail_admin' => Yii::t('app', 'Email Admin'),
            'tgl_periode_awal' => Yii::t('app', 'Tgl Periode Mulai'),
            'tgl_periode_akhir' => Yii::t('app', 'Tgl Periode Akhir'),
        ];
    }
}
