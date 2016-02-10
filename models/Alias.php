<?php

namespace infoweb\alias\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use dosamigos\translateable\TranslateableBehavior;
use infoweb\pages\models\Page;

/**
 * This is the model class for table "alias".
 *
 * @property string $id
 * @property string $entity
 * @property string $entity_id
 * @property string $created_at
 * @property string $updated_at
 */
class Alias extends \yii\db\ActiveRecord
{
    const TYPE_SYSTEM = 'system';
    const TYPE_USER_DEFINED = 'user-defined';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alias';
    }

    public function behaviors()
    {
        return [
            'trans' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => [
                    'url',
                    'entity',
                    'entity_id',
                ]
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function () {
                    return time();
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            // Types
            [['type'], 'string'],
            ['type', 'in', 'range' => [self::TYPE_SYSTEM, self::TYPE_USER_DEFINED]],
            // Default type to 'user-defined'
            ['type', 'default', 'value' => self::TYPE_USER_DEFINED]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(AliasLang::className(), ['alias_id' => 'id']);
    }
    
    public function getEntityModel()
    {
        return 'getEntityModel';
        //return $this->hasOne(Page::className(), ['id' => 'entity_id']);
    }

    public function getEntityTypeName()
    {
        return 'getEntityTypeName';
    }
}