<?php

namespace Test\Unit\Services;

use \Mockery;
use Test\TestCase;
use Core\Di\DiContainer as Di;
use App\Models\ExampleModel;
use App\Repositories\ExampleRepository;
use App\Services\ExampleService;
use Presentation\Models\WelcomeInfo;

/**
 * @coversDefaultClass \App\Services\ExampleService
 */
class ExampleServiceTest extends TestCase {

    /**
     * @test
     * @covers ::getWelcomeInfo
     */
    public function testGetWelcomeInfoReturnsViewModel() {
        $exampleModel = Mockery::mock(ExampleModel::class);
        $exampleRepository = Mockery::mock(ExampleRepository::class);
        $exampleRepository->shouldReceive('getExampleModel')->andReturn($exampleModel);
        $welcomeInfoViewModel = Mockery::mock(WelcomeInfo::class);
        $welcomeInfoViewModel->shouldReceive('connectionStatus')->andReturn('test');

        Di::set(ExampleRepository::class, $exampleRepository);
        Di::set(WelcomeInfo::class, $welcomeInfoViewModel);

        $exampleService = new ExampleService();

        $this->assertSame('test', $exampleService->getWelcomeInfo()->connectionStatus());
    }
}
