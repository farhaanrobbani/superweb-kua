<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectLegacyPengumuman
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = '/'.trim($request->path(), '/');

        if ($request->isMethod('GET') && ($path === '/pengumuman' || str_starts_with($path, '/pengumuman/'))) {
            $canonical = kua_navbar_page_url('pengumuman', '/pengumuman');

            if ($canonical !== '/pengumuman') {
                $rest = ltrim(substr($path, strlen('/pengumuman')), '/');
                $query = $request->getQueryString();

                $url = $canonical.($rest !== '' ? '/'.$rest : '');
                if ($query) {
                    $url .= '?'.$query;
                }

                return redirect($url, 301);
            }
        }

        return $next($request);
    }
}
