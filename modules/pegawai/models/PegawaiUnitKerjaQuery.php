<?php

namespace app\modules\pegawai\models;

/**
 * This is the ActiveQuery class for [[PegawaiUnitKerja]].
 *
 * @see PegawaiUnitKerja
 */
class PegawaiUnitKerjaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PegawaiUnitKerja[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PegawaiUnitKerja|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
