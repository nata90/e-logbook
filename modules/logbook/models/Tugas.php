<?php

namespace app\modules\logbook\models;

use Yii;
use yii\helpers\Json;
use app\modules\pegawai\models\UnitKerja;

/**
 * This is the model class for table "{{%tugas}}".
 *
 * @property string $id_tugas
 * @property int $id_kategori
 * @property string $nama_tugas
 * @property int $akses NULL = tertutup, 1 = terbuka, default NULL
 * @property int $status_tugas
 *
 * @property Kinerja[] $kinerjas
 * @property Kategori $kategori
 */
class Tugas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tugas}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tugas', 'id_kategori', 'nama_tugas', 'akses', 'status_tugas','id_unit_kerja'], 'required'],
            [['id_kategori', 'akses', 'status_tugas'], 'integer'],
            [['id_unit_kerja'], 'string', 'max' => 10],
            [['id_tugas'], 'string', 'max' => 20],
            [['nama_tugas'], 'string', 'max' => 250],
            [['id_tugas'], 'unique'],
            [['id_kategori'], 'exist', 'skipOnError' => true, 'targetClass' => Kategori::className(), 'targetAttribute' => ['id_kategori' => 'id_kategori']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_tugas' => Yii::t('app', 'ID Tugas'),
            'id_kategori' => Yii::t('app', 'Kategori'),
            'nama_tugas' => Yii::t('app', 'Nama Tugas'),
            'akses' => Yii::t('app', 'Akses'),
            'status_tugas' => Yii::t('app', 'Status Tugas'),
            'id_unit_kerja' => Yii::t('app', 'Unit Kerja'),
        ];
    }

    /**
     * Gets query for [[Kinerjas]].
     *
     * @return \yii\db\ActiveQuery|KinerjaQuery
     */
    public function getKinerjas()
    {
        return $this->hasMany(Kinerja::className(), ['id_tugas' => 'id_tugas']);
    }

    /**
     * Gets query for [[Kategori]].
     *
     * @return \yii\db\ActiveQuery|KategoriQuery
     */
    public function getKategori()
    {
        return $this->hasOne(Kategori::className(), ['id_kategori' => 'id_kategori']);
    }

    public function getUnitkerja()
    {
        return $this->hasOne(UnitKerja::className(), ['id_unit_kerja' => 'id_unit_kerja']);
    }

    /**
     * {@inheritdoc}
     * @return TugasQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TugasQuery(get_called_class());
    }
}
