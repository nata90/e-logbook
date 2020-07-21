<?php

namespace app\modules\pegawai\models;

use Yii;

/**
 * This is the model class for table "{{%jabatan}}".
 *
 * @property int $id_jabatan
 * @property int $id_grade
 * @property string $nama_jabatan
 * @property int $level_jabatan 0 = Dirut, 1 = Direktur, 2 = Kabag/Kabid, 3 = Kasubbag/Kasi, 4 = Ka.Instalasi, 5 = PJ, 6 = Staf
 * @property int|null $peer_grup 0 = Medis, 1 = Perawat, 2 = Penunjang Medis, 3 = Non Medis
 * @property int $status_jabatan
 * @property string $tmt_jabatan
 *
 * @property GradeJabatan $grade
 * @property JabatanPegawai[] $jabatanPegawais
 * @property Target[] $targets
 */
class Jabatan extends \yii\db\ActiveRecord
{
    public $id_klp_jabatan;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%jabatan}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_grade', 'nama_jabatan', 'level_jabatan', 'status_jabatan', 'tmt_jabatan'], 'required'],
            [['id_grade', 'level_jabatan', 'peer_grup', 'status_jabatan'], 'integer'],
            [['tmt_jabatan'], 'safe'],
            [['nama_jabatan'], 'string', 'max' => 200],
            [['id_grade'], 'exist', 'skipOnError' => true, 'targetClass' => GradeJabatan::className(), 'targetAttribute' => ['id_grade' => 'id_grade']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_jabatan' => Yii::t('app', 'Id Jabatan'),
            'id_grade' => Yii::t('app', 'Grade'),
            'nama_jabatan' => Yii::t('app', 'Nama Jabatan'),
            'level_jabatan' => Yii::t('app', 'Level Jabatan'),
            'peer_grup' => Yii::t('app', 'Peer Grup'),
            'status_jabatan' => Yii::t('app', 'Status Jabatan'),
            'tmt_jabatan' => Yii::t('app', 'Tanggal Mulai Jabatan'),
        ];
    }

    public static function getLevelJabatan($key = null){
        $level_jabatan = [0=>'Dirut', 1=>'Direktur', 2=>'Kabag/Kabid', 3=>'Kasubbag/Kasi', 4=>'Ka.Instalasi', 5=>'PJ', 6=>'Staff'];
        
        if($key === null){
            return $level_jabatan;
        }else{
            return $level_jabatan[$key];
        }

    }

    public static function getPeerGroup($pg = null){
        $peer_group = [0=>'Medis', 1=>'Perawat', 2=>'Penunjang Medis', 3=>'Non Medis'];
        
        if($pg === null){
            return $peer_group;
        }else{
            return $peer_group[$pg];
        }
        
    }
    /**
     * Gets query for [[Grade]].
     *
     * @return \yii\db\ActiveQuery|GradeJabatanQuery
     */
    public function getGrade()
    {
        return $this->hasOne(GradeJabatan::className(), ['id_grade' => 'id_grade']);
    }

    /**
     * Gets query for [[JabatanPegawais]].
     *
     * @return \yii\db\ActiveQuery|JabatanPegawaiQuery
     */
    public function getJabatanPegawais()
    {
        return $this->hasMany(JabatanPegawai::className(), ['id_jabatan' => 'id_jabatan']);
    }

    /**
     * Gets query for [[Targets]].
     *
     * @return \yii\db\ActiveQuery|TargetQuery
     */
    public function getTargets()
    {
        return $this->hasMany(Target::className(), ['id_jabatan' => 'id_jabatan']);
    }

    /**
     * {@inheritdoc}
     * @return JabatanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JabatanQuery(get_called_class());
    }

    public function beforeValidate(){
        if(parent::beforeValidate()){
           
            if($this->tmt_jabatan != null){
                $this->tmt_jabatan = date('Y-m-d', strtotime($this->tmt_jabatan));
            }
            

            return true;
        }

        return false;
    }
}
