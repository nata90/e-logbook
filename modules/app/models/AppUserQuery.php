<?php

namespace app\modules\app\models;

/**
 * This is the ActiveQuery class for [[AppUser]].
 *
 * @see AppUser
 */
class AppUserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AppUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AppUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
