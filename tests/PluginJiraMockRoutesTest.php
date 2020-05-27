<?php
namespace tests;

use Dotenv\Dotenv;
use extas\components\jsonrpc\App;
use extas\components\plugins\jsonrpc\PluginJiraMockRoutes;
use PHPUnit\Framework\TestCase;

/**
 * Class PluginJiraMockRoutesTest
 *
 * @package tests
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginJiraMockRoutesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
    }

    public function testPlugin()
    {
        $app = App::create();
        $plugin = new PluginJiraMockRoutes();
        $plugin($app);
        $routes = $app->getRouteCollector()->getRoutes();
        /**
         * - /api/jsonrpc
         * - /specs
         * - /
         */
        $this->assertCount(3, $routes);
    }
}
