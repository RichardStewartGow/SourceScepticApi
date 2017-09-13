<?php

/**
 * Created by PhpStorm.
 * User: Richard gow
 * Date: 09/07/2016
 * Time: 16:57
 */
namespace Model\Sources;

use Model\Constants;
use Model\Interfaces\Source as Source;
use Model\RequestDto as RequestDto;

class MySqlSource implements Source
{
    /**
     * @var array
     */
    private $connectionParams;

    /**
     * @var mysqli
     */
    private $connection;

    /**
     * @var array
     */
    private $requiredParams = array (
        'db_user', 'db_pass', 'db_host', 'db_port',
        'db_name'
    );

    /**
     * @return array
     */
    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    /*
     * @return array
     */
    public function getConnectionParams()
    {
        return $this->connectionParams;
    }

    /**
     * @return mysqli
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param mysqli $connection
     * @return MySqlSource
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @param array $params
     * @throws \Exception
     */
    private function makeDbConnection(array $params)
    {
        if (!$this->allParamsRequiredPresent()) {
            throw new \Exception('Unable to connect to internal data source');
        }

        //need to check this to allow unit tests to mock
        if (!$this->getConnection()) {
            $this->setConnection(
                new \mysqli($params['db_host'], $params['db_user'], $params['db_pass'], $params['db_name'])
            );
        }

    }

    /**
     * @param array $params
     * @return $this
     */
    public function setConnectionParams(array $params) {
        $this->connectionParams = $params;

        return $this;
    }

    /**
     * @return bool
     */
    private function allParamsRequiredPresent()
    {
        $params = $this->getConnectionParams();

        foreach ($this->requiredParams as $neededParam) {
            if (!array_key_exists($neededParam, $params) || !$params[$neededParam]) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param RequestDto $request
     * @return array
     * @throws \Exception
     */
    public function query(RequestDto $request)
    {
        $this->makeDbConnection($this->getConnectionParams());

        if ($request->getRequestQuery() === Constants::TYPE_ERROR) {
            throw new \Exception('Query Error please check your Url target');
        }

        if ($request->getRequestType() === Constants::TYPE_WRITE) {
            return $this->write($request);
        }

        if ($request->getRequestType() === Constants::TYPE_READ) {
            return $this->read($request);
        }

        throw new \Exception('Invalid Query');
    }

    /**
     * @param RequestDto $request
     * @return array
     * @throws \Exception
     */
    private function read(RequestDto $request)
    {
        if ($request->getRequestQuery() === Constants::TYPE_SHOW_COMPANIES) {
            $statement = $this->getConnection()->prepare(
                "SELECT * FROM companies"
            );
            $statement->execute();
            $results = $statement->get_result();
            $response = $results->fetch_all(MYSQLI_ASSOC);
            $response = array (Constants::TYPE_SHOW_COMPANIES => $response);

            $statement->close();

            return $response;
        }

        if ($request->getRequestQuery() === Constants::TYPE_GET_EMPLOYEES) {
            $queryArray = $request->getRequestArray();

            if (!array_key_exists(Constants::PARAM_COMPANY_NAME, $queryArray)
                || !$queryArray[Constants::PARAM_COMPANY_NAME]) {
                throw new \Exception(Constants::PARAM_COMPANY_NAME . " not Set");
            }

            $statement = $this->getConnection()->prepare(
                "SELECT `id` FROM companies WHERE `name` = (?)"
            );
            $statement->bind_param('s', $queryArray['companyName']);
            $statement->execute();
            $results = $statement->get_result();
            $companyId = $results->fetch_assoc();

            $statement = $this->getConnection()->prepare(
                "SELECT * FROM employees WHERE `companyId` = (?)"
            );
            $statement->bind_param('i', $companyId);
            $statement->execute();
            $results = $statement->get_result();
            $employees = $results->fetch_all(MYSQLI_ASSOC);

            if (count($employees) === 0) {
                $employees = 'none';
            }

            $response = array (Constants::TYPE_GET_EMPLOYEES => $employees);

            $statement->close();

            return $response;
        }

        throw new \Exception('Invalid Query');
    }

    /**
     * @param RequestDto $request
     * @return array
     * @throws \Exception
     */
    private function write(RequestDto $request)
    {
        if ($request->getRequestQuery() === Constants::TYPE_NEW_COMPANY) {
            $queryArray = $request->getRequestArray();

            if(!array_key_exists(Constants::PARAM_COMPANY_NAME, $queryArray)
                || !$queryArray[Constants::PARAM_COMPANY_NAME]) {
                throw new \Exception(Constants::PARAM_COMPANY_NAME . ' not Set');
            }

            if ($this->checkCompanyExists($queryArray[Constants::PARAM_COMPANY_NAME])) {
                throw new \Exception('Company allready exists');
            }

            $statement = $this->getConnection()->prepare(
                "INSERT INTO companies (name) VALUES (?)"
            );
            $statement->bind_param('s', $queryArray['companyName']);
            $success = $statement->execute();

            $response = array (Constants::TYPE_NEW_COMPANY => $success);

            $statement->close();

            return $response;
        }

        if ($request->getRequestQuery() === Constants::TYPE_ADD_EMPLOYEE) {
            $queryArray = $request->getRequestArray();

            //check we have the required params
            foreach (Constants::getRequiredParamsNewEmployee() as $required) {
                if (!array_key_exists($required, $queryArray)
                    || !$queryArray[$required]) {
                    throw new \Exception($required . " is not set");
                }
            }

            if (!$companyId
                = $this->checkCompanyExists($queryArray[Constants::PARAM_COMPANY_NAME])) {
                throw new \Exception($queryArray[Constants::PARAM_COMPANY_NAME] . ' does not exist');
            }

            $statement = $this->getConnection()->prepare(
                "INSERT INTO employees(firstName, lastName, companyId) VALUES (?, ?, ?)"
            );
            $statement->bind_param(
                'ssi',
                $queryArray[Constants::PARAM_EMPLOYEE_FIRST],
                $queryArray[Constants::PARAM_EMPLOYEE_SURNAME],
                $companyId
            );
            $success = $statement->execute();
            $response = array (Constants::TYPE_ADD_EMPLOYEE => $success);

            $statement->close();

            return $response;
        }

        throw new \Exception('Invalid Query');
    }

    /*
     * @param array
     * @return bool|int $response
     */
    private function checkCompanyExists($companyName)
    {
        $statement = $this->getConnection()->prepare(
            "SELECT `id` FROM companies WHERE `name` = (?)"
        );
        $statement->bind_param('s', $companyName);
        $statement->execute();
        $results = $statement->get_result();
        $results = $results->fetch_all(MYSQLI_ASSOC);
        //If result is found Id will exist, truthy = true, else return false;
        if (count($results) === 0) {
            return false;
        }
        //Should be only one row, get its id.
        $response = $results[0]['id'];

        return $response;
    }
}