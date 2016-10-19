<?php

namespace Services;


use Application\ServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

/**
 * Class HttpStringGenerator
 * @package Services
 */
class HttpStringGenerator implements ServiceInterface
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var string
     */
    private $requestUrl;

    /**
     * HttpStringGenerator constructor.
     * @param Client $httpClient
     * @param $requestUrl
     */
    public function __construct(Client $httpClient, $requestUrl)
    {
        $this->httpClient = $httpClient;
        $this->requestUrl = $requestUrl;
    }

    /**
     * @return string
     */
    public function generate()
    {
        try {
//            print_r($this->getHttpUrl());exit;
            $res = $this->httpClient->request('GET', $this->getHttpUrl());
            if ($res->getStatusCode() != 200) {
                throw new ServiceException($res->getBody(), $res->getStatusCode());
            }
            return (string)$res->getBody();
        } catch (BadResponseException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new ServiceException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return string
     */
    private function getHttpUrl()
    {
        return $this->requestUrl . "?" . http_build_query($this->parameters);
    }

    /**
     * @param $length
     */
    public function setLength($length)
    {
        $this->parameters['len'] = $length;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * @param $params
     */
    public function setParameters($params)
    {
        $this->parameters = $params;
    }
    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}