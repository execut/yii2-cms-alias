<?php if (Yii::$app->getSession()->hasFlash('alias')): ?>
<div class="alert alert-success">
    <p><?= Yii::$app->getSession()->getFlash('alias') ?></p>
</div>
<?php endif; ?>

<?php if (Yii::$app->getSession()->hasFlash('alias-error')): ?>
<div class="alert alert-danger">
    <p><?= Yii::$app->getSession()->getFlash('alias-error') ?></p>
</div>
<?php endif; ?>