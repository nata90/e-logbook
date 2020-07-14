<?php

namespace app\modules\pegawai\models;

use Yii;

/**
 * This is the model class for table "{{%bagian}}".
 *
 * @property string $id_bagian
 * @property string $id_direktorat
 * @property string $nama_bagian
 * @property int $status 1 = aktif, 0 = nonaktif
 * @property string $tmt_aktif
 *
 * @property Direktorat $direktorat
 * @property UnitKerja[] $unitKerjas
 */
class Bagian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bagian}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_bagian', 'id_direktorat', 'nama_bagian', 'status', 'tmt_aktif'], 'required'],
            [['status'], 'integer'],
            [['tmt_aktif'], 'safe'],
            [['id_bagian'], 'string', 'max' => 5],
            [['id_direktorat'], 'string', 'max' => 2],
            [['nama_bagian'], 'string', 'max' => 250],
            [['id_bagian'], 'unique'],
            [['id_direktorat'], 'exist', 'skipOnError' => true, 'targetClass' => Direktorat::className(), 'targetAttribute' => ['id_direktorat' => 'id_direktorat']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_bagian' => Yii::t('app', 'ID Bagian'),
            'id_direktorat' => Yii::t('app', 'Direktorat'),
            'nama_bagian' => Yii::t('app', 'Nama Bagian'),
            'status' => Yii::t('app', 'Status'),
            'tmt_aktif' => Yii::t('app', 'Tanggal Aktif'),
        ];
    }

    /**
     * Gets query for [[Direktorat]].
     *
     * @return \yii\db\ActiveQuery|DirektoratQuery
     */
    public function getDirektorat()
    {
        return $this->hasOne(Direktorat::className(), ['id_direktorat' => 'id_direktorat']);
    }

    /**
     * Gets query for [[UnitKerjas]].
     *
     * @return \yii\db\ActiveQuery|UnitKerjaQuery
     */
    public function getUnitKerjas()
    {
        return $this->hasMany(UnitKerja::className(), ['id_bagian' => 'id_bagian']);
    }

    /**
     * {@inheritdoc}
     * @return BagianQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BagianQuery(get_called_class());
    }

    public function beforeValidate(){
        if(parent::beforeValidate()){
           /* $authkey = md5(time());
            $this->password = md5($this->password.$authkey);
            $this->authkey = $authkey;*/
            if($this->tmt_aktif != null){
                $this->tmt_aktif = date('Y-m-d', strtotime($this->tmt_aktif));
            }
            

            return true;
        }

        return false;
    }
}
