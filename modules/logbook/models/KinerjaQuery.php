<?php

namespace app\modules\logbook\models;

/**
 * This is the ActiveQuery class for [[Kinerja]].
 *
 * @see Kinerja
 */
class KinerjaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Kinerja[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Kinerja|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
