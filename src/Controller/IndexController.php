<?php
namespace Controller;


use Application\Response\Response;

/**
 * Class IndexController
 * @package Controller
 */
class IndexController extends AbstractController
{
    /**
     * @return Response
     */
    public function randomAction()
    {
        try {
            $this->getRequestParams($this->request);
            $stringGenerator = $this->app->get('random-string-generator');

            if ($this->streamLength) {
                $stringGenerator->setLength($this->streamLength);
            }

            $stream = $stringGenerator->generate();
            $service = $this->app->get('shortest-sub-string');
            $service->setStream($stream);
            $service->setSearchCharacters($this->searchCharacters);
            $service->getMinimumSubString();

            $html = $this->app->get('twig')->render('index.twig', ['length' => $service->getShortestSubStringLength(), 'stream' => $service->getHighlightedStream()]);
            return new Response(200, $html);
        }
        catch (\Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }

    /**
     * @return Response
     */
    public function httpAction()
    {
        try {
            $stringGenerator = $this->app->get('http-string-generator');
            $this->getRequestParams($this->request);

            if ($this->streamLength) {
                $stringGenerator->setLength($this->streamLength);
            }

            $stream = $stringGenerator->generate();

            $service = $this->app->get('shortest-sub-string');
            $service->setStream($stream);
            $service->setSearchCharacters($this->searchCharacters);
            $service->getMinimumSubString();

            $html = $this->app->get('twig')->render('index.twig', ['length' => $service->getShortestSubStringLength(), 'stream' => $service->getHighlightedStream()]);
            return new Response(200, $html);
        }
        catch (\Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}