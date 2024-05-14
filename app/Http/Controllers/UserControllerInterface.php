<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class UserControllerInterface
 * @package   App\Http\Controllers
 * @author    Victor Tores <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
interface UserControllerInterface
{
    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function create(UserRequest $request): JsonResponse;

    /**
     * @param integer $id
     * @return JsonResponse
     */
    public function findById(int $id): JsonResponse;
}
