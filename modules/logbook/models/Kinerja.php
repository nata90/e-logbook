<?php

namespace app\modules\logbook\models;

use Yii;
use app\modules\pegawai\models\DataPegawai;

/**
 * This is the model class for table "{{%kinerja}}".
 *
 * @property int $id_kinerja
 * @property string|null $tanggal_kinerja
 * @property int|null $id_pegawai
 * @property string|null $id_tugas
 * @property int|null $jumlah
 * @property string|null $deskripsi
 * @property int|null $approval
 * @property int|null $user_approval
 * @property string|null $tgl_approval
 * @property string|null $create_date
 *
 * @property Tuga $tugas
 * @property DataPegawai $pegawai
 * @property DataPegawai $userApproval
 */
class Kinerja extends \yii\db\ActiveRecord
{
    public $range_date;
    public $nama_kategori;
    public $poin_kategori;
    public $nama_tugas;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%kinerja}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tanggal_kinerja', 'create_date', 'id_pegawai', 'jumlah','deskripsi','id_tugas'], 'required'],
            [['tanggal_kinerja', 'tgl_approval', 'create_date','row','range_date'], 'safe'],
            [['id_pegawai', 'jumlah', 'approval', 'user_approval'], 'integer'],
            [['deskripsi'], 'string'],
            [['id_tugas'], 'string', 'max' => 10],
            [['id_tugas'], 'exist', 'skipOnError' => true, 'targetClass' => Tugas::className(), 'targetAttribute' => ['id_tugas' => 'id_tugas']],
            [['id_pegawai'], 'exist', 'skipOnError' => true, 'targetClass' => DataPegawai::className(), 'targetAttribute' => ['id_pegawai' => 'id_pegawai']],
            [['user_approval'], 'exist', 'skipOnError' => true, 'targetClass' => DataPegawai::className(), 'targetAttribute' => ['user_approval' => 'id_pegawai']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_kinerja' => Yii::t('app', 'Id Kinerja'),
            'tanggal_kinerja' => Yii::t('app', 'Tanggal Kinerja'),
            'id_pegawai' => Yii::t('app', 'Id Pegawai'),
            'id_tugas' => Yii::t('app', 'Tugas'),
            'jumlah' => Yii::t('app', 'Jumlah'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
            'approval' => Yii::t('app', 'Approval'),
            'user_approval' => Yii::t('app', 'User Approval'),
            'tgl_approval' => Yii::t('app', 'Tgl Approval'),
            'create_date' => Yii::t('app', 'Create Date'),
        ];
    }

    public static function RangePeriodeIki(){
        $arr_date = [26,27,28,29,30,31];
        if(in_array(date('d'),$arr_date)){
            $mktime_start = mktime(0, 0, 0, date("m"), 26, date("Y"));
            $date_start = date("m/d/Y", $mktime_start);

            $mktime_end = mktime(0, 0, 0, date("m")+1, 25, date("Y"));
            $date_end = date("m/d/Y", $mktime_end);
        }else{
            $mktime_start = mktime(0, 0, 0, date("m")-1, 26, date("Y"));
            $date_start = date("m/d/Y", $mktime_start);

            $mktime_end = mktime(0, 0, 0, date("m"), 25, date("Y"));
            $date_end = date("m/d/Y", $mktime_end);
        }

        return $date_start.' - '.$date_end;
        
    }


    /**
     * Gets query for [[Tugas]].
     *
     * @return \yii\db\ActiveQuery|TugaQuery
     */
    public function getTugas()
    {
        return $this->hasOne(Tugas::className(), ['id_tugas' => 'id_tugas']);
    }

    /**
     * Gets query for [[Pegawai]].
     *
     * @return \yii\db\ActiveQuery|DataPegawaiQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(DataPegawai::className(), ['id_pegawai' => 'id_pegawai']);
    }

    /**
     * Gets query for [[UserApproval]].
     *
     * @return \yii\db\ActiveQuery|DataPegawaiQuery
     */
    public function getUserApproval()
    {
        return $this->hasOne(DataPegawai::className(), ['id_pegawai' => 'user_approval']);
    }

    /**
     * {@inheritdoc}
     * @return KinerjaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new KinerjaQuery(get_called_class());
    }
}
