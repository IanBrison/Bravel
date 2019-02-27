<?php

namespace Test\Unit\ViewModels;

use \Mockery;
use Test\TestCase;
use Core\Di\DiContainer as Di;
use App\Models\ExampleModel;
use Presentation\Models\WelcomeInfo;

/**
 * @coversDefaultClass \Presentation\Models\WelcomeInfo
 */
class WelcomeInfoTest extends TestCase {

    /**
     * @test
     * @covers ::connectionStatus
     */
    public function testReturnsTrueConnectionStatus() {
        $exampleModel = Mockery::mock(ExampleModel::class);
        $exampleModel->shouldReceive('isConnected')->andReturn(true);

        $welcomeInfo = new WelcomeInfo($exampleModel);

        $this->assertSame('Database Connected Successfully!!', $welcomeInfo->connectionStatus());
    }

    /**
     * @test
     * @covers ::connectionStatus
     */
    public function testReturnsFalseConnectionStatus() {
        $exampleModel = Mockery::mock(ExampleModel::class);
        $exampleModel->shouldReceive('isConnected')->andReturn(false);

        $welcomeInfo = new WelcomeInfo($exampleModel);

        $this->assertSame('Database Connection Failed!!', $welcomeInfo->connectionStatus());
    }
}
