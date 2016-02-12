<?php

namespace infoweb\alias\behaviors;

use infoweb\alias\models\AliasLang;
use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use infoweb\alias\models\Alias;
use yii\web\Response;
use yii\base\Model;

class AliasBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE   => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_INSERT   => 'afterInsert',
            ActiveRecord::EVENT_BEFORE_DELETE  => 'beforeDelete',
        ];
    }

    public function afterInsert($event)
    {
        $languages = Yii::$app->params['languages'];

        // Wrap the everything in a database transaction
        $transaction = Yii::$app->db->beginTransaction();

        // Create the alias
        $alias = new Alias([
            'entity_id' => $this->owner->id,
            'type'      => $this->owner->type,
        ]);

        if (!$alias->save()) {
            return false;
        }

        $post = Yii::$app->request->post();

        foreach ($languages as $languageId => $languageName) {

            // Save the alias tag translations
            $data = $post['AliasLang'][$languageId];

            $alias = $this->owner->alias;
            $alias->language = $languageId;
            $alias->url = $data['url'];
            $alias->entity = $this->owner->className();
            $alias->entity_id = $this->owner->id;

            if (!$alias->saveTranslation()) {
                return false;
            }
        }

        $transaction->commit();

        return true;
    }

    public function afterUpdate($event)
    {
        $languages = Yii::$app->params['languages'];

        // Wrap the everything in a database transaction
        $transaction = Yii::$app->db->beginTransaction();

        $post = Yii::$app->request->post();

        // Save the translations
        foreach ($languages as $languageId => $languageName) {

            // Save the alias tag translations
            $data = $post['Alias'][$languageId];

            $alias = $this->owner->alias;
            $alias->type = $this->owner->type;
            //$alias->language = $languageId;
            $alias->url = $data['url'];
            //echo '<pre>'; print_r($alias->attributes); echo '</pre>'; exit();
            //$alias->entity = $this->owner->className();
            //$alias->entity_id = $this->owner->id;

            if (!$alias->save()) {
                echo '<pre>'; print_r($alias->getErrors()); echo '</pre>'; exit();
                return false;
            }
        }

        $transaction->commit();

        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlias()
    {
        return $this->owner->hasOne(Alias::className(), ['entity_id' => 'id'])->where(['entity' => $this->owner->className(), 'language' => $this->owner->language]);
        /*
        return Alias::findOne([
            'entity'  => $this->owner->className(),
            'entity_id' => $this->owner->getPrimaryKey(),
            'language' => $this->owner->language,
        ]);
        */

    }

    public function beforeDelete()
    {
        // Try to load and delete the attached 'Alias' entity
        return $this->alias->delete();
    }


}