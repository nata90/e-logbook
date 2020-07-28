<?php

namespace app\modules\logbook\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\logbook\models\Kinerja;

/**
 * KinerjaSearch represents the model behind the search form of `app\modules\logbook\models\Kinerja`.
 */
class KinerjaSearch extends Kinerja
{
    public $date_start;
    public $date_end;
    public $range_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kinerja', 'id_pegawai', 'jumlah', 'approval', 'user_approval'], 'integer'],
            [['tanggal_kinerja', 'id_tugas', 'deskripsi', 'tgl_approval', 'create_date','date_start','date_end','range_date'], 'safe'],
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
        $query = Kinerja::find();

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
            'id_kinerja' => $this->id_kinerja,
            'tanggal_kinerja' => $this->tanggal_kinerja,
            'id_pegawai' => $this->id_pegawai,
            'jumlah' => $this->jumlah,
            'approval' => $this->approval,
            'user_approval' => $this->user_approval,
            'tgl_approval' => $this->tgl_approval,
            'create_date' => $this->create_date,
        ]);


        $query->andFilterWhere(['like', 'id_tugas', $this->id_tugas])->andFilterWhere(['like', 'deskripsi', $this->deskripsi]);
        if($this->range_date != null){
            $explode = explode('-',$this->range_date);
            $date_start = date('Y-m-d', strtotime(trim($explode[0])));
            $date_end = date('Y-m-d', strtotime(trim($explode[1])));
            $query->andFilterWhere(['between', 'tanggal_kinerja', $date_start, $date_end]);
        }

        $query->orderBy('tanggal_kinerja ASC');

        return $dataProvider;
    }
}
