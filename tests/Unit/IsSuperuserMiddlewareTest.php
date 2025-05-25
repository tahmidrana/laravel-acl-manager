<?php

namespace AclManager\Tests\Unit;

use AclManager\Tests\TestCase;
use AclManager\Tests\TestUser; // Using TestUser from TestCase
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tahmid\AclManager\Http\Middleware\IsSuperuser;

class IsSuperuserMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Set the superuser_column for tests
        config(['acl.superuser_column' => 'is_superuser']);
    }

    protected function createUser(array $attributes = [])
    {
        return TestUser::create(array_merge([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'is_superuser' => false, // Default to not superuser
        ], $attributes));
    }

    /** @test */
    public function test_allows_access_for_superuser()
    {
        $user = $this->createUser(['email' => 'super@example.com', 'is_superuser' => true]);
        $this->actingAs($user);

        $request = Request::create('/admin', 'GET');
        $middleware = new IsSuperuser();

        $response = $middleware->handle($request, function () {
            return new Response('Allowed');
        });

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Allowed', $response->getContent());
    }

    /** @test */
    public function test_aborts_for_non_superuser()
    {
        $user = $this->createUser(['email' => 'nonsuper@example.com', 'is_superuser' => false]);
        $this->actingAs($user);

        $request = Request::create('/admin', 'GET');
        $middleware = new IsSuperuser();

        try {
            $middleware->handle($request, function () {
                return new Response('Should not reach here');
            });
            $this->fail('HttpException was not thrown.');
        } catch (HttpException $e) {
            $this->assertEquals(403, $e->getStatusCode());
        }
    }

    /** @test */
    public function test_aborts_for_guest_user()
    {
        Auth::logout(); // Ensure guest user

        $request = Request::create('/admin', 'GET');
        $middleware = new IsSuperuser();

        try {
            $middleware->handle($request, function () {
                return new Response('Should not reach here');
            });
            $this->fail('HttpException was not thrown.');
        } catch (HttpException $e) {
            $this->assertEquals(403, $e->getStatusCode());
        }
    }
}
