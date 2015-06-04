<?php

namespace infoweb\alias\models;

use Yii;

/**
 * This is the model class for table "alias_lang".
 *
 * @property string $alias_id
 * @property string $language
 * @property string $url
 */
class AliasLang extends \yii\db\ActiveRecord
{
    /**
     * The entity type of the coupled Alias
     */
    public $entityType = null;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alias_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language', 'url'], 'required'],
            // Only required for existing records
            [['alias_id'], 'required', 'when' => function($model) {
                return !$model->isNewRecord;
            }],
            // Trim
            [['url'], 'trim'],
            [['alias_id'], 'integer'],
            [['language'], 'string', 'max' => 2],
            [['url'], 'string', 'max' => 255],
            [['alias_id', 'language'], 'unique', 'targetAttribute' => ['alias_id', 'language'], 'message' => Yii::t('infoweb/alias', 'The combination of Alias ID and Language has already been taken.')],
            [['language', 'url'], 'unique', 'targetAttribute' => ['language', 'url'], 'message' => Yii::t('infoweb/alias', 'The combination of Language and Url has already been taken.'), 'filter' => function($query) {
                // If an entityType is provided, use it to match with the alias table
                // because an url has to be unique within a type of entity    
                if ($this->entityType) {
                    $query->innerJoin('alias', ['alias.entity' => $this->entityType, 'alias.id' => 'alias_lang.alias_id']);
                }
                
                if (!$this->isNewRecord) {
                    $query->andWhere(['!=', 'alias_id', $this->alias_id]);    
                }

                return $query;
            }],
            ['url', function($attribute, $params) {
                // Check if the url is not a reserved url
                if (in_array($this->url, Yii::$app->getModule('alias')->reservedUrls))
                    $this->addError($attribute, Yii::t('infoweb/alias', 'This is a reserved url and can not be used'));
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alias_id' => Yii::t('infoweb/alias', 'Alias ID'),
            'language' => Yii::t('app', 'Language'),
            'url' => Yii::t('app', 'Url')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlias()
    {
        return $this->hasOne(Alias::className(), ['id' => 'alias_id']);
    }
}