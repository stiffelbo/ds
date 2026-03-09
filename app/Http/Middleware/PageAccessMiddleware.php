<?php

namespace App\Http\Middleware;

use App\Access\AccessResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PageAccessMiddleware
{
    public function __construct(
        protected AccessResolver $accessResolver
    ) {}

    public function handle(Request $request, Closure $next, ?string $resourceKey = null): Response
    {
        $resolvedResourceKey = $this->resolveResourceKey($request, $resourceKey);

        if (!$resolvedResourceKey) {
            abort(500, 'PageAccessMiddleware: unable to resolve resource key.');
        }

        $accessContext = $this->accessResolver->resolve(
            user: $request->user(),
            resourceKey: $resolvedResourceKey,
            method: $request->method(),
        );

        $request->attributes->set('access_context', $accessContext);

        if (!$accessContext->isAllowed()) {
            abort(403, $accessContext->denyReason ?? 'Forbidden');
        }

        return $next($request);
    }

    protected function resolveResourceKey(Request $request, ?string $resourceKey = null): ?string
    {
        if (is_string($resourceKey) && trim($resourceKey) !== '') {
            return trim($resourceKey);
        }

        $route = $request->route();

        if ($route) {
            $routeResourceKey = $route->defaults['resourceKey'] ?? null;
            if (is_string($routeResourceKey) && trim($routeResourceKey) !== '') {
                return trim($routeResourceKey);
            }
        }

        $routeParam = $request->route('resourceKey');
        if (is_string($routeParam) && trim($routeParam) !== '') {
            return trim($routeParam);
        }

        $path = trim($request->path(), '/');
        if ($path !== '') {
            $segments = explode('/', $path);

            if (!empty($segments)) {
                return trim((string) $segments[0]);
            }
        }

        return null;
    }
}