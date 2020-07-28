<?php

namespace app\modules\app\models;

use Yii;
use app\modules\pegawai\models\DataPegawai;

/**
 * This is the model class for table "app_user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $authkey
 * @property int $active
 * @property int $pegawai_id
 */
class AppUser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $pegawai_nama;
    public $new_password;

    const SCENARIO_ADD = 'add';
    const SCENARIO_UPDATE = 'update';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_user';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => ['username', 'password', 'authkey', 'pegawai_id','active','pegawai_nama','id_group'],
            self::SCENARIO_UPDATE => ['username', 'pegawai_id','active','pegawai_nama','id_group'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'authkey', 'pegawai_id','pegawai_nama','id_group'], 'required','on' => self::SCENARIO_ADD],
            [['username', 'pegawai_id','pegawai_nama','id_group'], 'required','on' => self::SCENARIO_UPDATE],
            [['active', 'pegawai_id','id_group'], 'integer'],
            [['username'], 'string', 'max' => 20],
            [['username'], 'unique'],
            [['password', 'authkey','new_password'], 'string', 'max' => 100],
            [['pegawai_nama'], 'string', 'max' => 300],
            [['accessToken'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'new_password' => Yii::t('app', 'Password Baru'),
            'authkey' => Yii::t('app', 'Authkey'),
            'active' => Yii::t('app', 'Aktif'),
            'pegawai_id' => Yii::t('app', 'Pegawai ID'),
            'pegawai_nama' => Yii::t('app', 'Nama Pegawai'),
            'accessToken' => Yii::t('app', 'Akses Token'),

        ];
    }

    /**
     * {@inheritdoc}
     * @return AppUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppUserQuery(get_called_class());
    }

    public function beforeValidate(){
        if(parent::beforeValidate()){
            $action = Yii::$app->controller->action->id;

            if($action != 'update'){
                $authkey = md5(time());
                $this->password = md5($this->password.$authkey);
                $this->authkey = $authkey;
            }
            

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(DataPegawai::className(), ['id_pegawai' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(AppUserGroup::className(), ['id' => 'id_group']);
    }

    public static function findIdentity($id){
        return AppUser::findOne($id);
    }

    public static function findByUsername($username){
        return AppUser::findOne(['username'=>$username,'active'=>1]);
    }

    public function getId(){
        return $this->id;
    }

    public function getAuthKey(){
        return $this->authkey;
    }

    public function validateAuthKey($authKey){
        return $this->authkey === $authKey;
    }

    public function validatePassword($password, $authkey){
        return $this->password === md5($password.$authkey);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return AppUser::findOne(['accessToken'=>$token]);
    }
}
