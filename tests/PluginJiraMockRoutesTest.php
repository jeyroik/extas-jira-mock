<?php
namespace tests;

use Dotenv\Dotenv;
use extas\components\extensions\TSnuffExtensions;
use extas\components\http\TSnuffHttp;
use extas\components\jira\JiraRoute;
use extas\components\jira\JiraRouteRepository;
use extas\components\jira\routes\RoutePrepared;
use extas\components\jsonrpc\App;
use extas\components\plugins\jsonrpc\PluginJiraMockRoutes;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\samples\parameters\ISampleParameter;
use PHPUnit\Framework\TestCase;

/**
 * Class PluginJiraMockRoutesTest
 *
 * @package tests
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginJiraMockRoutesTest extends TestCase
{
    use TSnuffHttp;
    use TSnuffExtensions;

    protected IRepository $routeRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->routeRepo = new JiraRouteRepository();
        $this->addReposForExt([
            'jiraRouteRepository' => JiraRouteRepository::class
        ]);
    }

    protected function tearDown(): void
    {
        $this->routeRepo->delete([JiraRoute::FIELD__NAME => '/test']);
        $this->deleteSnuffExtensions();
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

    public function testPluginRun()
    {
        $app = App::create();
        $plugin = new PluginJiraMockRoutes();
        $plugin($app);
        $routes = $app->getRouteCollector()->getRoutes();
        $this->createRoute('/prepared.json', RoutePrepared::class);

        putenv('EXTAS__JIRA_MOCK__HOST=test');
        putenv('EXTAS__JIRA_MOCK__BASE_PATH=' . getcwd() . '/tests');

        foreach ($routes as $route) {
            if ($route->getPattern() == '/') {
                $dispatcher = $route->getCallable();
                $response = $dispatcher(
                    $this->getPsrRequest('', [], '', 'GET', '/test'),
                    $this->getPsrResponse(),
                    []
                );

                $this->assertEquals(
                    '{"test": "is ok"}',
                    (string) $response->getBody(),
                    'Response mismatched: ' . $response->getBody()
                );
            }
        }
    }

    /**
     * @param string $path
     * @param string $class
     */
    protected function createRoute(string $path, string $class): void
    {
        $this->routeRepo->create(new JiraRoute([
            JiraRoute::FIELD__NAME => '/test',
            JiraRoute::FIELD__CLASS => $class,
            JiraRoute::FIELD__PARAMETERS => [
                'path' => [
                    ISampleParameter::FIELD__NAME => 'path',
                    ISampleParameter::FIELD__VALUE => $path
                ]
            ]
        ]));
    }
}
