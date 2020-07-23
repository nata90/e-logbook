<?php

namespace app\modules\logbook\models;

/**
 * This is the ActiveQuery class for [[Target]].
 *
 * @see Target
 */
class TargetQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Target[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Target|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
