<?php
declare(strict_types=1);

namespace App\Exceptions;

use App\Transformers\BaseTransformer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ValidationException::class,
    ];

    /**
     * @var BaseTransformer
     */
    private BaseTransformer $baseTransformer;

    /**
     * ErrorHandler constructor.
     * @param BaseTransformer $baseTransformer
     */
    public function __construct(BaseTransformer $baseTransformer)
    {
        $this->baseTransformer = $baseTransformer;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  Throwable  $exception
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Throwable  $exception
     * @return Response|JsonResponse
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        return $this->handle($exception);
    }
        
    /**
     * Handles the error.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse
     */
    private function handle(Throwable $exception): JsonResponse
    {
        $statusCode = 500;
        $message = 'Internal Server Error';
        // Since there are no request paramaters to be validated:
        // - ValidationException will not be caught and will not be removed in the $dontReport variable 
        // - Will also not use the $request from the render method
        $exceptionMap = [
            'DBALException '        => 422,
            'ErrorException'        => 400,
            'HttpResponseException' => 422,
            'NotFoundHttpException' => 404,
            'QueryException '       => 500,
        ];
        $exceptionName = class_basename($exception);

        if (array_key_exists($exceptionName, $exceptionMap)) {
            $statusCode = $exceptionMap[$exceptionName];
            $message = Response::$statusTexts[$statusCode];
        }

        $response = $this->baseTransformer->errorResponse([
            $statusCode,
            $message
        ]);
        // Response can be logged here

        return $response;
    }
}
