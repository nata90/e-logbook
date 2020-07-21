<?php

namespace app\modules\pegawai\models;

/**
 * This is the ActiveQuery class for [[JabatanPegawai]].
 *
 * @see JabatanPegawai
 */
class JabatanPegawaiQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return JabatanPegawai[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return JabatanPegawai|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
