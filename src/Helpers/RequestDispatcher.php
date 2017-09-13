<?php
/**
 * Created by PhpStorm.
 * User: Richard Gow
 * Date: 09/07/2016
 * Time: 15:55
 */

namespace Helpers;

use Model\RequestDto as RequestDto;
use Slim\Http\Response as Response;
use Helpers\IoHandler as IoHandler;
use Model\ResponseFactory as ResponseFactory;

class RequestDispatcher
{
    /**
     * @var IoHandler
     */
    private $ioHandler;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @return \Helpers\IoHandler
     */
    public function getIoHandler()
    {
        if (!$this->ioHandler) {
            $this->ioHandler = new IoHandler();
        }

        return $this->ioHandler;
    }

    /**
     * @param IoHandler $ioHandler
     * @return RequestDispatcher
     */
    public function setIoHandler(IoHandler $ioHandler)
    {
        $this->ioHandler = $ioHandler;
        return $this;
    }

    /**
     * @return ResponseFactory
     */
    public function getResponseFactory()
    {
        if (!$this->responseFactory) {
            $this->responseFactory = new ResponseFactory();
        }

        return $this->responseFactory;
    }

    /**
     * @param ResponseFactory $responseFactory
     * @return RequestDispatcher
     */
    public function setResponseFactory($responseFactory)
    {
        $this->responseFactory = $responseFactory;
        return $this;
    }

    /**
     * @param RequestDto $requestDto
     *
     * @return Response $responseDto
     */
    public function dispatch(RequestDto $requestDto)
    {
        $ioHandler = $this->getIoHandler();
        try {
            $sourceConnection = $ioHandler->createConnection();
            $response = $sourceConnection->query($requestDto);
        } catch (\Exception $e) {
            $response = array ('error' => $e);
        }

        $response = $this->getResponseFactory()->buildResponse($response);

        return $response;
    }
}