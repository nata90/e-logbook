<?php

namespace app\modules\pegawai\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pegawai\models\GradeJabatan;

/**
 * GradeJabatanSearch represents the model behind the search form of `app\modules\pegawai\models\GradeJabatan`.
 */
class GradeJabatanSearch extends GradeJabatan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_grade', 'id_klp_jabatan', 'grade', 'nilai_jbt_max', 'nilai_jbt_min', 'nilai_jbt', 'status_grade'], 'integer'],
            [['kode_grade', 'deskripsi'], 'safe'],
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
        $query = GradeJabatan::find();

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
            'id_grade' => $this->id_grade,
            'id_klp_jabatan' => $this->id_klp_jabatan,
            'grade' => $this->grade,
            'nilai_jbt_max' => $this->nilai_jbt_max,
            'nilai_jbt_min' => $this->nilai_jbt_min,
            'nilai_jbt' => $this->nilai_jbt,
            'status_grade' => $this->status_grade,
        ]);

        $query->andFilterWhere(['like', 'kode_grade', $this->kode_grade])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi]);

        return $dataProvider;
    }
}
