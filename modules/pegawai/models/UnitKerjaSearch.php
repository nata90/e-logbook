<?php

namespace app\modules\pegawai\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pegawai\models\UnitKerja;

/**
 * UnitKerjaSearch represents the model behind the search form of `app\modules\pegawai\models\UnitKerja`.
 */
class UnitKerjaSearch extends UnitKerja
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_unit_kerja', 'id_bagian', 'nama_unit_kerja', 'tmt_aktif'], 'safe'],
            [['status_unit'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UnitKerja::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'status_unit' => $this->status_unit,
            'tmt_aktif' => $this->tmt_aktif,
        ]);

        $query->andFilterWhere(['like', 'id_unit_kerja', $this->id_unit_kerja])
            ->andFilterWhere(['like', 'id_bagian', $this->id_bagian])
            ->andFilterWhere(['like', 'nama_unit_kerja', $this->nama_unit_kerja]);

        return $dataProvider;
    }
}
