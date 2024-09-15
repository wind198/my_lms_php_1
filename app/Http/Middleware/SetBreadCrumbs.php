<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class SetBreadCrumbs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $url = $request->url();
        $breadcrumbs = [
            [
                'text' => trans('noun.home'),
                'url' => route('home'),
            ],
        ];
        $resource = $request->attributes->get('resource');
        $primaryField = $request->attributes->get('primaryField');
        $indexRoute = $request->attributes->get('index_route');
        $record = $request->route($resource);

        // If both resource and index_route are found, append them to the breadcrumbs
        if ($resource && $indexRoute) {
            $breadcrumbs[] = [
                'text' => trans('noun.' . $resource),
                'url' => route($indexRoute),
            ];
            if (str_contains($url, 'show') && $record) {
                $breadcrumbs[] = [
                    'text' => $record->$primaryField,
                    'url' => route($indexRoute . ".show", [$resource => $record->id]), // Use the current URL as the edit route
                ];
            } elseif (str_contains($url, 'edit') && $record) {
                $breadcrumbs[] = [
                    'text' => $record->$primaryField,
                    'url' => route($indexRoute . ".show", [$resource => $record->id]), // Use the current URL as the edit route
                ];
                $breadcrumbs[] = [
                    'text' =>  trans('verb.edit'),
                    'url' => route($indexRoute . ".edit", [$resource => $record->id]), // Use the current URL as the edit route
                ];
            }
        }

        Inertia::share([
            'breadcrumbs' => $breadcrumbs                 // Add your breadcrumbs here...
        ]);

        return $next($request);
    }
}
