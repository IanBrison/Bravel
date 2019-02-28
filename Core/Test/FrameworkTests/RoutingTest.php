<?php

namespace Core\Test\FrameworkTests;

use \Mockery;
use Core\Test\BravelTestCase;
use Core\Di\DiContainer as Di;
use Core\Request\Request;
use Core\Session\Session;
use Core\Routing\Action;
use Core\Routing\Router;
use Core\Routing\GetRoute;
use Core\Routing\PostRoute;
use App\System\Exception\UnauthorizedActionException;
use App\System\Exception\HttpNotFoundException;

/**
 * @coversDefaultClass \Core\Routing\Router
 * @coversDefaultClass \Core\Routing\Route
 * @coversDefaultClass \Core\Routing\Action
 */
class RoutingTest extends BravelTestCase {

    private function prepareRouter(array $getRoutes, array $postRoutes): Router {
        $routes = array_merge($getRoutes, $postRoutes);
        $router = new Router();
        $router->compileRoutes($routes);
        return $router;
    }

    private function prepareGetRequest(string $pathInfo, bool $isAuthenticated) {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('getPathInfo')->andReturn($pathInfo);
        $request->shouldReceive('isPost')->andReturn(false);

        $session = Mockery::mock(Session::class);
        $session->shouldReceive('isAuthenticated')->andReturn($isAuthenticated);

        Di::set(Request::class, $request);
        Di::set(Session::class, $session);
    }

    private function preparePostRequest(string $pathInfo, bool $checkCsrfToken, bool $isAuthenticated) {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('getPathInfo')->andReturn($pathInfo);
        $request->shouldReceive('isPost')->andReturn(true);
        $request->shouldReceive('getPost')->with('_token')->andReturn('test_csrf_token');

        $session = Mockery::mock(Session::class);
        $session->shouldReceive('checkCsrfToken')->with('test_csrf_token')->andReturn($checkCsrfToken);
        $session->shouldReceive('isAuthenticated')->andReturn($isAuthenticated);

        Di::set(Request::class, $request);
        Di::set(Session::class, $session);
    }

    /**
     * @test
     * @covers Router::get
     * @covers Route::__construct
     * @covers Route::withAuth
     */
    public function testRouterRegistersGetRoutes() {
        $getRoute = Router::get('/normal/get', 'NormalGetController', 'normalGet');
        $getRoute2 = Router::get('/complex/:id/get', 'ComplexGetController', 'complexGet');
        $getRoute3 = Router::get('/auth/get', 'AuthGetController', 'authGet')->withAuth();

        $this->assertInstanceOf(GetRoute::class, $getRoute);
        $this->assertInstanceOf(GetRoute::class, $getRoute2);
        $this->assertInstanceOf(GetRoute::class, $getRoute3);

        return [$getRoute, $getRoute2, $getRoute3];
    }

    /**
     * @test
     * @covers Router::post
     */
    public function testRouterRegistersPostRoutes() {
        $postRoute = Router::post('/normal/post', 'NormalPostController', 'normalPost');
        $postRoute2 = Router::post('/complex/:id/post', 'ComplexPostController', 'complexPost');
        $postRoute3 = Router::post('/auth/post', 'AuthPostController', 'authPost')->withAuth();

        $this->assertInstanceOf(PostRoute::class, $postRoute);
        $this->assertInstanceOf(PostRoute::class, $postRoute2);
        $this->assertInstanceOf(PostRoute::class, $postRoute3);

        return [$postRoute, $postRoute2, $postRoute3];
    }

    /**
     * @test
     * @depends testRouterRegistersGetRoutes
     * @depends testRouterRegistersPostRoutes
     * @covers Router::compileRoutes
     * @covers Router::resolve
     * @covers Router::getAction
     * @covers Action::getController
     * @covers Action::getMethod
     */
    public function testRouterResolvesNormalGetRequest($getRoutes, $postRoutes) {
        $router = $this->prepareRouter($getRoutes, $postRoutes);

        $this->prepareGetRequest('/normal/get', false);

        $action = $router->resolve()->getAction();
        $this->assertSame('NormalGetController', $action->getController());
        $this->assertSame('normalGet', $action->getMethod());
    }

    /**
     * @test
     * @depends testRouterRegistersGetRoutes
     * @depends testRouterRegistersPostRoutes
     * @covers Router::compileRoutes
     * @covers Router::resolve
     * @covers Router::getAction
     * @covers Action::getController
     * @covers Action::getMethod
     */
    public function testRouterResolvesNormalPostRequest($getRoutes, $postRoutes) {
        $router = $this->prepareRouter($getRoutes, $postRoutes);

        $this->preparePostRequest('/normal/post', true, false);

        $action = $router->resolve()->getAction();
        $this->assertSame('NormalPostController', $action->getController());
        $this->assertSame('normalPost', $action->getMethod());
    }

    /**
     * @test
     * @depends testRouterRegistersGetRoutes
     * @depends testRouterRegistersPostRoutes
     * @covers Router::compileRoutes
     * @covers Router::resolve
     * @covers Router::getAction
     * @covers Action::setParams
     * @covers Action::getController
     * @covers Action::getMethod
     * @covers Action::getParams
     */
    public function testRouterResolvesComplexGetRequest($getRoutes, $postRoutes) {
        $router = $this->prepareRouter($getRoutes, $postRoutes);

        $this->prepareGetRequest('/complex/222/get', false);

        $action = $router->resolve()->getAction();
        $this->assertSame('ComplexGetController', $action->getController());
        $this->assertSame('complexGet', $action->getMethod());
        $this->assertSame(['id' => '222'], $action->getParams());
    }

