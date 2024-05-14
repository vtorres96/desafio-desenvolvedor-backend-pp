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
class UserController implements UserControllerInterface
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
        $data = $request->validated();
        $response = $this->userService->update(8, $data);

        return response()->json(
            ['data' => $response],
            Response::HTTP_CREATED
        );
    }

    /**
     * @param integer $id
     * @return JsonResponse
     */
    public function findById(int $id): JsonResponse
    {
        $response = $this->userService->findById($id);

        return response()->json(
            ['data' => $response],
            Response::HTTP_OK
        );
    }
}
