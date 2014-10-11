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
            Alias::TYPE_SYSTEM => ['disabled' => (Yii::$app->user->can('Superadmin')) ? false : true]
        ]
    ]); ?>
    
    <?= $form->field($model, 'entity')->dropDownList(['page' => Yii::t('app', 'Page')]); ?>
    
    <?= $form->field($model, 'entity_id')->dropDownList(ArrayHelper::map($entities['pages'], 'id', 'name')); ?>
</div>