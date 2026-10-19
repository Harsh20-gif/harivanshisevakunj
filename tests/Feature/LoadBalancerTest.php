<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoadBalancerTest extends TestCase
{
    public function test_health_check_endpoint_responds_ok_for_load_balancer_probes(): void
    {
        $response = $this->get('/up');

        $response->assertStatus(200);
    }

    public function test_trusted_proxy_correctly_resolves_client_ip_behind_load_balancer(): void
    {
        $clientIp = '203.0.113.195';

        $response = $this->withHeaders([
            'X-Forwarded-For' => $clientIp,
        ])->get(route('home'));

        $response->assertStatus(200);
        $this->assertSame($clientIp, request()->ip());
    }

    public function test_trusted_proxy_correctly_detects_https_behind_ssl_terminating_load_balancer(): void
    {
        $response = $this->withHeaders([
            'X-Forwarded-Proto' => 'https',
            'X-Forwarded-Port'  => '443',
        ])->get(route('home'));

        $response->assertStatus(200);
        $this->assertTrue(request()->isSecure());
    }

    public function test_balanced_page_loader_is_rendered_on_public_pages(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('id="page-balanced-loader"', false);
        $response->assertSee('page-loader', false);
        $response->assertSee('॥ राधावल्लभ श्री हरिवंश ॥', false);
        $response->assertSee('Shri Dham Vrindavan Seva', false);
        $response->assertSee('role="status"', false);
    }
}
