<?php

namespace infoweb\alias\behaviors;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use infoweb\alias\models\Alias;

class AliasRelationBehavior extends Behavior
{
    public function events()
    {
        $events = (method_exists($this->owner, 'events')) ? $this->owner->events() : [];
        return ArrayHelper::merge($events, [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete'
        ]);
    }

    public function afterDelete($event)
    {
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
        return $this->owner->getTranslation($language)->alias;
    }

    /**
     * Deletes all aliases for the model
     *
     * @return boolean
     */
    protected function deleteAliases()
    {
        return Alias::deleteAll(['entity' => get_class($this->owner), 'entity_id' => $this->owner->id]);
    }
}