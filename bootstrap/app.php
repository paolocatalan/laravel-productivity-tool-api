<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->name('api.v1.')
                ->group(base_path('routes/api_v1.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'last_active_at' => App\Http\Middleware\TrackLastActiveAt::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'AuthenticationException',
                    'message' => 'Request was not successful because it lacks valid authentication credentials for the requested resource.'
                ], 401);
            }
        })
        ->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'AccessDeniedHttpException',
                    'message' => 'You are not authorized to make this request.'
                ], 403);
            }
        })
        ->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'NotFoundHttpException',
                    'message' => 'Server cannot find the requested resource.'
                ], 404);
            }
        });
    })->create();
