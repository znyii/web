<?php

namespace ZnYii\Web\Actions;

use Yii;
use yii\base\Model;

abstract class BaseFormAction extends BaseAction
{

    protected $with = [];
    protected $sort = [];
    protected $formClass;
    protected $entityClass;
    protected $successMessage;
    protected $successMessageKey;
    protected $successRedirectUrl;

    public function setWith(array $with)
    {
        $this->with = $with;
    }

    public function setSort(array $sort)
    {
        $this->sort = $sort;
    }

    public function setFormClass(string $formClass): void
    {
        $this->formClass = $formClass;
    }

    public function setEntityClass(string $entityClass): void
    {
        $this->entityClass = $entityClass;
    }

    public function setSuccessMessage(array $successMessage): void
    {
        $this->successMessage = $successMessage;
    }

    public function getSuccessMessage(): array
    {
        return $this->successMessage ?: $this->getI18NextParams($this->successMessageKey);
    }

    public function setSuccessRedirectUrl(array $successRedirectUrl): void
    {
        $this->successRedirectUrl = $successRedirectUrl;
    }
}