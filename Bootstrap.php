<?php
namespace infoweb\alias;

use Yii;
use yii\base\BootstrapInterface;
use infoweb\alias\models\AliasLang;

class Bootstrap implements BootstrapInterface
{
    /** @inheritdoc */
    public function bootstrap($app)
    {
        if ($app->hasModule('alias') && ($module = $app->getModule('alias')) instanceof Module) {            

            $this->setEventHandlers();
        }
    }
    
    protected function setEventHandlers()
    {
        // Set eventhandlers for the 'AliasLang' model
        Event::on(AliasLang::className(), ActiveRecord::EVENT_AFTER_FIND, function ($event) {
            
            // Set the entityType based on the alias if that already exists
            if ($event->sender->alias) {
                $event->sender->entityType = $alias->entity;
            }
        });    
    }
}