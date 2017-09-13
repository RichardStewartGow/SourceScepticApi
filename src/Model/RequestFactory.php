<?php
/**
 * Created by PhpStorm.
 * User: Richard Gow
 * Date: 09/07/2016
 * Time: 14:34
 */

namespace Model;

use Slim\Http\Request as Request;
use Model\Constants as Constants;
use Helpers\SanitiserHelper as Sanitiser;
use Model\RequestDto as RequestDto;

/**
 * Basic factory pattern for creating and loading a request into a RequestDto object
 *
 * Class RequestFactory
 * @package Model
 */
class RequestFactory
{
    /**
     * @var RequestDto
     */
    private $requestDto;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Sanitiser
     */
    private $santiser;

    /**
     * @return \Model\RequestDto
     */
    public function getRequestDto()
    {
        if (!$this->requestDto) {
            $this->requestDto = new RequestDto();
        }

        return $this->requestDto;
    }

    /**
     * @param \Model\RequestDto $requestDto
     * @return RequestFactory
     */
    public function setRequestDto($requestDto)
    {
        $this->requestDto = $requestDto;
        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return Sanitiser
     */
    public function getSanitiser()
    {
        if (!$this->santiser) {
            $this->setSanitiser(new Sanitiser());
        }

        return $this->santiser;
    }

    /**
     * @param Sanitiser $santiser
     * @return RequestController
     */
    public function setSanitiser(Sanitiser $santiser)
    {
        $this->santiser = $santiser;
        return $this;
    }

    /**
     * @param Request $request
     * @return RequestDto $requestDto
     */
    public function makeRequest(Request $request)
    {
        $this->setRequest($request);
        $this->santiseRequest();
        $this->loadValues();

        return $this->getRequestDto();
    }

    private function santiseRequest()
    {
        $input = $this->getRequest()->getParsedBody();

        $this->getRequestDto()->setRequestArray(
            $this->getSanitiser()->sanitiseInput($input)
        );

        return;
    }

    private function loadValues()
    {
        $this->findRequestType();
        $this->findRequestQuery();

        return;
    }

    private function findRequestType()
    {
        $requestType = $this->getRequest()->getUri()->getPath();

        if ($requestType === Constants::TYPE_WRITE) {
            $this->getRequestDto()->setRequestType(Constants::TYPE_WRITE);
            return;
        }

        if ($requestType === Constants::TYPE_READ) {
            $this->getRequestDto()->setRequestType(Constants::TYPE_READ);
            return;
        }

        $this->getRequestDto()->setRequestType(Constants::TYPE_NONE);
        return;
    }

    private function findRequestQuery()
    {
        $requestArray = $this->getRequestDto()->getRequestArray();

        if (array_key_exists('request', $requestArray)) {
            $requestQuery = $this->makeSureRequestQueryValid($requestArray['request']);
            $this->getRequestDto()->setRequestQuery($requestQuery);
            return;
        }

        $this->getRequestDto()->setRequestQuery(Constants::TYPE_NONE);
    }

    /**
     * Load the query  and check it is valid for request type
     * if not load type error and exception will be thrown in @todo do exception
     *
     * @param $requestQuery
     * @return string
     */
    private function makeSureRequestQueryValid($requestQuery)
    {
        $validQueries = Constants::getValidQueries();
        $requestType = $this->getRequestDto()->getRequestType();

        foreach ($validQueries as $query => $type) {
            if ($requestQuery === $query && $requestType === $type) {
                return $query;
            }
        }

        return Constants::TYPE_ERROR;
    }
}