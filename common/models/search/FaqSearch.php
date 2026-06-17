<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Faq;

/**
 * FaqSearch represents the model behind the search form of `common\models\Faq`.
 */
class FaqSearch extends Faq
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'course_id', 'page_id', 'created_at', 'updated_at', 'user_id'], 'integer'],
            [['question_ru', 'question_en', 'question_uz', 'answer_ru', 'answer_en', 'answer_uz'], 'safe'],
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
        $query = Faq::find();

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
            'course_id' => $this->course_id,
            'page_id' => $this->page_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'question_ru', $this->question_ru])
            ->andFilterWhere(['like', 'question_en', $this->question_en])
            ->andFilterWhere(['like', 'question_uz', $this->question_uz])
            ->andFilterWhere(['like', 'answer_ru', $this->answer_ru])
            ->andFilterWhere(['like', 'answer_en', $this->answer_en])
            ->andFilterWhere(['like', 'answer_uz', $this->answer_uz]);

        return $dataProvider;
    }
}
