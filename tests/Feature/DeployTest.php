<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeployTest extends TestCase
{
    use RefreshDatabase;

    public function test_deploy_rejects_request_without_token(): void
    {
        $this->postJson('/deploy', headers: [
            'X-GitHub-Event' => 'push',
            'X-GitHub-Delivery' => 'abc123',
        ])->assertUnauthorized();
    }

    public function test_deploy_rejects_request_with_wrong_token(): void
    {
        $this->postJson('/deploy', headers: [
            'X-Deploy-Token' => 'salah',
            'X-GitHub-Event' => 'push',
            'X-GitHub-Delivery' => 'abc123',
        ])->assertUnauthorized();
    }

    public function test_deploy_rejects_request_without_github_headers(): void
    {
        $this->postJson('/deploy', headers: [
            'X-Deploy-Token' => config('app.deploy_token'),
        ])->assertForbidden();
    }
}
