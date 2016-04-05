<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Risk;

/**
 * RiskSearch represents the model behind the search form about `app\models\Risk`.
 */
class RiskSearch extends Risk
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['risk_id', 'pro_risk_id', 'pro_risk_detail_id', 'pro_risk_sub_detail_id', 'clinic_id', 'severity_level', 'born_id', 'source_id', 'edit_dep_id', 'edit_user_id', 'review_id', 'follow_id'], 'integer'],
            [['date_stamp', 'date_risk', 'detail_prob', 'date_edit', 'method', 'review_date', 'review_detail'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Risk::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            
            'risk_id' => $this->risk_id,
            'date_stamp' => $this->date_stamp,
            'pro_risk_id' => $this->pro_risk_id,
            'pro_risk_detail_id' => $this->pro_risk_detail_id,
            'pro_risk_sub_detail_id' => $this->pro_risk_sub_detail_id,
            'clinic_id' => $this->clinic_id,
            'severity_level' => $this->severity_level,
            'date_risk' => $this->date_risk,
            'born_id' => $this->born_id,
            'source_id' => $this->source_id,
            'edit_dep_id' => $this->edit_dep_id,
            'edit_user_id' => $this->edit_user_id,
            'date_edit' => $this->date_edit,
            'review_id' => $this->review_id,
            'review_date' => $this->review_date,
            'follow_id' => $this->follow_id,
        ]);

        $query->andFilterWhere(['like', 'detail_prob', $this->detail_prob])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'review_detail', $this->review_detail]);

        return $dataProvider;
    }
}
