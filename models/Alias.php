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
    const ENTITY_PAGE = 'page';
    
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
                    'url'
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
            [['entity', 'entity_id'], 'required'],
            [['entity'], 'string'],
            [['entity_id', 'created_at', 'updated_at'], 'integer'],
            [['entity', 'entity_id'], 'unique', 'targetAttribute' => ['entity', 'entity_id'], 'message' => Yii::t('app', 'The combination of Entity and Entity ID has already been taken.')],
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
            'id' => Yii::t('app', 'ID'),
            'type'
            'entity' => Yii::t('app', 'Entity'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
        switch ($this->entity) {
            case self::TYPE_PAGE:
            default:
                return $this->hasOne(Page::className(), ['id' => 'entity_id']);
                break;
                
        }            
    }

    public function getEntityTypeName()
    {
        switch ($this->entity) {
            // Page
            case 'page':
                return Yii::t('app', 'Page');
                break;
        }    
    }
}