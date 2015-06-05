<?php
namespace infoweb\alias;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /** @inheritdoc */
    public function bootstrap($app)
    {
        if ($app->hasModule('alias') && ($module = $app->getModule('alias')) instanceof Module) {            

        }
    }
}