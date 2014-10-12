<?php

namespace infoweb\alias;

class Module extends \yii\base\Module
{
    /**
     * @var array  Url's can not be used as an alias
     */
    public $reservedUrls = [];
    
    public $controllerNamespace = 'infoweb\alias\controllers';
    
    public function init()
    {
        parent::init();
    }
}