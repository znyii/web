<?php

namespace ZnYii\Web\Actions;

use Illuminate\Container\Container;
use Yii;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnYii\Base\Forms\BaseForm;
use ZnYii\Base\Helpers\FormHelper;
use ZnYii\Base\Helpers\UnprocessibleErrorHelper;

class CreateAction extends BaseFormAction
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

    public function run()
    {
        $this->runCallback();
        /** @var BaseForm $model */
        $model = Container::getInstance()->get($this->formClass);
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post($model->formName());
            FormHelper::setAttributes($model, $postData);
            try {
                $this->service->create(FormHelper::extractAttributesForEntity($model, $this->entityClass));
                $this->toastrService->success($this->getSuccessMessage());
                return $this->redirect($this->successRedirectUrl);
            } catch (UnprocessibleEntityException $e) {
                $errors = FormHelper::setErrorsToModel($model, $e->getErrorCollection());
                $errorMessage = implode('<br/>', $errors);
                $this->toastrService->warning($errorMessage);
            } catch (\DomainException $e) {
                $this->toastrService->warning($e->getMessage());
            }
        }
        return $this->render('create', [
            'request' => Yii::$app->request,
            'model' => $model,
        ]);
    }
}
