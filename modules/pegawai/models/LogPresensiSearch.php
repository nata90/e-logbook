<?php

namespace app\modules\pegawai\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pegawai\models\LogPresensi;

/**
 * LogPresensiSearch represents the model behind the search form of `app\modules\pegawai\models\LogPresensi`.
 */
class LogPresensiSearch extends LogPresensi
{
    public $range_date;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sn', 'scan_date', 'pin', 'att_id', 'device_ip'], 'safe'],
            [['verifymode', 'inoutmode'], 'integer'],
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
        $query = LogPresensi::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'verifymode' => $this->verifymode,
            'inoutmode' => $this->inoutmode,
            'pin' => $this->pin,
        ]);

        $query->andFilterWhere(['like', 'sn', $this->sn])
            ->andFilterWhere(['like', 'att_id', $this->att_id])
            ->andFilterWhere(['like', 'device_ip', $this->device_ip]);

        if($this->range_date != null){
            $explode = explode('-',$this->range_date);
            $date_start = date('Y-m-d', strtotime(trim($explode[0])));
            $date_end = date('Y-m-d', strtotime(trim($explode[1])));
            $query->andFilterWhere(['between', 'scan_date', $date_start.' 00:00:00', $date_end.' 23:59:59']);
        }

        $query->orderBy('scan_date DESC');
        return $dataProvider;
    }
}
