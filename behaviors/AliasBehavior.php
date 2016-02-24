<?php

namespace infoweb\alias\behaviors;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;
use infoweb\alias\models\Alias;

class AliasBehavior extends Behavior
{
    /**
     * @var string The fully qualified name of the class that the Alias applies to
     */
    public $entityType = '';

    /**
     * @var string The field of the owner that has to be used for Alias::$entity_id
     */
    public $entityIdField = 'id';

    /**
     * @var string The field of the owner that has to be used for Alias::$language
     */
    public $languageField = 'language';

    /**
     * @var string The key in the $_POST array that holds the value for Alias::$type
     */
    public $typePostKey = 'type';

    /**
     * @var Alias The Alias of the owner model
     */
    protected $aliasModel = null;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_INSERT    => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE    => 'afterUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE   => 'beforeDelete'
        ];
    }

    public function beforeValidate($event)
    {
        if (Yii::$app->request->post(StringHelper::basename(Alias::className()), [])) {
            // Update the alias before validation so that it is allways up to date
            $this->updateAlias();
        }
    }

    public function afterInsert($event)
    {
        if (Yii::$app->request->post(StringHelper::basename(Alias::className()), [])) {
            // Update the Alias model with an entity_id and save it
            $this->alias->entity_id = $this->owner->{$this->entityIdField};

            if (!$this->alias->save()) {
                throw new \Exception('Saving of the Alias failed');
            }
        }

        return true;
    }

    public function afterUpdate($event)
    {
        if (Yii::$app->request->post(StringHelper::basename(Alias::className()), [])) {
            if (!$this->alias->save()) {
                throw new \Exception('Saving of the Alias failed');
            }
        }

        return true;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            return $this->owner->alias->delete();
        } else {
            return true;
        }
    }

    /**
     * Returns the Alias model.
     * If it does not exist the first time this method is called, it is loaded
     * or created and cached.
     *
     * @return Alias
     */
    public function getAlias()
    {
        // Load Alias
        if (!$this->aliasModel) {
            $conditions = [
                'entity'    => $this->entityType,
                'entity_id' => $this->owner->{$this->entityIdField},
                'language'  => $this->owner->{$this->languageField}
            ];
            $this->aliasModel = Alias::findOne($conditions);

            // Create Alias
            if (!$this->aliasModel) {
                $this->aliasModel = new Alias($conditions);
            }
        }

        return $this->aliasModel;
    }

    /**
     * Updates the Alias model attributes, based on data of the $_POST array
     */
    protected function updateAlias()
    {
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $type = Alias::TYPE_USER_DEFINED;
            // Values from the $_POST array can be extracted by using the last
            // part of $this->entityType and $this->typePostKey as keys.
            $entityNameParts = explode('\\', $this->entityType);
            $entityName = array_pop($entityNameParts);

            if ($entityName && isset($post[$entityName])) {
                if (isset($post[$entityName][$this->typePostKey])) {
                    $type = $post[$entityName][$this->typePostKey];
                }
            }

            $this->alias->type = $type;
            $this->alias->url = $post[StringHelper::basename(Alias::className())][$this->alias->language]['url'];
        }
    }
}