<?php

namespace AclManager\Tests\Unit;

use AclManager\Tests\TestCase;
use AclManager\Tests\TestUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Tahmid\AclManager\Facades\Acl;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tahmid\AclManager\Http\Middleware\RolePermissionCheck;
use Illuminate\Routing\Route as IlluminateRoute; // For mocking
use Mockery;

class RolePermissionCheckMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close(); // Important for Mockery
        parent::tearDown();
    }

    protected function createUser(array $attributes = [])
    {
        return TestUser::create(array_merge([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'is_superuser' => false,
        ], $attributes));
    }

    protected function mockCurrentRouteAction(string $controllerAction)
    {
        $mockedRoute = Mockery::mock(IlluminateRoute::class);
        // The 'action' property needs to be an array with a 'controller' key
        $mockedRoute->action = ['controller' => $controllerAction];
        Route::shouldReceive('current')->andReturn($mockedRoute);
    }

    /** @test */
    public function test_allows_access_if_user_has_permission()
    {
        $user = $this->createUser(['email' => 'permcheck@example.com']);
        $this->actingAs($user);

        $request = Request::create('/some-url', 'GET');
        $controllerAction = 'SomeNamespace\Http\Controllers\SomeController@someMethod';
        $expectedActionName = 'SomeController@someMethod'; // Based on middleware logic

        $this->mockCurrentRouteAction($controllerAction);
        
        $aclMock = Mockery::mock('alias:Acl');
        $aclMock->shouldReceive('can')->once()->with($expectedActionName)->andReturn(true);

        $middleware = new RolePermissionCheck();
        $response = $middleware->handle($request, function () {
            return new Response('Allowed');
        });

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Allowed', $response->getContent());
    }

    /** @test */
    public function test_aborts_if_user_does_not_have_permission()
    {
        $user = $this->createUser(['email' => 'nopermcheck@example.com']);
        $this->actingAs($user);

        $request = Request::create('/some-url', 'GET');
        $controllerAction = 'SomeNamespace\Http\Controllers\AnotherController@otherMethod';
        $expectedActionName = 'AnotherController@otherMethod';

        $this->mockCurrentRouteAction($controllerAction);

        $aclMock = Mockery::mock('alias:Acl');
        $aclMock->shouldReceive('can')->once()->with($expectedActionName)->andReturn(false);

        $middleware = new RolePermissionCheck();

        try {
            $middleware->handle($request, function () {
                return new Response('Should not reach here');
            });
            $this->fail('HttpException was not thrown.');
        } catch (HttpException $e) {
            $this->assertEquals(401, $e->getStatusCode());
        }
    }

    /** @test */
    public function test_correctly_extracts_action_name()
    {
        $user = $this->createUser(['email' => 'actionextract@example.com']);
        $this->actingAs($user);

        $request = Request::create('/admin/my-resource', 'GET');
        $controllerAction = 'App\Http\Controllers\Admin\MyResourceController@index';
        // Middleware logic: explode('Controllers\', $action)[1]
        $expectedActionName = 'Admin\MyResourceController@index';

        $this->mockCurrentRouteAction($controllerAction);

        $aclMock = Mockery::mock('alias:Acl');
        $aclMock->shouldReceive('can')->once()->with($expectedActionName)->andReturn(true);

        $middleware = new RolePermissionCheck();
        $response = $middleware->handle($request, function () {
            return new Response('Access Granted');
        });
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Access Granted', $response->getContent());
    }
}
