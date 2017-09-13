<?php
/**
 * Created by PhpStorm.
 * User: Richard Gow
 * Date: 10/07/2016
 * Time: 12:44
 */

namespace Model;
use Slim\Http\Response as Response;

class ResponseFactory
{
    /**
     * @var array
     */
    private $responseData;

    /**
     * @return array
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * @param array $responseData
     * @return ResponseFactory
     */
    public function setResponseData(array $responseData)
    {
        $this->responseData = $responseData;
        return $this;
    }

    public function buildResponse(array $responseData)
    {
        $this->setResponseData($responseData);
        $response = new Response();

        $response = $this->checkAndHandleErrors($response);

        if ($response->getStatusCode() !== 500) {
            $response = $this->loadData($response);
        }

        return $response;
    }

    private function checkAndHandleErrors(Response $response)
    {
        if (array_key_exists('error', $this->getResponseData())) {
            $response = $response->withStatus(500);
            $response = $response->withJson(
                array('error' => $this->getResponseData()['error']->getMessage())
            );
        }

        return $response;
    }

    private function loadData(Response $response)
    {
        $response = $response->withJson(array($this->getResponseData()));

        return $response;
    }
}