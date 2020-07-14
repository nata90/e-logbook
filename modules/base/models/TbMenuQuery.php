<?php

namespace app\modules\base\models;

/**
 * This is the ActiveQuery class for [[TbMenu]].
 *
 * @see TbMenu
 */
class TbMenuQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TbMenu[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TbMenu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
