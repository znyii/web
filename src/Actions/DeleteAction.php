<?php

namespace ZnYii\Web\Actions;

use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;

class DeleteAction extends BaseAction
{

    private $with = [];
    private $successMessage;
    private $successMessageKey = 'delete_success';
    private $successRedirectUrl;

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
            $this->toastrService->success($this->getSuccessMessage());
        } catch (\DomainException $e) {
            $this->toastrService->warning($e->getMessage());
        }
        return $this->redirect($this->successRedirectUrl);
    }
}
