<?php

/**
 * Created by PhpStorm.
 * User: LordMondando
 * Date: 10/07/2016
 * Time: 16:58
 */
use Slim\Http\Request as Request;
use Model\RequestFactory as RequestFactory;

class RequestFactoryTest extends PHPUnit_Framework_TestCase
{
    private $requestFactory;

    public function setUp()
    {
        $this->requestFactory = new RequestFactory();
    }

    public function testWriteRequestCorrect()
    {
        $mockRequest = $this->createMock('Slim\Http\Request');

        $mockUri = $this->createMock('Slim\Http\Uri');

        $mockUri->expects($this->any())
            ->method('getPath')
            ->willReturn('write');

        $requestArray = array (
            'request' => 'addEmployee',
            'companyName' => 'unitTests',
            'employeeForename' => 'unitTest',
            'employeeSurname' => 'unitTestMan'
        );

        $mockRequest->expects($this->any())
            ->method('getParsedBody')
            ->willReturn($requestArray);

        $mockRequest->expects($this->any())
            ->method('getUri')
            ->willReturn($mockUri);

        $output = $this->requestFactory->makeRequest($mockRequest);
        $this->assertSame(get_class($output), 'Model\RequestDto');
        $this->assertSame($output->getRequestType(), 'write');
        $this->assertSame($output->getRequestArray(), $requestArray);
    }

    public function testReadRequestCorrect()
    {
        $mockRequest = $this->createMock('Slim\Http\Request');

        $mockUri = $this->createMock('Slim\Http\Uri');

        $mockUri->expects($this->any())
            ->method('getPath')
            ->willReturn('read');

        $requestArray = array (
            'request' => 'showAllCompanies',
        );

        $mockRequest->expects($this->any())
            ->method('getParsedBody')
            ->willReturn($requestArray);

        $mockRequest->expects($this->any())
            ->method('getUri')
            ->willReturn($mockUri);
        $output = $this->requestFactory->makeRequest($mockRequest);

        $this->assertSame(get_class($output), 'Model\RequestDto');
        $this->assertSame($output->getRequestType(), 'read');
        $this->assertSame($output->getRequestArray(), $requestArray);
    }

    public function testWrongRequest()
    {
        $mockRequest = $this->createMock('Slim\Http\Request');

        $mockUri = $this->createMock('Slim\Http\Uri');

        $mockUri->expects($this->any())
            ->method('getPath')
            ->willReturn('test');

        $requestArray = array (
            'request' => 'showAllCompanies',
        );

        $mockRequest->expects($this->any())
            ->method('getParsedBody')
            ->willReturn($requestArray);

        $mockRequest->expects($this->any())
            ->method('getUri')
            ->willReturn($mockUri);

        $output = $this->requestFactory->makeRequest($mockRequest);

        $this->assertSame($output->getRequestType(), 'none');
        $this->assertSame($output->getRequestQuery(), 'error');
    }
}
