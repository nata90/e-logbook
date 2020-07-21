<?php

namespace app\modules\pegawai\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pegawai\models\Jabatan;

/**
 * JabatanSearch represents the model behind the search form of `app\modules\pegawai\models\Jabatan`.
 */
class JabatanSearch extends Jabatan
{
    public $id_klp_jabatan;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jabatan', 'id_grade', 'level_jabatan', 'peer_grup', 'status_jabatan'], 'integer'],
            [['nama_jabatan', 'tmt_jabatan','id_klp_jabatan'], 'safe'],
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
        $query = Jabatan::find()->leftJoin('grade_jabatan','jabatan.id_grade = grade_jabatan.id_grade');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
            'pagination' => [
                'pageSize' => 100,
            ]
        
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_jabatan' => $this->id_jabatan,
            'id_grade' => $this->id_grade,
            'level_jabatan' => $this->level_jabatan,
            'peer_grup' => $this->peer_grup,
            'status_jabatan' => $this->status_jabatan,
            'tmt_jabatan' => $this->tmt_jabatan,
            'grade_jabatan.id_klp_jabatan' => $this->id_klp_jabatan,
        ]);

        $query->andFilterWhere(['like', 'nama_jabatan', $this->nama_jabatan]);

        return $dataProvider;
    }
}
