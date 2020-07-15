<?php

namespace app\modules\pegawai\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pegawai\models\PegawaiUnitKerja;

/**
 * PegawaiUnitKerjaSearch represents the model behind the search form of `app\modules\pegawai\models\PegawaiUnitKerja`.
 */
class PegawaiUnitKerjaSearch extends PegawaiUnitKerja
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai_unit_kerja', 'id_pegawai', 'status_peg'], 'integer'],
            [['id_unit_kerja', 'tmt_peg'], 'safe'],
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
        $query = PegawaiUnitKerja::find();

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
            'id_pegawai_unit_kerja' => $this->id_pegawai_unit_kerja,
            'id_pegawai' => $this->id_pegawai,
            'status_peg' => 1,
            'tmt_peg' => $this->tmt_peg,
        ]);

        $query->andFilterWhere(['like', 'id_unit_kerja', $this->id_unit_kerja]);

        return $dataProvider;
    }
}
