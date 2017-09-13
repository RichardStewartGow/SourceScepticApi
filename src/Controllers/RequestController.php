<?php
/**
 * Created by PhpStorm.
 * User: Richard Gow
 * Date: 09/07/2016
 * Time: 10:57
 */

namespace Controllers;

use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use Model\RequestFactory as RequestFactory;
use Helpers\RequestDispatcher as RequestDispatcher;

class RequestController
{
    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var RequestDispatcher
     */
    private $requestDispatcher;

    /**
     * @return RequestFactory
     */
    public function getRequestFactory()
    {
        if (!$this->requestFactory) {
            $this->setRequestFactory(
                new RequestFactory()
            );
        }

        return $this->requestFactory;
    }

    /**
     * @param RequestFactory $requestFactory
     * @return RequestController
     */
    public function setRequestFactory(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
        return $this;
    }

    /**
     * @return RequestDispatcher
     */
    public function getRequestDispatcher()
    {
        if (!$this->requestDispatcher) {
            $this->requestDispatcher = new RequestDispatcher();
        }

        return $this->requestDispatcher;
    }

    /**
     * @param RequestDispatcher $requestDispatcher
     * @return RequestController
     */
    public function setRequestDispatcher($requestDispatcher)
    {
        $this->requestDispatcher = $requestDispatcher;
        return $this;
    }

    /**
     * @param Request $request
     *
     * @return Response $response
     */
    public function processRequest(Request $request)
    {
        $requestFactory = $this->getRequestFactory();
        $requestDto = $requestFactory->makeRequest($request);
        $response = $this->getRequestDispatcher()->dispatch($requestDto);

        return $response;
    }

}