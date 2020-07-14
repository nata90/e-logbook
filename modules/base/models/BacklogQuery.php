<?php

namespace app\modules\base\models;

/**
 * This is the ActiveQuery class for [[Backlog]].
 *
 * @see Backlog
 */
class BacklogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Backlog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Backlog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
