<?php

namespace app\modules\pegawai\models;

/**
 * This is the ActiveQuery class for [[GradeJabatan]].
 *
 * @see GradeJabatan
 */
class GradeJabatanQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return GradeJabatan[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return GradeJabatan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
