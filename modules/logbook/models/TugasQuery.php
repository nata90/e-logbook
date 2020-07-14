<?php

namespace app\modules\logbook\models;

/**
 * This is the ActiveQuery class for [[Tugas]].
 *
 * @see Tugas
 */
class TugasQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Tugas[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Tugas|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
