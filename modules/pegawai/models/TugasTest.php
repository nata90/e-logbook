<?php

namespace app\modules\pegawai\models;

use Yii;

/**
 * This is the model class for table "tugas".
 *
 * @property string $id_tugas
 * @property int $id_kategori
 * @property string $nama_tugas
 * @property int $akses 0= tertutup, 1 = terbuka, default 0
 * @property int $status_tugas
 * @property string $id_unit_kerja
 *
 * @property Kinerja[] $kinerjas
 * @property Kategori $kategori
 */
class TugasTest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tugas';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_logbook_test');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tugas', 'id_kategori', 'nama_tugas', 'status_tugas', 'id_unit_kerja'], 'required'],
            [['id_kategori', 'akses', 'status_tugas'], 'integer'],
            [['id_tugas'], 'string', 'max' => 20],
            [['nama_tugas'], 'string', 'max' => 400],
            [['id_unit_kerja'], 'string', 'max' => 5],
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
            'id_tugas' => 'Id Tugas',
            'id_kategori' => 'Id Kategori',
            'nama_tugas' => 'Nama Tugas',
            'akses' => 'Akses',
            'status_tugas' => 'Status Tugas',
            'id_unit_kerja' => 'Id Unit Kerja',
        ];
    }

    /**
     * Gets query for [[Kinerjas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKinerjas()
    {
        return $this->hasMany(Kinerja::className(), ['id_tugas' => 'id_tugas']);
    }

    /**
     * Gets query for [[Kategori]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKategori()
    {
        return $this->hasOne(Kategori::className(), ['id_kategori' => 'id_kategori']);
    }
}
