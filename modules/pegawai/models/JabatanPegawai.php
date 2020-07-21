<?php

namespace app\modules\pegawai\models;

use Yii;

/**
 * This is the model class for table "{{%jabatan_pegawai}}".
 *
 * @property int $id_jbt_pegawai
 * @property int $id_jabatan
 * @property int $id_pegawai
 * @property int $status_jbt
 * @property string $tmt_jbt
 *
 * @property DataPegawai $pegawai
 * @property Jabatan $jabatan
 */
class JabatanPegawai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%jabatan_pegawai}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jabatan', 'id_pegawai', 'status_jbt', 'tmt_jbt'], 'required'],
            [['id_jabatan', 'id_pegawai', 'status_jbt'], 'integer'],
            [['tmt_jbt'], 'safe'],
            [['id_pegawai'], 'exist', 'skipOnError' => true, 'targetClass' => DataPegawai::className(), 'targetAttribute' => ['id_pegawai' => 'id_pegawai']],
            [['id_jabatan'], 'exist', 'skipOnError' => true, 'targetClass' => Jabatan::className(), 'targetAttribute' => ['id_jabatan' => 'id_jabatan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_jbt_pegawai' => Yii::t('app', 'Id Jbt Pegawai'),
            'id_jabatan' => Yii::t('app', 'Jabatan'),
            'id_pegawai' => Yii::t('app', 'Id Pegawai'),
            'status_jbt' => Yii::t('app', 'Status'),
            'tmt_jbt' => Yii::t('app', 'Tmt Jbt'),
        ];
    }

    /**
     * Gets query for [[Pegawai]].
     *
     * @return \yii\db\ActiveQuery|DataPegawaiQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(DataPegawai::className(), ['id_pegawai' => 'id_pegawai']);
    }

    /**
     * Gets query for [[Jabatan]].
     *
     * @return \yii\db\ActiveQuery|JabatanQuery
     */
    public function getJabatan()
    {
        return $this->hasOne(Jabatan::className(), ['id_jabatan' => 'id_jabatan']);
    }

    /**
     * {@inheritdoc}
     * @return JabatanPegawaiQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JabatanPegawaiQuery(get_called_class());
    }
}
