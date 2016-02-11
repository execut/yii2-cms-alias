<?php
use infoweb\pages\components\Page;

$alias = ($model->isNewRecord) ? (new \infoweb\alias\models\Alias) : $model->page->alias;

?>
<?= $form->field($alias, "[{$alias->language}]url")->textInput([
    'maxlength' => 255,
    'name' => "AliasLang[{$alias->language}][url]",
    //'placeholder' => '/'.$model->language.'/',
    'data-slugified' => 'true',
    //'readonly' => ($page->type == Page::TYPE_SYSTEM && !Yii::$app->user->can('Superadmin')) ? true : false,
    //'data-duplicateable' => Yii::$app->getModule('pages')->allowContentDuplication ? 'true' : 'false'
]); ?>
