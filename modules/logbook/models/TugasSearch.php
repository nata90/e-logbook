<?php

namespace app\modules\logbook\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\logbook\models\Tugas;

/**
 * TugasSearch represents the model behind the search form of `app\modules\logbook\models\Tugas`.
 */
class TugasSearch extends Tugas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tugas', 'nama_tugas'], 'safe'],
            [['id_kategori', 'akses', 'status_tugas'], 'integer'],
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
        $query = Tugas::find();

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
            'id_kategori' => $this->id_kategori,
            'akses' => $this->akses,
            'status_tugas' => $this->status_tugas,
        ]);

        $query->andFilterWhere(['like', 'id_tugas', $this->id_tugas])
            ->andFilterWhere(['like', 'nama_tugas', $this->nama_tugas]);

        return $dataProvider;
    }
}
