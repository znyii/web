<?php

/**
 * @var View $this
 * @var Model $model
 * @var string $attribute
 * @var array $options
 */

use yii\base\Model;
use yii\helpers\Html;
use yii\web\View;

$this->registerJs(file_get_contents(__DIR__ . '/script.js'), View::POS_END);
if($model->imageUrl) {
    $js = 'uploader.addFile("'. $model->imageUrl .'")';
    $this->registerJs($js, View::POS_END);
}

?>

<?= Html::activeLabel($model, $attribute); ?>

<style>
    .uploader-image {
        height: 100px;
    }
</style>

<div id="image-template" class="d-none">
    <div class="mb-2 mr-2 d-inline-block">
        <img src="" class="rounded img-thumbnail float-left uploader-image" alt="">
        <p class="uploader-file-name d-none"></p>
    </div>
</div>

<div id="image-container">

</div>

<div class="custom-file">
    <?= Html::activeFileInput($model, $attribute, $options); ?>
    <?= Html::error($model, $attribute, ['class' => 'text-danger']); ?>
    <label class="custom-file-label" for="customFile">Choose file</label>
</div>