    /**
     * @test
     * @depends testRouterRegistersGetRoutes
     * @depends testRouterRegistersPostRoutes
     * @covers Router::compileRoutes
     * @covers Router::resolve
     * @covers Router::getAction
     * @covers Action::setParams
     * @covers Action::getController
     * @covers Action::getMethod
     * @covers Action::getParams
     */
    public function testRouterResolvesComplexPostRequest($getRoutes, $postRoutes) {
        $router = $this->prepareRouter($getRoutes, $postRoutes);

        $this->preparePostRequest('/complex/222/post', true, false);

        $action = $router->resolve()->getAction();
        $this->assertSame('ComplexPostController', $action->getController());
        $this->assertSame('complexPost', $action->getMethod());
        $this->assertSame(['id' => '222'], $action->getParams());
    }

    /**
     * @test
     * @depends testRouterRegistersGetRoutes
     * @depends testRouterRegistersPostRoutes
     * @covers Router::compileRoutes
     * @covers Router::resolve
     * @covers Router::getAction
     * @covers Action::getController
     * @covers Action::getMethod
     */
    public function testRouterResolvesWithAuthGetRequest($getRoutes, $postRoutes) {
        $router = $this->prepareRouter($getRoutes, $postRoutes);

        $this->prepareGetRequest('/auth/get', true);

        $action = $router->resolve()->getAction();
        $this->assertSame('AuthGetController', $action->getController());
        $this->assertSame('authGet', $action->getMethod());
    }

    /**
     * @test
     * @depends testRouterRegistersGetRoutes
     * @depends testRouterRegistersPostRoutes
     * @covers Router::compileRoutes
     * @covers Router::resolve
     * @covers Router::getAction
     * @covers Action::getController
     * @covers Action::getMethod
     */
    public function testRouterResolvesWithAuthPostRequest($getRoutes, $postRoutes) {
        $router = $this->prepareRouter($getRoutes, $postRoutes);

        $this->preparePostRequest('/auth/post', true, true);

        $action = $router->resolve()->getAction();
        $this->assertSame('AuthPostController', $action->getController());
        $this->assertSame('authPost', $action->getMethod());
    }

    /**
     * @test
     * @depends testRouterRegistersGetRoutes
     * @depends testRouterRegistersPostRoutes
     * @covers Router::compileRoutes
     * @covers Router::resolve
     * @covers Router::getAction
     */
    public function testRouterThrowsExceptionWithoutAuthGetRequest($getRoutes, $postRoutes) {
        $router = $this->prepareRouter($getRoutes, $postRoutes);

        $this->prepareGetRequest('/auth/get', false);

        $this->expectException(UnauthorizedActionException::class);
        $action = $router->resolve()->getAction();
    }

    /**
     * @test
     * @depends testRouterRegistersGetRoutes
     * @depends testRouterRegistersPostRoutes
     * @covers Router::compileRoutes
     * @covers Router::resolve
     * @covers Router::getAction
     */
    public function testRouterThrowsExceptionWithoutAuthPostRequest($getRoutes, $postRoutes) {
        $router = $this->prepareRouter($getRoutes, $postRoutes);

        $this->preparePostRequest('/auth/post', true, false);

        $this->expectException(UnauthorizedActionException::class);
        $action = $router->resolve()->getAction();
    }

    /**
     * @test
     * @depends testRouterRegistersGetRoutes
     * @depends testRouterRegistersPostRoutes
     * @covers Router::compileRoutes
     * @covers Router::resolve
     * @covers Router::getAction
     */
    public function testRouterThrowsExceptionWrongCsrfPostRequest($getRoutes, $postRoutes) {
        $router = $this->prepareRouter($getRoutes, $postRoutes);

        $this->preparePostRequest('/normal/post', false, false);

        $this->expectException(\Exception::class);
        $action = $router->resolve()->getAction();
    }

    /**
     * @test
     * @depends testRouterRegistersGetRoutes
     * @depends testRouterRegistersPostRoutes
     * @covers Router::compileRoutes
     * @covers Router::resolve
     * @covers Router::getAction
     */
    public function testRouterThrowsExceptionNoRouteGetRequest($getRoutes, $postRoutes) {
        $router = $this->prepareRouter($getRoutes, $postRoutes);

        $this->prepareGetRequest('/get', false);

        $this->expectException(HttpNotFoundException::class);
        $action = $router->resolve()->getAction();
    }

    /**
     * @test
     * @depends testRouterRegistersGetRoutes
     * @depends testRouterRegistersPostRoutes
     * @covers Router::compileRoutes
     * @covers Router::resolve
     * @covers Router::getAction
     */
    public function testRouterThrowsExceptionNoRoutePostRequest($getRoutes, $postRoutes) {
        $router = $this->prepareRouter($getRoutes, $postRoutes);

        $this->preparePostRequest('/post', true, false);

        $this->expectException(HttpNotFoundException::class);
        $action = $router->resolve()->getAction();
    }
}
