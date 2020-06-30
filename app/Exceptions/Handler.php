<?php

namespace App\Exceptions;

use App;
use BadMethodCallException;
use ErrorException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;
use TypeError;

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

    // protected $environment = { return } ;
 

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        //Removed all instances of  $request->wantsJson() to ensure only json is returned
        if ( $exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            $message =  (env('APP_ENV') === 'production') ? 
                            'Unauthorised Request' :
                            'Unauthorised: '. $exception->getMessage ();
            return $this->exceptionError ($exception, 400, $message);
	    }
        if ($exception instanceof \InvalidArgumentException) {
            $message =  (env('APP_ENV') === 'production') ? 
                                            config('constants.default_error_message') :
                                            'Exception: '. $exception->getMessage ();
            return $this->exceptionError ($exception, 400, $message);
        }

        if($exception instanceof TypeError) {
            $message =  (env('APP_ENV') === 'production') ? 
                                    'Type Error, Please try again' :
                                'Type Error: '. $exception->getMessage ();
            return $this->exceptionError ($exception, 400, $message);
        }

        if($exception instanceof ErrorException) {
            $message =  (env('APP_ENV') === 'production') ? 
                                    'Error Exception, Please try again' :
                                'Error Exception: '. $exception->getMessage ();
            return $this->exceptionError ($exception, 400, $message);
        }
        if($exception instanceof BadMethodCallException) {
            $message =  (env('APP_ENV') === 'production') ? 
                                'Error with a method call' :
                                'Error with a method call: '. $exception->getMessage ();
            return $this->exceptionError ($exception, 500, $message);
        }
        if ($exception instanceof AuthenticationException) {
            $message =  (env('APP_ENV') === 'production') ? 
                                                'Unauthenticated' :
                                                'Unauthenticated: '. $exception->getMessage ();
            return $this->exceptionError ($exception, 401, $message);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $message =  (env('APP_ENV') === 'production') ? 
                                config('constants.default_error_message') :
                                'Exception: '. $exception->getMessage ();

            return $this->exceptionError ($exception, 405, $message);
        }
        // dd($request->expectsJson());
        if($exception instanceof BindingResolutionException) {
            
            $message =  (env('APP_ENV') === 'production') ? 
                                config('constants.default_error_message') :
                                'Exception: '. $exception->getMessage ();

            return $this->exceptionError ($exception, 405, $message);
        }
        
        if (in_array ('api', $request->route ()->middleware ())) {
            $request->headers->set ('Accept', 'application/json');
        }
        // if (App::environment('testing')) {
        //     throw $exception;
        // }

        return parent::render($request, $exception);
    }

    /**
     * @param Throwable $exception
     * @return JsonResponse
     */
    public function exceptionError(Throwable $exception, int $statusCode= 400 , string $message): JsonResponse
    {
        if (env('APP_ENV') === 'production') {
            return response ()->json ([
                    'status' => 'error',
                    'data' => [],
                    'message' => $message
                ], $statusCode);
        }
        return response ()->json ([
            'status' => 'error',
            'data' => [],
            'message' => $message,
            'trace' => $exception->getTrace()
        ], $statusCode);  
    }

}
