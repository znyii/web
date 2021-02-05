<?php

namespace ZnYii\Web\Actions;

use ZnYii\Web\Widgets\Toastr\Toastr;

class DeleteAction extends BaseAction
{

    private $with = [];
    private $successMessage;
    private $successMessageKey = 'delete_success';
    private $successRedirectUrl;

    public function setWith(array $with)
    {
        $this->with = $with;
    }

    public function setSuccessMessage(array $successMessage): void
    {
        $this->successMessage = $successMessage;
    }

    public function getSuccessMessage(): array
    {
        return $this->successMessage ?: $this->getI18NextParams($this->successMessageKey);
    }

    public function setSuccessRedirectUrl($successRedirectUrl): void
    {
        $this->successRedirectUrl = $successRedirectUrl;
    }

    public function run(int $id)
    {
        try {
            $this->service->deleteById($id);
            Toastr::create($this->getSuccessMessage(), Toastr::TYPE_SUCCESS);
        } catch (\DomainException $e) {
            Toastr::create($e->getMessage(), Toastr::TYPE_WARNING);
        }
        return $this->redirect($this->successRedirectUrl);
    }
}
