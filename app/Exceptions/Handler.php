<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable               $exception
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function render($request, Throwable $exception): ResponseAlias
    {
        $request->headers->add(['X-Requested-With' => 'XMLHttpRequest']);
        if ($exception instanceof InvalidArgumentException) {
            $exception = new HttpException(ResponseAlias::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        if ($exception instanceof ModelNotFoundException) {
            $exception = new NotFoundHttpException("Recurso não encontrado", $exception);
        }

        return parent::render($request, $exception);
    }
}
