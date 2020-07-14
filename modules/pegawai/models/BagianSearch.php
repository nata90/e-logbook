<?php

namespace app\modules\pegawai\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pegawai\models\Bagian;

/**
 * BagianSearch represents the model behind the search form of `app\modules\pegawai\models\Bagian`.
 */
class BagianSearch extends Bagian
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_bagian', 'id_direktorat', 'nama_bagian', 'tmt_aktif'], 'safe'],
            [['status'], 'integer'],
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
        $query = Bagian::find();

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
            'status' => $this->status,
            'tmt_aktif' => $this->tmt_aktif,
        ]);

        $query->andFilterWhere(['like', 'id_bagian', $this->id_bagian])
            ->andFilterWhere(['like', 'id_direktorat', $this->id_direktorat])
            ->andFilterWhere(['like', 'nama_bagian', $this->nama_bagian]);

        return $dataProvider;
    }
}
