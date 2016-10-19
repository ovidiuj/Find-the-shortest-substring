<?php

namespace Controller;


use Application\Request\HttpRequestInterface;
use Application\Response\Response;

/**
 * Class RequestTrait
 * @package Controller
 */
trait RequestTrait
{
    /**
     * @var string
     */
    public $searchCharacters;

    /**
     * @var int
     */
    public $streamLength;

    /**
     * @param HttpRequestInterface $request
     * @return Response
     */
    public function getRequestParams(HttpRequestInterface $request)
    {
        $params = $request->getParameters();

        if (empty($params) || !isset($params['chars'])) {
            return new Response(400, "Not enough arguments (missings: \"search characters\")");
        } elseif (isset($params['chars'])) {
            $this->searchCharacters = $this->sanitizeString($params['chars']);
        }

        if (isset($params['length'])) {
            $this->streamLength = $this->sanitizeInt($params['length']);
        }
    }

    /**
     * @param $val
     * @return string
     */
    public function sanitizeString($val)
    {
        return (string)trim(strip_tags($val));
    }

    /**
     * @param $val
     * @return int
     */
    public function sanitizeInt($val)
    {
        return (int)preg_replace('/[^0-9\-]/', '', $val);
    }


}