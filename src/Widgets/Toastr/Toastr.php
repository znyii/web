<?php

namespace ZnYii\Web\Widgets\Toastr;

use yii\base\Widget;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnCore\Container\Helpers\ContainerHelper;
use ZnCore\Collection\Interfaces\Enumerable;
use ZnDomain\Entity\Factories\PropertyAccess;

class Toastr extends Widget
{

    /**
     * information alert
     */
    const TYPE_INFO = 'info';
    /**
     * danger/error alert
     */
    const TYPE_DANGER = 'danger';
    /**
     * success alert
     */
    const TYPE_SUCCESS = 'success';
    /**
     * warning alert
     */
    const TYPE_WARNING = 'warning';
    /**
     * primary alert
     */
    const TYPE_PRIMARY = 'primary';
    /**
     * default alert
     */
    const TYPE_DEFAULT = 'well';
    /**
     * custom alert
     */
    const TYPE_CUSTOM = 'custom';

    private $toastrService;

    public function __construct(ToastrServiceInterface $toastrService, $config = [])
    {
        parent::__construct($config);
        $this->toastrService = $toastrService;
    }

    /**
     * Runs the widget
     */
    public function run()
    {
        $collection = $this->toastrService->findAll();
        $this->generateHtml($collection);
    }

    public static function create($content, $type = self::TYPE_SUCCESS, $delay = 5000)
    {
        self::getToastrService()->add($type, $content, $delay);
    }

    private function generateHtml(Enumerable $collection)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($collection as $entity) {
            $type = $propertyAccessor->getValue($entity, 'type');
            $type = str_replace('alert-', '', $type);
            $content = $propertyAccessor->getValue($entity, 'content');
//		    dd("toastr.{$type}('{$content}'); \n");
            $this->view->registerJs("toastr.{$type}('{$content}'); \n");
        }
    }

    private static function getToastrService(): ToastrServiceInterface
    {
        $container = ContainerHelper::getContainer();
        return $container->get(ToastrServiceInterface::class);
    }
}
