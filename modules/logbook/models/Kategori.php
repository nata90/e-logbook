<?php

namespace app\modules\logbook\models;

use Yii;

/**
 * This is the model class for table "{{%kategori}}".
 *
 * @property int $id_kategori
 * @property string $nama_kategori
 * @property int $poin_kategori
 * @property int $status_kategori
 *
 * @property Tuga[] $tugas
 */
class Kategori extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%kategori}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_kategori', 'poin_kategori', 'status_kategori'], 'required'],
            [['poin_kategori', 'status_kategori'], 'integer'],
            [['nama_kategori'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_kategori' => Yii::t('app', 'Id Kategori'),
            'nama_kategori' => Yii::t('app', 'Nama Kategori'),
            'poin_kategori' => Yii::t('app', 'Poin Kategori'),
            'status_kategori' => Yii::t('app', 'Status Kategori'),
        ];
    }

    /**
     * Gets query for [[Tugas]].
     *
     * @return \yii\db\ActiveQuery|TugaQuery
     */
    public function getTugas()
    {
        return $this->hasMany(Tuga::className(), ['id_kategori' => 'id_kategori']);
    }

    /**
     * {@inheritdoc}
     * @return KategoriQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KategoriQuery(get_called_class());
    }
}
