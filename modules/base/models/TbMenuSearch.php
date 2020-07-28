<?php

namespace app\modules\base\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\base\models\TbMenu;

/**
 * TbMenuSearch represents the model behind the search form of `app\modules\base\models\TbMenu`.
 */
class TbMenuSearch extends TbMenu
{
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'active', 'type', 'order'], 'integer'],
            [['menu_name', 'module', 'url', 'icon'], 'safe'],
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
        $query = TbMenu::find();

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
            'parent_id' => $this->parent_id,
            'active' => $this->active,
            'type' => $this->type,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'menu_name', $this->menu_name])
            ->andFilterWhere(['like', 'module', $this->module])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'icon', $this->icon]);

        return $dataProvider;
    }

    public function searchMenu($params)
    {
        $query = TbMenu::find();

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
            'parent_id' => $this->parent_id,
            'active' => 1,
            'type'=>1,
        ]);

        $query->andFilterWhere(['like', 'menu_name', $this->menu_name])
            ->andFilterWhere(['like', 'module', $this->module])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'icon', $this->icon]);

        return $dataProvider;
    }
}
