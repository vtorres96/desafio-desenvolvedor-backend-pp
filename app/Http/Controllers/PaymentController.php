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
class PaymentController extends Controller
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
    public function create(PaymentRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $response = $this->paymentService->create($data);

            return response()->json(
                ['data' => $response],
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return response()->json(
                ['message' => 'Falha ao processar requisição'],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
