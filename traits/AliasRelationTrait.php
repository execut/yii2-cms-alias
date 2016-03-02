<?php

namespace infoweb\alias\traits;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use infoweb\alias\models\Alias;

trait AliasRelationTrait
{
    /**
     * Finds an instance of the owner based on the provided alias and language
     *
     * @param string $alias The alias
     * @param mixed $language The language
     * @return ActiveRecord An instance of the owner
     */
    public static function findByAlias($alias = '', $language = null)
    {
        // Try to load an Alias with provided params
        $aliasModel = Alias::findOne([
            'url' => $alias,
            'language' => ($language) ?: Yii::$app->language,
            'entity' => parent::className(),
        ]);

        return ($aliasModel) ? parent::findOne($aliasModel->entity_id) : null;
    }

    public function events()
    {
        $events = (method_exists(parent, 'events')) ? parent::events() : [];
        return ArrayHelper::merge($events, [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete'
        ]);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        // Delete all attached aliases
        $this->deleteAliases();
    }

    /**
     * Returns the attached Alias model
     *
     * @return Alias
     */
    public function getAlias($language = null)
    {
        return $this->getTranslation($language)->alias;
    }

    /**
     * Deletes all aliases for the model
     *
     * @return boolean
     */
    protected function deleteAliases()
    {
        return Alias::deleteAll(['entity' => parent::className(), 'entity_id' => $this->primaryKey]);
    }
}