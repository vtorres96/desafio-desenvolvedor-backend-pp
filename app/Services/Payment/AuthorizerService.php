<?php

namespace App\Services;

use App\Repositories\PaymentRepositoryInterface;
use App\Services\UserServiceInterface;
use Exception;

/**
 * Class PaymentService
 * @package   App\Services
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class PaymentService implements PaymentServiceInterface
{
    /** @var \App\Repositories\PaymentRepositoryInterface $paymentRepository */
    private PaymentRepositoryInterface $paymentRepository;

    /** @var \App\Services\UserServiceInterface $userService */
    private UserServiceInterface $userService;

    /**
     * PaymentService constructor.
     * @param \App\Repositories\PaymentRepositoryInterface $paymentRepository
     * @param \App\Services\UserServiceInterface $userService
     */
    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        UserServiceInterface $userService
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->userService = $userService;
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function create(array $data): array
    {
        try {
            $this->paymentRepository->beginTransaction();

            $payer = $this->userService->findById($data['payer']);
            $this->checkIsCommonUser($payer['type']);
            $this->checkHasSufficientBalance($payer['balance'], $data['value']);

            $payee = $this->userService->findById($data['payee']);
//            $authorized = this->authorizerTransaction();
            $authorized = true;
            if(!$authorized){
                throw new Exception("Trasation negada");
            }

            $this->paymentRepository->create($data);
            $this->paymentRepository->commitTransaction();
        } catch (Exception $exception) {
            $this->paymentRepository->rollbackTransaction();
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param string $type
     * @throws Exception
     */
    private function checkIsCommonUser(string $type): void
    {
        if ($type !== UserServiceInterface::COMMON) {
            throw new Exception('O usuário payee deve ser do tipo "common"');
        }
    }

    /**
     * @param float $balance
     * @param $value
     * @throws Exception
     */
    private function checkHasSufficientBalance(float $balance, $value)
    {
        if ($balance < $value) {
            throw new Exception('Saldo insuficiente para realizar o pagamento');
        }
    }
}
