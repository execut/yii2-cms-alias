<?php

namespace infoweb\alias\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use infoweb\alias\models\Alias;

/**
 * AliasSearch represents the model behind the search form about `app\models\Alias`.
 */
class AliasSearch extends Alias
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entityModel.name'], 'safe'],
        ];
    }
    
    public function attributes()
    {
        // Add related fields to searchable attributes
        return array_merge(parent::attributes(), ['entityModel.name']);
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
        $query = Alias::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);        
        
        // Join the entity model as a relation
        $query->joinWith(['entityModel' => function($query) {
             $query->join('INNER JOIN', ['entity' => 'pages_lang'], 'pages.id = entity.page_id AND entity.language = \'' . Yii::$app->language . '\'');
        }]);
        
        // enable sorting for the related column
        $dataProvider->sort->attributes['entityModel.name'] = [
            'asc' => ['entity.name' => SORT_ASC],
            'desc' => ['entity.name' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['LIKE', 'entity.name', $this->getAttribute('entityModel.name')]);

        return $dataProvider;
    }
}