<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class UserController
 * @package   App\Http\Controllers
 * @author    Victor Tores <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
interface UserControllerInterface
{
    /**
     * @param UserRequest $request
     * @return Response
     */
    public function create(UserRequest $request): Response;

    /**
     * @param integer $id
     * @return JsonResponse
     */
    public function findById(int $id): JsonResponse;
}
