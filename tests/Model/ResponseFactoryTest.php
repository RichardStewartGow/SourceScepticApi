<?php

/**
 * Created by PhpStorm.
 * User: LordMondando
 * Date: 10/07/2016
 * Time: 16:10
 */
use Model\ResponseFactory as ResponseFactory;

class ResponseFactoryTest extends PHPUnit_Framework_TestCase
{
    private $responseFactory;

    public function setUp()
    {
        $this->responseFactory = new ResponseFactory();
    }

    public function testShowCompanyEmployeesResponse()
    {
        $showCompanyEmployeesArray = array (
            'showCompanyEmployees' => array(
                0 => array (
                    'id' => 1,
                    'firstName' => 'test',
                    'lastName' => 'man',
                    'companyId' => 1,
                ),
                1 => array (
                    'id' => 2,
                    'firstName' => 'test2',
                    'lastName' => 'man2',
                    'companyId' => 1,
                )
            )
        );

        $response = $this->responseFactory->buildResponse($showCompanyEmployeesArray);

        $this->assertSame(get_class($response), 'Slim\Http\Response');
        $this->assertSame($response->getStatusCode(), 200);
        $output = json_decode($response->getBody());
        $output = $output[0]->showCompanyEmployees;

        $this->assertSame($output[0]->firstName, 'test');
        $this->assertSame($output[1]->lastName, 'man2');
    }

    public function testShowCompaniesResponse()
    {
        $showAllCompaniesArray = array (
            'showAllCompanies' => array (
                0 => array (
                    'id' => 1,
                    'name' => 'TestCompany1'
                ),
                1 => array (
                    'id' => 2,
                    'name' => 'TestCompany2'
                )
            )
        );

        $response = $this->responseFactory->buildResponse($showAllCompaniesArray);
        $this->assertSame($response->getStatusCode(), 200);
        $output = json_decode($response->getBody());
        $output = $output[0]->showAllCompanies;

        $this->assertSame($output[0]->name, 'TestCompany1');
        $this->assertSame($output[1]->id, 2);
    }

    public function testCreateCompany()
    {
        $companyWritten = array (
            'newCompany' => true
        );

        $response = $this->responseFactory->buildResponse($companyWritten);

        $this->assertSame($response->getStatusCode(), 200);
        $output = json_decode($response->getBody());
        $this->assertSame($output[0]->newCompany, true);
    }

    public function testCreateNewEmployee()
    {
        $companyWritten = array (
            'addEmployee' => true
        );

        $response = $this->responseFactory->buildResponse($companyWritten);

        $this->assertSame($response->getStatusCode(), 200);
        $output = json_decode($response->getBody());
        $this->assertSame($output[0]->addEmployee, true);
    }

    public function test500()
    {
        $exceptionInput = array (
            'error' => new Exception('companyName not Set')
        );

        $response = $this->responseFactory->buildResponse($exceptionInput);
        $this->assertSame($response->getStatusCode(), 500);
        $output = json_decode($response->getBody());
        $this->assertSame($output->error, 'companyName not Set');
    }
}
