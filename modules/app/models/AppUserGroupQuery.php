<?php

namespace app\modules\app\models;

/**
 * This is the ActiveQuery class for [[AppUserGroup]].
 *
 * @see AppUserGroup
 */
class AppUserGroupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AppUserGroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AppUserGroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
