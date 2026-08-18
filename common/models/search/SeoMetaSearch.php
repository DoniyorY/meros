<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SeoMeta;

/**
 * SeoMetaSearch represents the model behind the search form of `common\models\SeoMeta`.
 */
class SeoMetaSearch extends SeoMeta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'entity_id', 'created_at', 'updated_at'], 'integer'],
            [['entity_type', 'title_ru', 'title_en', 'title_uz', 'description_ru', 'description_en', 'description_uz', 'h1_ru', 'h1_en', 'h1_uz', 'text_ru', 'text_en', 'text_uz', 'canonical', 'og_image', 'robots'], 'safe'],
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
        $query = SeoMeta::find();

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
            'entity_id' => $this->entity_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'entity_type', $this->entity_type])
            ->andFilterWhere(['like', 'title_ru', $this->title_ru])
            ->andFilterWhere(['like', 'title_en', $this->title_en])
            ->andFilterWhere(['like', 'title_uz', $this->title_uz])
            ->andFilterWhere(['like', 'description_ru', $this->description_ru])
            ->andFilterWhere(['like', 'description_en', $this->description_en])
            ->andFilterWhere(['like', 'description_uz', $this->description_uz])
            ->andFilterWhere(['like', 'h1_ru', $this->h1_ru])
            ->andFilterWhere(['like', 'h1_en', $this->h1_en])
            ->andFilterWhere(['like', 'h1_uz', $this->h1_uz])
            ->andFilterWhere(['like', 'text_ru', $this->text_ru])
            ->andFilterWhere(['like', 'text_en', $this->text_en])
            ->andFilterWhere(['like', 'text_uz', $this->text_uz])
            ->andFilterWhere(['like', 'canonical', $this->canonical])
            ->andFilterWhere(['like', 'og_image', $this->og_image])
            ->andFilterWhere(['like', 'robots', $this->robots]);

        return $dataProvider;
    }
}
