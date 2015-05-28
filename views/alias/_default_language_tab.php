<div class="tab-content language-tab">
    <?= $form->field($model, "[{$model->language}]url")->textInput([
        'maxlength' => 255,
        'name' => "AliasLang[{$model->language}][url]"
    ]); ?>
</div>