<?php

namespace app\modules\pegawai\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pegawai\models\KlpJabatan;

/**
 * KlpJabatanSearch represents the model behind the search form of `app\modules\pegawai\models\KlpJabatan`.
 */
class KlpJabatanSearch extends KlpJabatan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_klp_jabatan', 'status_klp_jabatan'], 'integer'],
            [['kode_klp_jabatan', 'nama_klp_jabatan', 'deskripsi'], 'safe'],
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
        $query = KlpJabatan::find();

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
            'id_klp_jabatan' => $this->id_klp_jabatan,
            'status_klp_jabatan' => $this->status_klp_jabatan,
        ]);

        $query->andFilterWhere(['like', 'kode_klp_jabatan', $this->kode_klp_jabatan])
            ->andFilterWhere(['like', 'nama_klp_jabatan', $this->nama_klp_jabatan])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi]);

        return $dataProvider;
    }
}
