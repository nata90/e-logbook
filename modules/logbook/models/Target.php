<?php

namespace app\modules\logbook\models;

use Yii;
use app\modules\pegawai\models\Jabatan;
use app\modules\pegawai\models\UnitKerja;

/**
 * This is the model class for table "{{%target}}".
 *
 * @property int $id_target
 * @property int $id_jabatan
 * @property string $id_unit_kerja
 * @property int $nilai_target
 * @property int $status_target
 *
 * @property UnitKerja $unitKerja
 * @property Jabatan $jabatan
 */
class Target extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%target}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jabatan', 'id_unit_kerja', 'nilai_target', 'status_target'], 'required'],
            [['id_jabatan', 'nilai_target', 'status_target'], 'integer'],
            [['id_unit_kerja'], 'string', 'max' => 5],
            [['id_unit_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => UnitKerja::className(), 'targetAttribute' => ['id_unit_kerja' => 'id_unit_kerja']],
            [['id_jabatan'], 'exist', 'skipOnError' => true, 'targetClass' => Jabatan::className(), 'targetAttribute' => ['id_jabatan' => 'id_jabatan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_target' => Yii::t('app', 'Id Target'),
            'id_jabatan' => Yii::t('app', 'Id Jabatan'),
            'id_unit_kerja' => Yii::t('app', 'Id Unit Kerja'),
            'nilai_target' => Yii::t('app', 'Nilai Target'),
            'status_target' => Yii::t('app', 'Status Target'),
        ];
    }

    /**
     * Gets query for [[UnitKerja]].
     *
     * @return \yii\db\ActiveQuery|UnitKerjaQuery
     */
    public function getUnitKerja()
    {
        return $this->hasOne(UnitKerja::className(), ['id_unit_kerja' => 'id_unit_kerja']);
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
     * @return TargetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TargetQuery(get_called_class());
    }
}
