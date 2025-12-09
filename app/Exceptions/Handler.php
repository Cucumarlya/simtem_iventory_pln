<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\HttpResponseException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        
        // Handle URL encoding errors
        $this->renderable(function (\Exception $e, $request) {
            if ($e instanceof \Illuminate\Http\Exceptions\MalformedHttpException) {
                return response()->view('errors.400', [
                    'message' => 'Invalid URL encoding detected. Please check your URL.',
                    'error' => $e->getMessage()
                ], 400);
            }
        });
    }
    
    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        // Log semua exception dengan detail
        \Log::error('Exception occurred: ' . $exception->getMessage(), [
            'exception' => $exception,
            'trace' => $exception->getTraceAsString(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
        
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
        // Untuk debug, tampilkan error detail
        if (app()->environment('local')) {
            return parent::render($request, $exception);
        }
        
        // Handle validation exceptions
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        
        // Handle 404 errors
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }
        
        // Handle 500 errors
        if ($exception instanceof \Exception) {
            \Log::error('Server Error: ' . $exception->getMessage(), [
                'exception' => $exception,
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->view('errors.500', [
                'message' => 'Terjadi kesalahan server. Silakan coba lagi.',
                'error_code' => $exception->getCode(),
            ], 500);
        }
        
        return parent::render($request, $exception);
    }
}