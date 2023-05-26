<?php
declare(strict_types=1);

namespace App\Exceptions;

use App\Transformers\BaseTransformer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        return $this->handle($request, $exception);
    }
        
    /**
     * Handles the error.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse
     */
    private function handle(Request $request, Throwable $exception): JsonResponse
    {
        $statusCode = 500;
        $message = 'Internal Server Error';
        $exceptionMap = [
            'QueryException' => 422,
            'QueryExecutionException' => 422,
            'HttpResponseException' => 422,
            'ValidationException' => 422,
            'ErrorException' => 400,
            'ModelNotFoundException' => 404,
            'NotFoundHttpException' => 404,
            'NoRequestException' => 400
        ];
        $exceptionName = class_basename($exception);

        if (array_key_exists($exceptionName, $exceptionMap)) {
            $statusCode = $exceptionMap[$exceptionName];
            $message = $this->getMessage($exceptionName, $exception, $statusCode);
        }

        $response = $this->baseTransformer->errorResponse([
            $statusCode,
            $message
        ]);
        // Response can be logged here

        return $response;
    }

    /**
     * Get appropriate message depending on the exception.
     * Add a filter for custom exceptions that need to show the exception message.
     *
     * @param string $exceptionName
     * @param Throwable $exception
     * @param int $statusCode
     * @return string
     */
    private function getMessage(string $exceptionName, Throwable $exception, int $statusCode): string
    {
        if ($exceptionName === class_basename(ValidationException::class)) {
            return $exception->getMessage();
        }

        if ($exceptionName === class_basename(ModelNotFoundException::class)) {
            /** @var ModelNotFoundException $exception */
            $model = last(explode('\\', $exception->getModel()));
            $ids = implode(',', $exception->getIds());
            return $model . ': ' . $ids . ' ' . Response::$statusTexts[$statusCode];
        }

        return Response::$statusTexts[$statusCode];
    }
}
