<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserSubscriptions;

/**
 * UserSubscriptionsSearch represents the model behind the search form of `common\models\UserSubscriptions`.
 */
class UserSubscriptionsSearch extends UserSubscriptions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'plan_id', 'user_id', 'status', 'start_date', 'expires_date', 'created_at', 'updated_at', 'amount', 'currency_code'], 'integer'],
            [['subscription_key', 'payment_transaction_id', 'payment_provider'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = UserSubscriptions::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'plan_id' => $this->plan_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'expires_date' => $this->expires_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'amount' => $this->amount,
            'currency_code' => $this->currency_code,
        ]);

        $query->andFilterWhere(['like', 'subscription_key', $this->subscription_key])
            ->andFilterWhere(['like', 'payment_transaction_id', $this->payment_transaction_id])
            ->andFilterWhere(['like', 'payment_provider', $this->payment_provider]);

        return $dataProvider;
    }
}
