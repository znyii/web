<?php

namespace ZnYii\Web\Actions;

use Illuminate\Container\Container;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnYii\Web\Widgets\Toastr\Toastr;
use Yii;
use ZnYii\Base\Forms\BaseForm;
use ZnYii\Base\Helpers\FormHelper;
use ZnYii\Base\Helpers\UnprocessibleErrorHelper;

class CreateAction extends BaseFormAction
{

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
                Toastr::create($this->getSuccessMessage(), Toastr::TYPE_SUCCESS);
                return $this->redirect($this->successRedirectUrl);
            } catch (UnprocessibleEntityException $e) {
                $errors = FormHelper::setErrorsToModel($model, $e->getErrorCollection());
                $errorMessage = implode('<br/>', $errors);
                Toastr::create($errorMessage, Toastr::TYPE_WARNING);
            } catch (\DomainException $e) {
                Toastr::create($e->getMessage(), Toastr::TYPE_WARNING);
            }
        }
        return $this->render('create', [
            'request' => Yii::$app->request,
            'model' => $model,
        ]);
    }
}
