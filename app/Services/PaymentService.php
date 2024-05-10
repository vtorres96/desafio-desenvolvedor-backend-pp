<?php

namespace App\Services;

use App\Repositories\PaymentRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Class PaymentService
 * @package   App\Services
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class PaymentService implements PaymentServiceInterface
{
    /** @var \App\Repositories\PaymentRepositoryInterface */
    private PaymentRepositoryInterface $paymentRepository;

    /**
     * PaymentService constructor.
     * @param \App\Repositories\PaymentRepositoryInterface $paymentRepository
     */
    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->paymentRepository->create($data);
    }
}
