<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Billing;

/**
 * BillingSearch represents the model behind the search form of `common\models\Billing`.
 */
class BillingSearch extends Billing
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'subscription_id', 'start_date', 'expires_date', 'created_at', 'updated_at', 'payment_provider', 'payment_status', 'amount', 'status'], 'integer'],
            [['billing_token', 'payment_transaction_id'], 'safe'],
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
        $query = Billing::find();

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
            'user_id' => $this->user_id,
            'subscription_id' => $this->subscription_id,
            'start_date' => $this->start_date,
            'expires_date' => $this->expires_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'payment_provider' => $this->payment_provider,
            'payment_status' => $this->payment_status,
            'amount' => $this->amount,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'billing_token', $this->billing_token])
            ->andFilterWhere(['like', 'payment_transaction_id', $this->payment_transaction_id]);

        return $dataProvider;
    }
}
