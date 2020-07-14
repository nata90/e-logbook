<?php

namespace app\modules\base\models;

use Yii;

/**
 * This is the model class for table "backlog".
 *
 * @property int $id
 * @property string $butir_kegiatan
 * @property string $deskripsi
 * @property string $tgl_entri
 * @property int $row
 */
class Backlog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'backlog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['butir_kegiatan', 'deskripsi', 'tgl_entri', 'row', 'jumlah'], 'required'],
            [['butir_kegiatan', 'deskripsi'], 'string'],
            [['tgl_entri'], 'safe'],
            [['row'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'butir_kegiatan' => Yii::t('app', 'Butir Kegiatan'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
            'tgl_entri' => Yii::t('app', 'Tgl Entri'),
            'row' => Yii::t('app', 'Row'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return BacklogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BacklogQuery(get_called_class());
    }
}
