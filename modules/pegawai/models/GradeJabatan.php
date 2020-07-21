<?php

namespace app\modules\pegawai\models;

use Yii;

/**
 * This is the model class for table "{{%grade_jabatan}}".
 *
 * @property int $id_grade
 * @property int $id_klp_jabatan
 * @property string $kode_grade
 * @property int $grade
 * @property string $deskripsi
 * @property int $nilai_jbt_max
 * @property int $nilai_jbt_min
 * @property int $nilai_jbt
 * @property int $status_grade
 *
 * @property KlpJabatan $klpJabatan
 * @property Jabatan[] $jabatans
 */
class GradeJabatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%grade_jabatan}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_klp_jabatan', 'kode_grade', 'grade', 'deskripsi', 'nilai_jbt_max', 'nilai_jbt_min', 'nilai_jbt', 'status_grade'], 'required'],
            [['id_klp_jabatan', 'grade', 'nilai_jbt_max', 'nilai_jbt_min', 'nilai_jbt', 'status_grade'], 'integer'],
            [['deskripsi'], 'string'],
            [['kode_grade'], 'unique'],
            [['kode_grade'], 'string', 'max' => 50],
            [['id_klp_jabatan'], 'exist', 'skipOnError' => true, 'targetClass' => KlpJabatan::className(), 'targetAttribute' => ['id_klp_jabatan' => 'id_klp_jabatan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_grade' => Yii::t('app', 'Id Grade'),
            'id_klp_jabatan' => Yii::t('app', 'Kelompok Jabatan'),
            'kode_grade' => Yii::t('app', 'Kode Grade'),
            'grade' => Yii::t('app', 'Grade'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
            'nilai_jbt_max' => Yii::t('app', 'Nilai Jabatan Max'),
            'nilai_jbt_min' => Yii::t('app', 'Nilai Jabatan Min'),
            'nilai_jbt' => Yii::t('app', 'Nilai Jabatan'),
            'status_grade' => Yii::t('app', 'Status Grade'),
        ];
    }

    /**
     * Gets query for [[KlpJabatan]].
     *
     * @return \yii\db\ActiveQuery|KlpJabatanQuery
     */
    public function getKlpjabatan()
    {
        return $this->hasOne(KlpJabatan::className(), ['id_klp_jabatan' => 'id_klp_jabatan']);
    }

    /**
     * Gets query for [[Jabatans]].
     *
     * @return \yii\db\ActiveQuery|JabatanQuery
     */
    public function getJabatans()
    {
        return $this->hasMany(Jabatan::className(), ['id_grade' => 'id_grade']);
    }

    /**
     * {@inheritdoc}
     * @return GradeJabatanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GradeJabatanQuery(get_called_class());
    }
}
