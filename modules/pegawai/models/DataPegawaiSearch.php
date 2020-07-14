<?php

namespace app\modules\pegawai\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pegawai\models\DataPegawai;

/**
 * DataPegawaiSearch represents the model behind the search form of `app\modules\pegawai\models\DataPegawai`.
 */
class DataPegawaiSearch extends DataPegawai
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pegawai', 'pin', 'jenis_peg', 'status_peg', 'gender'], 'integer'],
            [['nip', 'nama', 'tmp_lahir', 'tgl_lahir', 'username', 'password'], 'safe'],
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
        $query = DataPegawai::find();

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
            'id_pegawai' => $this->id_pegawai,
            'pin' => $this->pin,
            //'tgl_lahir' => $this->tgl_lahir,
            //'jenis_peg' => $this->jenis_peg,
            //'status_peg' => $this->status_peg,
            //'gender' => $this->gender,
        ]);

        $query->andFilterWhere(['like', 'nip', $this->nip])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'tmp_lahir', $this->tmp_lahir]);
            //->andFilterWhere(['like', 'username', $this->username])
            //->andFilterWhere(['like', 'password', $this->password]);

        $query->orderBy('id_pegawai DESC');

        return $dataProvider;
    }
}
