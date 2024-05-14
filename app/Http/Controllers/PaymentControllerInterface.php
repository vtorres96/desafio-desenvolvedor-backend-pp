<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentControllerInterface
 * @package   App\Http\Controllers
 * @author    Victor Torres <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
interface PaymentControllerInterface
{
    /**
     * @param PaymentRequest $request
     * @return JsonResponse
     */
    public function transfer(PaymentRequest $request): JsonResponse;
}
