<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Plans;

/**
 * PlansSearch represents the model behind the search form of `common\models\Plans`.
 */
class PlansSearch extends Plans
{
    public $company;
    public $coverage_amount;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_id', 'policy_id', 'min_age', 'max_age', 'coverage_id', 'company_id', 'pre_medical'], 'integer'],
            [['rate_per', 'deductible'], 'number'],
            [['plan_sub_category','company','coverage_amount'], 'safe'],
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
        $query = Plans::find()->alias('a');
        $query->joinWith(['company b']);
        $query->joinWith(['coverage c']);

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

        $dataProvider->sort->attributes['company'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['b.name' => SORT_ASC],
            'desc' => ['b.name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['coverage_amount'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['c.coverage_amount' => SORT_ASC],
            'desc' => ['c.coverage_amount' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'a.plan_id' => $this->plan_id,
            'a.policy_id' => $this->policy_id,
            'a.min_age' => $this->min_age,
            'a.max_age' => $this->max_age,
            'a.coverage_id' => $this->coverage_id,
            'a.company_id' => $this->company_id,
            'a.rate_per' => $this->rate_per,
            'a.deductible' => $this->deductible,
            'a.pre_medical' => $this->pre_medical,
            'c.coverage_amount' => $this->coverage_amount,
        ]);

        $query->andFilterWhere(['like', 'a.plan_sub_category', $this->plan_sub_category]);
        $query->andFilterWhere(['like', 'b.name', $this->company]);

        return $dataProvider;
    }
}
