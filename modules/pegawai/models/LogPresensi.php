<?php

namespace app\modules\pegawai\models;

use Yii;

/**
 * This is the model class for table "att_log".
 *
 * @property string $sn
 * @property string $scan_date
 * @property string $pin
 * @property int $verifymode
 * @property int $inoutmode
 * @property string $att_id
 * @property string|null $device_ip
 */
class LogPresensi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'att_log';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_presensi');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sn', 'scan_date', 'pin', 'verifymode'], 'required'],
            [['scan_date'], 'safe'],
            [['verifymode', 'inoutmode'], 'integer'],
            [['sn'], 'string', 'max' => 30],
            [['pin'], 'string', 'max' => 32],
            [['att_id', 'device_ip'], 'string', 'max' => 50],
            [['sn', 'scan_date', 'pin'], 'unique', 'targetAttribute' => ['sn', 'scan_date', 'pin']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sn' => Yii::t('app', 'Sn'),
            'scan_date' => Yii::t('app', 'Scan Date'),
            'pin' => Yii::t('app', 'Pin'),
            'verifymode' => Yii::t('app', 'Verifymode'),
            'inoutmode' => Yii::t('app', 'Inoutmode'),
            'att_id' => Yii::t('app', 'Att ID'),
            'device_ip' => Yii::t('app', 'Device Ip'),
        ];
    }
}
