<?php

namespace app\modules\pegawai\models;

use Yii;

/**
 * This is the model class for table "{{%unit_kerja}}".
 *
 * @property string $id_unit_kerja
 * @property string $id_bagian
 * @property string $nama_unit_kerja
 * @property int $status_unit
 * @property string $tmt_aktif
 *
 * @property PegawaiUnitKerja[] $pegawaiUnitKerjas
 * @property Target[] $targets
 * @property Bagian $bagian
 */
class UnitKerja extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%unit_kerja}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_unit_kerja', 'id_bagian', 'nama_unit_kerja', 'status_unit', 'tmt_aktif'], 'required'],
            [['status_unit'], 'integer'],
            [['tmt_aktif'], 'safe'],
            [['id_unit_kerja', 'id_bagian'], 'string', 'max' => 5],
            [['nama_unit_kerja'], 'string', 'max' => 250],
            [['id_unit_kerja'], 'unique'],
            [['id_bagian'], 'exist', 'skipOnError' => true, 'targetClass' => Bagian::className(), 'targetAttribute' => ['id_bagian' => 'id_bagian']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_unit_kerja' => Yii::t('app', 'ID Unit Kerja'),
            'id_bagian' => Yii::t('app', 'Bagian'),
            'nama_unit_kerja' => Yii::t('app', 'Nama Unit Kerja'),
            'status_unit' => Yii::t('app', 'Status Unit'),
            'tmt_aktif' => Yii::t('app', 'Tmt Aktif'),
        ];
    }

    /**
     * Gets query for [[PegawaiUnitKerjas]].
     *
     * @return \yii\db\ActiveQuery|PegawaiUnitKerjaQuery
     */
    public function getPegawaiUnitKerjas()
    {
        return $this->hasMany(PegawaiUnitKerja::className(), ['id_unit_kerja' => 'id_unit_kerja']);
    }

    /**
     * Gets query for [[Targets]].
     *
     * @return \yii\db\ActiveQuery|TargetQuery
     */
    public function getTargets()
    {
        return $this->hasMany(Target::className(), ['id_unit_kerja' => 'id_unit_kerja']);
    }

    /**
     * Gets query for [[Bagian]].
     *
     * @return \yii\db\ActiveQuery|BagianQuery
     */
    public function getBagian()
    {
        return $this->hasOne(Bagian::className(), ['id_bagian' => 'id_bagian']);
    }

    /**
     * {@inheritdoc}
     * @return UnitKerjaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UnitKerjaQuery(get_called_class());
    }
}
