<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use infoweb\alias\models\Alias;
?>
<div class="tab-content default-tab">
    <?= $form->field($model, 'type')->dropDownList([
        Alias::TYPE_SYSTEM        => Yii::t('app', 'System'),
        Alias::TYPE_USER_DEFINED  => Yii::t('app', 'User defined')
    ],[
        'options' => [
            Alias::TYPE_SYSTEM => ['disabled' => (Yii::$app->user->can('Superadmin')) ? false : true],
            Alias::TYPE_USER_DEFINED => ['disabled' => ($model->type == Alias::TYPE_SYSTEM && !Yii::$app->user->can('Superadmin')) ? true : false],
        ]
    ]); ?>
    
    <?= $form->field($model, 'entity')->dropDownList(['page' => Yii::t('infoweb/pages', 'Page')]); ?>
    
    <?= $form->field($model, 'entity_id')->dropDownList(
        ArrayHelper::map($entities['pages'], 'id', 'name'),
        [
            'prompt' => Yii::t('infoweb/alias', 'Choose a page')
        ]);
    ?>
</div>