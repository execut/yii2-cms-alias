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
            [['alias_id', 'language'], 'unique', 'targetAttribute' => ['alias_id', 'language'], 'message' => Yii::t('app', 'The combination of Alias ID and Language has already been taken.')],
            [['language', 'url'], 'unique', 'targetAttribute' => ['language', 'url'], 'message' => Yii::t('app', 'The combination of Language and Url has already been taken.'), 'filter' => function($query) {
                return $query->andWhere(['!=', 'alias_id', $this->alias_id]);    
            }],
            ['url', function($attribute, $params) {

                if (in_array($this->url, Yii::$app->getModule('alias')->reservedUrls))
                    $this->addError($attribute, Yii::t('app', 'This is a reserved url and can not be used'));   
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alias_id' => Yii::t('app', 'Alias ID'),
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