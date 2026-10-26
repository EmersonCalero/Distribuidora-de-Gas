<?php

use App\Exceptions\ReglaNegocioException;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [ForceJsonResponse::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 404: ruta o registro inexistente
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Recurso no encontrado.'], 404);
            }
        });

        // 409: restricción de integridad (duplicado o registro con dependencias)
        $exceptions->render(function (QueryException $e, Request $request) {
            if ($request->is('api/*') && $e->getCode() === '23000') {
                return response()->json([
                    'message' => 'Operación rechazada por una restricción de integridad (registro duplicado o con dependencias).',
                ], 409);
            }
        });

        // 422: reglas de negocio (RN-xx)
        $exceptions->render(function (\RuntimeException $e, Request $request) {
            $esRegla = $e instanceof ReglaNegocioException || str_starts_with($e->getMessage(), 'RN-');

            if ($request->is('api/*') && $esRegla) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
        });
    })->create();
    