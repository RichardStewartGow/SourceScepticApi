<?php
/**
 * Created by PhpStorm.
 * User: Richard Gow
 * Date: 09/07/2016
 * Time: 14:04
 */

namespace Model;

use Slim\Http\Request as Request;
use Helpers\SanitiserHelper as Sanitiser;
use Model\Constants as Constants;

/**
 * Class Request
 * @package Model
 *
 * A simple Dto for handling requests.
 */
class RequestDto
{
    /**
     * @var string
     */
    private $requestType;

    /**
     * @var string
     */
    private $requestQuery;

    /**
     * @var array
     */
    private $requestArray;

    /**
     * @return string
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * @param string $requestType
     */
    public function setRequestType($requestType)
    {
        $this->requestType = $requestType;
    }

    /**
     * @return string
     */
    public function getRequestQuery()
    {
        return $this->requestQuery;
    }

    /**
     * @param string $requestQuery
     * @return RequestDto
     */
    public function setRequestQuery($requestQuery)
    {
        $this->requestQuery = $requestQuery;
        return $this;
    }

    /**
     * @return array
     */
    public function getRequestArray()
    {
        return $this->requestArray;
    }

    /**
     * @param array $requestArray
     * @return RequestController
     */
    public function setRequestArray(array $requestArray)
    {
        $this->requestArray = $requestArray;
        return $this;
    }

    
}