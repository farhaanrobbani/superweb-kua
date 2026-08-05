<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeployController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $token = config('app.deploy_token');

        if (blank($token) || ! hash_equals($token, (string) $request->header('X-Deploy-Token', ''))) {
            abort(401);
        }

        if (empty($request->header('X-GitHub-Event')) || empty($request->header('X-GitHub-Delivery'))) {
            abort(403);
        }

        $output = [];
        $exit = 1;

        exec('sudo ' . base_path('scripts/deploy.sh') . ' 2>&1', $output, $exit);

        return response()->json([
            'ok' => $exit === 0,
            'exit' => $exit,
            'output' => array_slice($output, -40),
        ], $exit === 0 ? 200 : 500);
    }
}
