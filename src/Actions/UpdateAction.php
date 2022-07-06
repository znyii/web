<?php

namespace ZnYii\Web\Actions;

use ZnCore\Container\Libs\Container;
use Yii;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnCore\Arr\Helpers\ArrayHelper;
use ZnCore\Validation\Exceptions\UnprocessibleEntityException;
use ZnCore\Entity\Helpers\EntityHelper;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;
use ZnCore\Query\Entities\Query;
use ZnYii\Base\Enums\ScenarionEnum;
use ZnYii\Base\Forms\BaseForm;
use ZnYii\Base\Helpers\FormHelper;
use ZnYii\Base\Helpers\UnprocessibleErrorHelper;

class UpdateAction extends BaseFormAction
{

    private $toastrService;

    public function __construct(
        $id, $controller,
        ToastrServiceInterface $toastrService,
        $config = []
    )
    {
        parent::__construct($id, $controller, $config);
        $this->toastrService = $toastrService;
    }

    public function run(int $id)
    {
        $entity = $this->readOne($id);
//        $this->runCallback();
        /** @var BaseForm $model */
        //$model = Container::getInstance()->get($this->formClass);
        $model = FormHelper::createFormByClass($this->formClass, ScenarionEnum::UPDATE);
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post($model->formName());
            FormHelper::setAttributes($model, $postData);
            try {
                if ($this->entityClass) {
                    $data = FormHelper::extractAttributesForEntity($model, $this->entityClass);
                } else {
                    $data = $postData;
                }
                $this->service->updateById($id, $data);
                $this->toastrService->success($this->getSuccessMessage());
                return $this->redirect($this->successRedirectUrl);
            } catch (UnprocessibleEntityException $e) {
                $errors = FormHelper::setErrorsToModel($model, $e->getErrorCollection());
                $errorMessage = implode('<br/>', $errors);
                $this->toastrService->warning($errorMessage);
            } catch (\DomainException $e) {
                $this->toastrService->warning($e->getMessage());
            }
        } else {
            $data = EntityHelper::toArrayForTablize($entity);
            $data = ArrayHelper::merge($data, EntityHelper::toArray($entity));
            FormHelper::setAttributes($model, $data);
        }
        return $this->render('update', [
            'request' => Yii::$app->request,
            'model' => $model,
        ]);
    }

    private function readOne(int $id): EntityIdInterface
    {
        $query = new Query();
        $query->with($this->with);
        /** @var EntityIdInterface $entity */
        $entity = $this->service->findOneById($id, $query);
        $this->runCallback([$entity]);
        return $entity;
    }
}
