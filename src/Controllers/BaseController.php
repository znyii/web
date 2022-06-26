<?php

namespace ZnYii\Web\Controllers;

use yii\helpers\Url;
use yii\web\Controller;
use ZnLib\Components\I18Next\Facades\I18Next;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;
use ZnCore\Domain\Service\Interfaces\CrudServiceInterface;
use ZnLib\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;
use ZnYii\Web\Actions\CreateAction;
use ZnYii\Web\Actions\DeleteAction;
use ZnYii\Web\Actions\IndexAction;
use ZnYii\Web\Actions\RestoreAction;
use ZnYii\Web\Actions\UpdateAction;
use ZnYii\Web\Actions\ViewAction;

abstract class BaseController extends Controller
{

    /** @var CrudServiceInterface */
    protected $service;

    /** @var BreadcrumbWidget */
    protected $breadcrumbWidget;
    protected $baseUri;
    protected $formClass;
    protected $entityClass;
    protected $filterModel = null;
    public $baseViewAlias = '';
    protected $defaultPerPage = 10;

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'service' => $this->service,
                'filterModel' => $this->filterModel,
                'with' => $this->with(),
                'defaultPerPage' => $this->defaultPerPage,
                /*'sort' => [
                    'id' => SORT_DESC,
                ],*/
            ],
            'create' => [
                'class' => CreateAction::class,
                'service' => $this->service,
                'successMessage' => ['web', 'message.create_success'],
//                'i18NextConfig' => $this->i18NextConfig(),
                'successRedirectUrl' => [$this->baseUri],
                'formClass' => $this->formClass,
                'entityClass' => $this->entityClass,
                'callback' => function () {
                    $this->breadcrumbWidget->add(I18Next::t('core', 'action.create'), Url::to([$this->baseUri . '/create']));
                }
            ],
            'view' => [
                'class' => ViewAction::class,
                'service' => $this->service,
                'with' => $this->with(),
                'callback' => function (EntityIdInterface $entity) {
                    $this->breadcrumbWidget->add(I18Next::t('core', 'action.view'), Url::to([$this->baseUri . '/view', 'id' => $entity->getId()]));
                }
            ],
            'update' => [
                'class' => UpdateAction::class,
                'service' => $this->service,
                'with' => $this->with(),
                'successMessage' => ['web', 'message.update_success'],
//                'i18NextConfig' => $this->i18NextConfig(),
                'successRedirectUrl' => [$this->baseUri],
                'formClass' => $this->formClass,
                'entityClass' => $this->entityClass,
                'callback' => function (EntityIdInterface $entity) {
//                    $this->breadcrumbWidget->add($entity->getTitle(), Url::to([$this->baseUri . '/view', 'id' => $entity->getId()]));
                    $this->breadcrumbWidget->add(I18Next::t('core', 'action.update'), Url::to([$this->baseUri . '/update', 'id' => $entity->getId()]));
                }
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'service' => $this->service,
                'successMessage' => ['web', 'message.delete_success'],
                'successRedirectUrl' => [$this->baseUri],
//                'i18NextConfig' => $this->i18NextConfig(),
            ],
        ];
    }

    protected function getRestoreActionConfig(): array
    {
        return [
            'class' => RestoreAction::class,
            'service' => $this->service,
            'successMessage' => ['web', 'message.restore_success'],
            'successRedirectUrl' => [$this->baseUri],
        ];
    }

    /*public function i18NextConfig(): array
    {
        return [
            'bundle' => '',
            'file' => '',
        ];
    }*/

    /* protected function extractTitleFromEntity(object $entity): string {
         return $entity->getTitle();
     }*/

    public function with()
    {
        return [];
    }
}
