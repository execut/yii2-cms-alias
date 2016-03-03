<?php // Kartik ActiveForm ?>
<?php if ($form::className() == 'kartik\widgets\ActiveForm'): ?>
<?= $form->field($alias, "[{$alias->language}]url", [
        'addon' => ['prepend' => ['content' => $urlPrefix]]
    ])->textInput([
        'maxlength' => 255,
        'name' => "Alias[{$alias->language}][url]",
        'data-slugified' => 'true',
        'readonly' => $readonly,
        'data-duplicateable' => $duplicateable,
    ]); ?>
<?php // Bootstrap ActiveForm ?>
<?php elseif ($form::className() == \yii\bootstrap\ActiveForm::className()) : ?>
<?= $form->field($alias, "[{$alias->language}]url", [
        'inputTemplate' => (empty($urlPrefix)) ? "{input}" : "<div class=\"input-group\"><span class=\"input-group-addon\">{$urlPrefix}</span>{input}</div>"
    ])->textInput([
        'maxlength' => 255,
        'name' => "Alias[{$alias->language}][url]",
        'data-slugified' => 'true',
        'readonly' => $readonly,
        'data-duplicateable' => $duplicateable,
    ]); ?>
<?php endif; ?>