<?php

namespace app\modules\base\models;

/**
 * This is the ActiveQuery class for [[AppGroupMenu]].
 *
 * @see AppGroupMenu
 */
class AppGroupMenuQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return AppGroupMenu[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AppGroupMenu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
