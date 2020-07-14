<?php

namespace app\modules\pegawai\models;

use Yii;

/**
 * This is the model class for table "direktorat".
 *
 * @property string $id_direktorat
 * @property string $nama_direktorat
 * @property int $status 1 = aktif, 0 = nonaktif
 * @property string $tmt_aktif
 *
 * @property Bagian[] $bagians
 */
class Direktorat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'direktorat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_direktorat', 'nama_direktorat', 'status', 'tmt_aktif'], 'required'],
            [['status'], 'integer'],
            [['tmt_aktif'], 'safe'],
            [['id_direktorat'], 'string', 'max' => 2],
            [['nama_direktorat'], 'string', 'max' => 100],
            [['id_direktorat'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_direktorat' => Yii::t('app', 'ID Direktorat'),
            'nama_direktorat' => Yii::t('app', 'Nama Direktorat'),
            'status' => Yii::t('app', 'Status'),
            'tmt_aktif' => Yii::t('app', 'Tanggal Aktif'),
        ];
    }

    /**
     * Gets query for [[Bagians]].
     *
     * @return \yii\db\ActiveQuery|BagianQuery
     */
    public function getBagians()
    {
        return $this->hasMany(Bagian::className(), ['id_direktorat' => 'id_direktorat']);
    }

    /**
     * {@inheritdoc}
     * @return DirektoratQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DirektoratQuery(get_called_class());
    }

    public function beforeValidate(){
        if(parent::beforeValidate()){
           
            if($this->tmt_aktif != null){
                $this->tmt_aktif = date('Y-m-d', strtotime($this->tmt_aktif));
            }
            

            return true;
        }

        return false;
    }
}
