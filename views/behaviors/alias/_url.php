<?php
use infoweb\pages\components\Page;
?>
<?= $form->field($alias, "[{$alias->language}]url")->textInput([
    'maxlength' => 255,
    //'name' => "Alias[{$alias->language}][url]",
    //'placeholder' => '/'.$model->language.'/',
    'data-slugified' => 'true',
    //'readonly' => ($page->type == Page::TYPE_SYSTEM && !Yii::$app->user->can('Superadmin')) ? true : false,
    //'data-duplicateable' => Yii::$app->getModule('pages')->allowContentDuplication ? 'true' : 'false'
]); ?>
