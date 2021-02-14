<?php

namespace ZnYii\Web\Widgets\Upload;

use yii\base\Widget;

class UploadImageWidget extends Widget
{

    public $model;
    public $attribute;
    public $isMultiple = false;
    public $options = [
        'class' => 'custom-file-input',
    ];
    private static $id = 1;

    public function run(): string
    {
        return $this->render('index', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'options' => $this->getOptions(),
        ]);
    }

    private function getOptions(): array {
        $options = $this->options;
        if($this->isMultiple) {
            $options['multiple'] = 'multiple';
        }
        if($options['class']) {
            $options['class'] .= ' file-uploader';
        }
        return $options;
    }
}
