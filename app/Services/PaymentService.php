<?php

namespace App\Services;

use App\Repositories\PaymentRepositoryInterface;
use App\Services\Notification\EmailServiceInterface;
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

    /** @var \App\Services\Notification\EmailServiceInterface $emailService */
    private EmailServiceInterface $emailService;

    /**
     * PaymentService constructor.
     * @param \App\Repositories\PaymentRepositoryInterface $paymentRepository
     * @param \App\Services\UserServiceInterface $userService
     * @param \App\Services\Payment\AuthorizerServiceInterface $authorizerService
     * @param \App\Services\Notification\EmailServiceInterface $emailService
     */
    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        UserServiceInterface $userService,
        AuthorizerServiceInterface $authorizerService,
        EmailServiceInterface $emailService
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->userService = $userService;
        $this->authorizerService = $authorizerService;
        $this->emailService = $emailService;
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
            $this->notifyUsers($payer, $payee, $data['value']);

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

    /**
     * @param array $payer
     * @param array $payee
     * @param float $amount
     * @return void
     */
    private function notifyUsers(array $payer, array $payee, float $amount): void
    {
        $formatedValue = number_format($amount, 2, ',', '.');
        $emailPayer = [
            'email' => $payer["email"],
            'subject' => 'Transferência enviada com sucesso',
            'content' => "Sua transferência no valor de R$ $formatedValue foi enviada com sucesso"
        ];
        $emailPayee = [
            'email' => $payee["email"],
            'subject' => 'Transferência recebida com sucesso',
            'content' => "Você recebeu um depósito no valor de R$ $formatedValue"
        ];
        $this->emailService->sendEmail($emailPayer);
        $this->emailService->sendEmail($emailPayee);
    }
}
