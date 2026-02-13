<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Inertia\Inertia;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            \Illuminate\Session\Middleware\StartSession::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, $request) {
            if ($e instanceof ValidationException) {
                return back()->withErrors($e->errors())->withInput();
            }

            if ($e instanceof AuthenticationException) {
                return redirect()->guest(route('login'));
            }

            if ($e instanceof UnauthorizedException) {
                return redirect()->guest(route('login'));
            }

            $status = $e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface
                ? $e->getStatusCode()
                : 500;

            if (in_array($status, [403, 404, 419, 500])) {
                return Inertia::render("Errors/$status", [
                    'status' => $status,
                    'message' => [
                        403 => 'Forbidden',
                        404 => 'Not Found',
                        419 => 'Page Expired',
                        500 => 'Server Error',
                    ][$status] ?? 'Something went wrong',
                ])->toResponse($request)->setStatusCode($status);
            }

            return null;
        });
    })
    ->create();
