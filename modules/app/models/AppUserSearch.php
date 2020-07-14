<?php

namespace app\modules\app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\app\models\AppUser;

/**
 * AppUserSearch represents the model behind the search form of `app\modules\app\models\AppUser`.
 */
class AppUserSearch extends AppUser
{
    public $pegawai_nama;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'active', 'pegawai_id'], 'integer'],
            [['username', 'password', 'authkey','pegawai_nama'], 'safe'],
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
        $query = AppUser::find()->leftJoin('data_pegawai','app_user.pegawai_id = data_pegawai.id_pegawai');

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
            'id' => $this->id,
            'active' => $this->active,
            'pegawai_id' => $this->pegawai_id,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username]);
        $query->andFilterWhere(['like', 'data_pegawai.nama', $this->pegawai_nama]);

        return $dataProvider;
    }
}
