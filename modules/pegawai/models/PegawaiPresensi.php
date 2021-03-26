<?php

namespace app\modules\pegawai\models;

use Yii;

/**
 * This is the model class for table "pegawai".
 *
 * @property int $pegawai_id
 * @property string $pegawai_pin
 * @property string|null $pegawai_nip
 * @property string $pegawai_nama
 * @property string|null $pegawai_alias
 * @property string $pegawai_pwd
 * @property string $pegawai_rfid
 * @property string $pegawai_privilege -1: Invalid, 0: User,  1: Operator, 2: Sub Admin, 3: Admin
 * @property string|null $pegawai_telp
 * @property int $pegawai_status 0:Non Aktif; 1:Aktif; 2:Berhenti
 * @property string|null $tempat_lahir
 * @property string|null $tgl_lahir
 * @property int|null $pembagian1_id
 * @property int|null $pembagian2_id
 * @property int|null $pembagian3_id
 * @property string|null $tgl_mulai_kerja
 * @property string|null $tgl_resign
 * @property int $gender 1:Laki-laki, 2:Perempuan
 * @property string|null $tgl_masuk_pertama
 * @property string|null $photo_path
 * @property string|null $tmp_img
 * @property string|null $nama_bank
 * @property string|null $nama_rek
 * @property string|null $no_rek
 * @property int|null $new_pegawai_id
 * @property string|null $password
 * @property int $reset_device
 */
class PegawaiPresensi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pegawai';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_presensi');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pegawai_id', 'pegawai_pin', 'pegawai_nama'], 'required'],
            [['pegawai_id', 'pegawai_status', 'pembagian1_id', 'pembagian2_id', 'pembagian3_id', 'gender', 'new_pegawai_id', 'reset_device'], 'integer'],
            [['tgl_lahir', 'tgl_mulai_kerja', 'tgl_resign', 'tgl_masuk_pertama'], 'safe'],
            [['tmp_img'], 'string'],
            [['pegawai_pin', 'pegawai_rfid'], 'string', 'max' => 32],
            [['pegawai_nip'], 'string', 'max' => 30],
            [['pegawai_nama', 'photo_path'], 'string', 'max' => 255],
            [['pegawai_alias', 'pegawai_privilege', 'tempat_lahir', 'nama_bank'], 'string', 'max' => 50],
            [['pegawai_pwd'], 'string', 'max' => 10],
            [['pegawai_telp', 'no_rek'], 'string', 'max' => 20],
            [['nama_rek'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 45],
            [['pegawai_pin'], 'unique'],
            [['pegawai_nip'], 'unique'],
            [['pegawai_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pegawai_id' => 'Pegawai ID',
            'pegawai_pin' => 'Pegawai Pin',
            'pegawai_nip' => 'Pegawai Nip',
            'pegawai_nama' => 'Pegawai Nama',
            'pegawai_alias' => 'Pegawai Alias',
            'pegawai_pwd' => 'Pegawai Pwd',
            'pegawai_rfid' => 'Pegawai Rfid',
            'pegawai_privilege' => 'Pegawai Privilege',
            'pegawai_telp' => 'Pegawai Telp',
            'pegawai_status' => 'Pegawai Status',
            'tempat_lahir' => 'Tempat Lahir',
            'tgl_lahir' => 'Tgl Lahir',
            'pembagian1_id' => 'Pembagian1 ID',
            'pembagian2_id' => 'Pembagian2 ID',
            'pembagian3_id' => 'Pembagian3 ID',
            'tgl_mulai_kerja' => 'Tgl Mulai Kerja',
            'tgl_resign' => 'Tgl Resign',
            'gender' => 'Gender',
            'tgl_masuk_pertama' => 'Tgl Masuk Pertama',
            'photo_path' => 'Photo Path',
            'tmp_img' => 'Tmp Img',
            'nama_bank' => 'Nama Bank',
            'nama_rek' => 'Nama Rek',
            'no_rek' => 'No Rek',
            'new_pegawai_id' => 'New Pegawai ID',
            'password' => 'Password',
            'reset_device' => 'Reset Device',
        ];
    }
}
