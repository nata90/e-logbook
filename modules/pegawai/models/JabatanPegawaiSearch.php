<?php

namespace app\modules\pegawai\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pegawai\models\JabatanPegawai;

/**
 * JabatanPegawaiSearch represents the model behind the search form of `app\modules\pegawai\models\JabatanPegawai`.
 */
class JabatanPegawaiSearch extends JabatanPegawai
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jbt_pegawai', 'id_jabatan', 'id_pegawai', 'id_penilai', 'status_jbt'], 'integer'],
            [['tmt_jbt'], 'safe'],
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
        $query = JabatanPegawai::find();

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
            'id_jbt_pegawai' => $this->id_jbt_pegawai,
            'id_jabatan' => $this->id_jabatan,
            'id_pegawai' => $this->id_pegawai,
            'id_penilai' => $this->id_penilai,
            'status_jbt' => $this->status_jbt,
            'tmt_jbt' => $this->tmt_jbt,
        ]);

        return $dataProvider;
    }
}
