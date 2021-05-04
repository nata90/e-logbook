<?php

namespace app\models;

use app\modules\app\models\AppUser;
use app\modules\app\models\AppUserQuery;
use app\modules\app\models\AppUserSearch;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

    /**
     * {@inheritdoc}
     * @return AppUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AppUserQuery(get_called_class());
    }


     public static function findIdentity($id){
        return AppUser::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        //return AppUser::findOne(['accessToken'=>$token]);
        foreach (self::$users as $user) {
            if ($user['id'] === (string) $token->getClaim('uid')) {
                return new static($user);
            }
        }

        return null;
    }

    public static function findByUsername($username){
        return AppUser::findOne(['username'=>$username]);
    }


    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password, $authkey)
    {
        return $this->password === md5($password.$authkey);
    }

    
}
