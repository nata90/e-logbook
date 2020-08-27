<?php

namespace app\modules\pegawai\models;

use Yii;
use app\modules\logbook\models\Target;

/**
 * This is the model class for table "data_pegawai".
 *
 * @property int $id_pegawai
 * @property string $nip
 * @property int $pin ID Presensi
 * @property string $nama
 * @property string $tmp_lahir
 * @property string $tgl_lahir
 * @property int $jenis_peg 0 = PNS, 1 = Non PNS, 2 = kontrak
 * @property int $status_peg 0 = Masih Bekerja, 1 = Pensiun, 2 = Pindah ke Luar RS, 3 = Meninggal
 * @property int $gender 0 = Pria, 1 = Wanita
 * @property string $username
 * @property string $password
 *
 * @property JabatanPegawai[] $jabatanPegawais
 * @property Kinerja[] $kinerjas
 * @property Kinerja[] $kinerjas0
 * @property PegawaiUnitKerja[] $pegawaiUnitKerjas
 */
class DataPegawai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_pegawai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nip', 'nama', 'tmp_lahir', 'tgl_lahir', 'jenis_peg', 'status_peg', 'gender'], 'required'],
            [['pin', 'jenis_peg', 'status_peg', 'gender','update'], 'integer'],
            [['tgl_lahir','update'], 'safe'],
            [['nip', 'tmp_lahir'], 'string', 'max' => 20],
            [['nama'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pegawai' => Yii::t('app', 'Id Pegawai'),
            'nip' => Yii::t('app', 'NIP / NIK'),
            'pin' => Yii::t('app', 'PIN'),
            'nama' => Yii::t('app', 'Nama'),
            'tmp_lahir' => Yii::t('app', 'Tempat Lahir'),
            'tgl_lahir' => Yii::t('app', 'Tanggal Lahir'),
            'jenis_peg' => Yii::t('app', 'Jenis Pegawai'),
            'status_peg' => Yii::t('app', 'Status Pegawai'),
            'gender' => Yii::t('app', 'Jenis Kelamin'),
        ];
    }

    /**
     * Gets query for [[JabatanPegawais]].
     *
     * @return \yii\db\ActiveQuery|JabatanPegawaiQuery
     */
    public function getJabatanPegawais()
    {
        return $this->hasMany(JabatanPegawai::className(), ['id_pegawai' => 'id_pegawai'])->onCondition(['status_jbt' => 1]);
    }

    /**
     * Gets query for [[Kinerjas]].
     *
     * @return \yii\db\ActiveQuery|KinerjaQuery
     */
    public function getKinerjas()
    {
        return $this->hasMany(Kinerja::className(), ['id_pegawai' => 'id_pegawai']);
    }

    /**
     * Gets query for [[Kinerjas0]].
     *
     * @return \yii\db\ActiveQuery|KinerjaQuery
     */
    public function getKinerjas0()
    {
        return $this->hasMany(Kinerja::className(), ['user_approval' => 'id_pegawai']);
    }

    /**
     * Gets query for [[PegawaiUnitKerjas]].
     *
     * @return \yii\db\ActiveQuery|PegawaiUnitKerjaQuery
     */
    public function getPegawaiUnitKerjas()
    {
        return $this->hasMany(PegawaiUnitKerja::className(), ['id_pegawai' => 'id_pegawai'])->onCondition(['status_peg' => 1]);
    }

    public function getLackOfProfile($id_pegawai){
        $error_message = array();
        //cek unit kerja
        $model_unit = PegawaiUnitKerja::find()->where(['id_pegawai'=>$id_pegawai, 'status_peg'=>1])->one();

        if($model == null){
            $error_message[] = 'Anda belum memiliki unit kerja';
        }

        //cek jabatan pegawai
        $model_jabatan = JabatanPegawai::find()->where(['id_pegawai'=>$id_pegawai, 'status_jbt'=>1])->one();

        if($model_jabatan == null){
            $error_message[] = 'Anda belum memiliki jabatan';
        }else{
            //target jabatan
            $model_target = Target::find()->where(['id_jabatan'=>$model_jabatan->id_jabatan, 'status_target'=>1])->one();

            if($model_target == null){
                $error_message[] = 'Target jabatan anda belum di set';
            }
        }

        return $error_message;

    }

    /**
     * {@inheritdoc}
     * @return DataPegawaiQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DataPegawaiQuery(get_called_class());
    }

    public function beforeValidate(){
        if(parent::beforeValidate()){
           /* $authkey = md5(time());
            $this->password = md5($this->password.$authkey);
            $this->authkey = $authkey;*/
            if($this->tgl_lahir != null){
                $this->tgl_lahir = date('Y-m-d', strtotime($this->tgl_lahir));
            }
            

            return true;
        }

        return false;
    }
}
