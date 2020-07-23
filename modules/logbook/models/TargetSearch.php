<?php

namespace app\modules\logbook\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\logbook\models\Target;

/**
 * TargetSearch represents the model behind the search form of `app\modules\logbook\models\Target`.
 */
class TargetSearch extends Target
{
    public $nama_jabatan;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_target', 'id_jabatan', 'nilai_target', 'status_target'], 'integer'],
            [['id_unit_kerja','nama_jabatan'], 'safe'],
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
        $query = Target::find();

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
            'id_target' => $this->id_target,
            'id_jabatan' => $this->id_jabatan,
            'nilai_target' => $this->nilai_target,
            'status_target' => $this->status_target,
        ]);

        $query->andFilterWhere(['like', 'id_unit_kerja', $this->id_unit_kerja]);

        return $dataProvider;
    }
}
