<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\AuthCodeRepeatException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if (!env('APP_DEBUG') && $request->is('api*')) {
            $message  = 'Что-то пошло не так...';
            $error    = 500;
            $response = responseApi()->setSuccess(false);

            // кастомная ошибка
            if ($exception instanceof ApiException) {
                $message = $exception->getMessage();
                $error            = $exception->getCode();

                return $response->setError($error)->setMessage($message)->get();
            }

            // ошибка валидации
            if ($exception instanceof ValidationException) {
                $message = '';
                $error   = 422;

                foreach ($exception->errors() as $item) {
                    foreach ($item as $rule) {
                        $message .= ' ' . $rule;
                    }
                }

                return $response->setError($error)->setMessage(trim($message))->get();
            }

            // не авторизован
            if ($exception instanceof AuthenticationException || ($exception instanceof HttpException && $exception->getStatusCode() == 401)) {
                $message = 'Вы не авторизованы';
                $error            = 401;

                return $response->setError($error)->setMessage($message)->get();
            }

            // нет прав доступа
            if ($exception instanceof HttpException && $exception->getStatusCode() == 403) {
                $message = 'У вас нет прав доступа к этому ресурсу';
                $error            = 403;

                return $response->setError($error)->setMessage($message)->get();
            }

            // не найден метод
            if ($exception instanceof HttpException && $exception->getStatusCode() == 404) {
                $message = 'Метод не найден';
                $error            = 404;

                return $response->setError($error)->setMessage($message)->get();
            }

            // модель не найдена
            if ($exception instanceof ModelNotFoundException) {
                $message = "Модель {$exception->getModel()} не найдена";
                $error            = 404;

                return $response->setError($error)->setMessage($message)->get();
            }

            // модель не найдена
            if ($exception instanceof AuthCodeRepeatException) {
                $message = "Повторно отправить код можно только через 2 минуты";
                $error            = 400;

                return $response->setError($error)->setMessage($message)->get();
            }

            return $response->setError($error)->setMessage($message)->get();
        }

        return parent::render($request, $exception);
    }
}
