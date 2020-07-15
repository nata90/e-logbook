<?php

namespace app\modules\pegawai\models;

use Yii;

/**
 * This is the model class for table "{{%pegawai_unit_kerja}}".
 *
 * @property int $id_pegawai_unit_kerja
 * @property string $id_unit_kerja
 * @property int $id_pegawai
 * @property int $status_peg
 * @property string $tmt_peg
 *
 * @property UnitKerja $unitKerja
 * @property DataPegawai $pegawai
 */
class PegawaiUnitKerja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pegawai_unit_kerja}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_unit_kerja', 'id_pegawai', 'status_peg', 'tmt_peg'], 'required'],
            [['id_pegawai', 'status_peg'], 'integer'],
            [['tmt_peg'], 'safe'],
            [['id_unit_kerja'], 'string', 'max' => 5],
            [['id_unit_kerja'], 'exist', 'skipOnError' => true, 'targetClass' => UnitKerja::className(), 'targetAttribute' => ['id_unit_kerja' => 'id_unit_kerja']],
            [['id_pegawai'], 'exist', 'skipOnError' => true, 'targetClass' => DataPegawai::className(), 'targetAttribute' => ['id_pegawai' => 'id_pegawai']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pegawai_unit_kerja' => Yii::t('app', 'Id Pegawai Unit Kerja'),
            'id_unit_kerja' => Yii::t('app', 'Id Unit Kerja'),
            'id_pegawai' => Yii::t('app', 'Id Pegawai'),
            'status_peg' => Yii::t('app', 'Status Peg'),
            'tmt_peg' => Yii::t('app', 'Tmt Peg'),
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
     * Gets query for [[Pegawai]].
     *
     * @return \yii\db\ActiveQuery|DataPegawaiQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(DataPegawai::className(), ['id_pegawai' => 'id_pegawai']);
    }

    /**
     * {@inheritdoc}
     * @return PegawaiUnitKerjaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PegawaiUnitKerjaQuery(get_called_class());
    }
}
