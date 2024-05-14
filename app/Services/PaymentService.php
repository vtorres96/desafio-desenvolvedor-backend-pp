<?php

namespace App\Services;

use App\Repositories\PaymentRepositoryInterface;
use App\Services\Payment\AuthorizerServiceInterface;
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

    /** @var \App\Services\Payment\AuthorizerServiceInterface $authorizerService */
    private AuthorizerServiceInterface $authorizerService;

    /**
     * PaymentService constructor.
     * @param \App\Repositories\PaymentRepositoryInterface $paymentRepository
     * @param \App\Services\UserServiceInterface $userService
     * @param \App\Services\Payment\AuthorizerServiceInterface $authorizerService
     */
    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        UserServiceInterface $userService,
        AuthorizerServiceInterface $authorizerService
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->userService = $userService;
        $this->authorizerService = $authorizerService;
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function transfer(array $data): array
    {
        try {
            $this->paymentRepository->beginTransaction();

            $payer = $this->userService->findById($data['payer']);

            $this->checkIsCommonUser($payer['type']);
            $this->checkHasSufficientBalance($payer['balance'], $data['value']);

            $payee = $this->userService->findById($data['payee']);

            $this->checkPossibilityMakingTransfer();

            $this->changeBalance($payer, -$data['value']);
            $this->changeBalance($payee, $data['value']);
            $response = $this->paymentRepository->create($data)->toArray();

            $this->paymentRepository->commitTransaction();
        } catch (Exception $exception) {
            $this->paymentRepository->rollbackTransaction();
            throw new Exception($exception->getMessage());
        }

        return $response;
    }

    /**
     * @param string $type
     * @throws Exception
     */
    private function checkIsCommonUser(string $type): void
    {
        if ($type !== UserServiceInterface::COMMON) {
            throw new Exception('O usuário pagador (payee) deve ser do tipo "common" para enviar transferência');
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

    /**
     * @throws Exception
     */
    private function checkPossibilityMakingTransfer()
    {
        $authorized = $this->authorizerService->checkTransactionIsAuthorized();
        if (!$authorized) {
            throw new Exception("Transação negada");
        }
    }

    /**
     * @param array $user
     * @param float $amount
     * @throws Exception
     */
    private function changeBalance(array $user, float $amount): void
    {
        $newBalance = [
            'balance' => $user['balance'] + $amount
        ];

        $this->userService->update($user['id'], $newBalance);
    }
}
