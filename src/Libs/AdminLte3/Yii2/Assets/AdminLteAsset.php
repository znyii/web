<?php

namespace ZnYii\Web\Libs\AdminLte3\Yii2\Assets;

use yii\web\JqueryAsset;
use ZnYii\Web\Assets\BaseAsset;

class AdminLteAsset extends BaseAsset
{

    public $sourcePath = __DIR__ . '/dist';
    public $css = [
        'css/adminlte.min.css',
    ];
    public $js = [
        'js/bootstrap.bundle.min.js',
        'js/adminlte.min.js',
    ];
    public $depends = [
        JqueryAsset::class,
    ];
}
