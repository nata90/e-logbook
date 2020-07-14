<?php

namespace app\modules\pegawai\models;

/**
 * This is the ActiveQuery class for [[Direktorat]].
 *
 * @see Direktorat
 */
class DirektoratQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Direktorat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Direktorat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
