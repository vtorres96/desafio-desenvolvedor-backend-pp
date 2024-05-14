<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Services\PaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class PaymentController
 * @package   App\Http\Controllers
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class PaymentController implements PaymentControllerInterface
{
    /** @var \App\Services\PaymentServiceInterface */
    private PaymentServiceInterface $paymentService;

    /**
     * PaymentController constructor.
     *
     * @param \App\Services\PaymentServiceInterface $paymentService
     */
    public function __construct(PaymentServiceInterface $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * @param PaymentRequest $request
     * @return JsonResponse
     */
    public function transfer(PaymentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->paymentService->transfer($data);

        return response()->json(
            ['data' => $response],
            Response::HTTP_CREATED
        );
    }
}
