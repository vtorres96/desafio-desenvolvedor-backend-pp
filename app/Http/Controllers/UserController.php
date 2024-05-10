<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class UserController
 * @package   App\Http\Controllers
 * @author    Victor Tores <victorcdc96@gmail.com>
 * @copyright PP <www.pp.com.br>
 */
class UserController extends Controller
{
    /** @var \App\Services\UserServiceInterface $userService */
    private UserServiceInterface $userService;

    /**
     * UserController constructor.
     *
     * @param \App\Services\UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function create(UserRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $response = $this->userService->create($data);

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
