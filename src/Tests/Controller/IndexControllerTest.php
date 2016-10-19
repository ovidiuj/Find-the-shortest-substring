<?php
namespace Tests\Controller;


use Application\Application;
use Application\Request\HttpRequest;
use Application\Response\Response;
use Application\Routing\Route;
use Application\Routing\Router;
use Controller\IndexController;

class IndexControllerTest extends \PHPUnit_Framework_TestCase
{
    private $request;

    private $router;

    private $route;

    private $app;

    private $controller;

    public function setUp()
    {
        parent::setUp();
        $server = [
            'REQUEST_URI' => '/random-generator/300/EOT',
            'REQUEST_METHOD' => 'GET'
        ];
        $this->request = new HttpRequest($server);
        $this->router = new Router();
        $this->route = new Route('get', '/random-generator/{length}/{chars}', 'IndexController', 'index');
        $this->app = new Application($this->router);
        $this->controller = new IndexController($this->request, $this->app);
    }

    public function testGetRequestParams()
    {
        $this->router->addRoute($this->route);
        $this->router->route($this->request);
        $this->assertInternalType('array', $this->controller->getRequest()->getParameters());
    }

    public function testRandomAction()
    {
        $shortestSubStringLength = 5;
        $stream = 'THEQUICKBROWNFOXJUMPSOVERTHELAZYDOG';
        $highlightedStream = 'THEQUICKBROWNFOXJUMPS<b><u>OVERT</u></b>HELAZYDOG';
        $html = 'html';

        $stringGenerator= $this->getMock('Services\RandomStringGenerator');

        $stringGenerator
            ->expects($this->once())
            ->method('generate')
            ->will($this->returnValue($stream));


        $service= $this->getMock('Services\ShortestSubString');
        $service
            ->expects($this->once())
            ->method('setStream')
            ->with($stream);

        $service
            ->expects($this->once())
            ->method('setSearchCharacters')
            ->with(null);

        $service
            ->expects($this->once())
            ->method('getMinimumSubString');

        $service
            ->expects($this->once())
            ->method('getShortestSubStringLength')
            ->will($this->returnValue($shortestSubStringLength));

        $service
            ->expects($this->once())
            ->method('getHighlightedStream')
            ->will($this->returnValue($highlightedStream));

        $template = $this->getMock('Twig_Environment');
        $template
            ->expects($this->once())
            ->method("render")
            ->with('index.twig', ['length' => $shortestSubStringLength, 'stream' => $highlightedStream])
            ->will($this->returnValue($html));

        $application = $this->getMock('Application\Application', [], [$this->router]);
        $application->expects($this->at(0))
            ->method("get")
            ->with("random-string-generator")
            ->will($this->returnValue($stringGenerator));

        $application->expects($this->at(1))
            ->method("get")
            ->with("shortest-sub-string")
            ->will($this->returnValue($service));

        $application->expects($this->at(2))
            ->method("get")
            ->with("twig")
            ->will($this->returnValue($template));

        $controller = new IndexController($this->request, $application);
        $response = new Response(200, $html);

        $this->assertEquals($response, $controller->randomAction());
    }

    public function testHttpAction()
    {
        $shortestSubStringLength = 5;
        $stream = 'THEQUICKBROWNFOXJUMPSOVERTHELAZYDOG';
        $highlightedStream = 'THEQUICKBROWNFOXJUMPS<b><u>OVERT</u></b>HELAZYDOG';
        $html = 'html';

        $httpClient = $this->getMock('GuzzleHttp\Client');
        $stringGenerator= $this->getMock('Services\HttpStringGenerator', [], [$httpClient, 'https://test.org']);

        $stringGenerator
            ->expects($this->once())
            ->method('generate')
            ->will($this->returnValue($stream));


        $service= $this->getMock('Services\ShortestSubString');
        $service
            ->expects($this->once())
            ->method('setStream')
            ->with($stream);

        $service
            ->expects($this->once())
            ->method('setSearchCharacters')
            ->with(null);

        $service
            ->expects($this->once())
            ->method('getMinimumSubString');

        $service
            ->expects($this->once())
            ->method('getShortestSubStringLength')
            ->will($this->returnValue($shortestSubStringLength));

        $service
            ->expects($this->once())
            ->method('getHighlightedStream')
            ->will($this->returnValue($highlightedStream));

        $template = $this->getMock('Twig_Environment');
        $template
            ->expects($this->once())
            ->method("render")
            ->with('index.twig', ['length' => $shortestSubStringLength, 'stream' => $highlightedStream])
            ->will($this->returnValue($html));

        $application = $this->getMock('Application\Application', [], [$this->router]);
        $application->expects($this->at(0))
            ->method("get")
            ->with("random-string-generator")
            ->will($this->returnValue($stringGenerator));

        $application->expects($this->at(1))
            ->method("get")
            ->with("shortest-sub-string")
            ->will($this->returnValue($service));

        $application->expects($this->at(2))
            ->method("get")
            ->with("twig")
            ->will($this->returnValue($template));

        $controller = new IndexController($this->request, $application);
        $response = new Response(200, $html);

        $this->assertEquals($response, $controller->randomAction());
    }
}