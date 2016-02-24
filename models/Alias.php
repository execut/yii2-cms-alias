<?php

namespace infoweb\alias\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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

    public static function primaryKey()
    {
        return [
            'entity',
            'entity_id',
            'language'
        ];
    }

    public function behaviors()
    {
        return [
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
            [['entity', 'language', 'url'], 'required'],
            ['entity_id', 'required', 'when' => function($model) {
                return !$this->isNewRecord;
            }],
            [['created_at', 'updated_at'], 'integer'],
            // Types
            [['type'], 'string'],
            ['type', 'in', 'range' => [self::TYPE_SYSTEM, self::TYPE_USER_DEFINED]],
            // Default type to 'user-defined'
            ['type', 'default', 'value' => self::TYPE_USER_DEFINED],
            // Trim
            [['url'], 'trim'],
            [['entity_id'], 'integer'],
            [['language'], 'string', 'max' => 10],
            [['url', 'entity'], 'string', 'max' => 255],
            [['entity', 'language', 'url'], 'unique', 'targetAttribute' => ['entity', 'language', 'url'], 'message' => Yii::t('infoweb/alias', 'This url is already used')],
            ['url', function($attribute, $params) {
                // Check if the url is not a reserved url when:
                //  - Inserting a new record
                //  - Updating an existing record that is not part of a system alias
                //  - The record is an instance of \infoweb\pages\models\Page
                if (in_array($this->url, Yii::$app->getModule('alias')->reservedUrls) &&
                    ($this->isNewRecord || (!$this->isNewRecord && $this->type != Alias::TYPE_SYSTEM)) &&
                    $this->entity == \infoweb\pages\models\Page::className())
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
            'type' => Yii::t('app', 'Type'),
            'alias_id' => Yii::t('infoweb/alias', 'Alias ID'),
            'language' => Yii::t('app', 'Language'),
            'url' => Yii::t('app', 'Url'),
            'entity' => Yii::t('app', 'Entity'),
            'entity_id' => Yii::t('app', 'Entity ID'),
        ];
    }

    public function getEntityModel()
    {
        return $this->hasOne($this->entity, ['id' => 'entity_id']);
    }

    public function getEntityTypeName()
    {
        return $this->entity;
    }
}