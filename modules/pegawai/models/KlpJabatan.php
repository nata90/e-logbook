<?php

namespace app\modules\pegawai\models;

use Yii;

/**
 * This is the model class for table "{{%klp_jabatan}}".
 *
 * @property int $id_klp_jabatan
 * @property string $kode_klp_jabatan
 * @property string $nama_klp_jabatan
 * @property string $deskripsi
 * @property int $status_klp_jabatan
 *
 * @property GradeJabatan[] $gradeJabatans
 */
class KlpJabatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%klp_jabatan}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_klp_jabatan', 'nama_klp_jabatan', 'deskripsi', 'status_klp_jabatan'], 'required'],
            [['deskripsi'], 'string'],
            [['status_klp_jabatan'], 'integer'],
            [['kode_klp_jabatan'], 'unique'],
            [['kode_klp_jabatan'], 'string', 'max' => 5],
            [['nama_klp_jabatan'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_klp_jabatan' => Yii::t('app', 'Id Klp Jabatan'),
            'kode_klp_jabatan' => Yii::t('app', 'Kode Kelompok Jabatan'),
            'nama_klp_jabatan' => Yii::t('app', 'Nama Kelompok Jabatan'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
            'status_klp_jabatan' => Yii::t('app', 'Status Kelomlpok Jabatan'),
        ];
    }

    /**
     * Gets query for [[GradeJabatans]].
     *
     * @return \yii\db\ActiveQuery|GradeJabatanQuery
     */
    public function getGradeJabatans()
    {
        return $this->hasMany(GradeJabatan::className(), ['id_klp_jabatan' => 'id_klp_jabatan']);
    }

    /**
     * {@inheritdoc}
     * @return KlpJabatanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KlpJabatanQuery(get_called_class());
    }
}
