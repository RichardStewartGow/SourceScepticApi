<?php

/**
 * Created by PhpStorm.
 * User: LordMondando
 * Date: 10/07/2016
 * Time: 17:19
 */
use Model\Sources\MySqlSource as MySqlSource;

class MySqlSourceTest extends PHPUnit_Framework_TestCase
{
    private $mySqlSource;

    private $mockRequest;
    
    private $mockDbConnection;

    private $mockDbResult;

    public function setUp()
    {
        $this->mySqlSource = new MySqlSource();
        $this->mySqlSource->setConnectionParams(
            array(
                'db_user' => 'test',
                'db_pass' => 'test',
                'db_host' => 'test',
                'db_port' => 'test',
                'db_name' => 'test'
            )
        );
    }

    public function testReturnAllCompanies()
    {
        $input = array(
                0 => array (
                    'id' => 1,
                    'name' => 'TestCompany'
                )
        );

        $this->dbOutputHelper($input);

        $mockDto = $this->createMock('Model\RequestDto');

        $mockDto->expects($this->any())
            ->method('getRequestType')
            ->willReturn('read');

        $mockDto->expects($this->any())
            ->method('getRequestQuery')
            ->willReturn('showAllCompanies');

        $output = $this->mySqlSource->query($mockDto);
        $this->assertSame($output['showAllCompanies'], $input);
    }

    public function testGetEmployees()
    {
        $input = array (
            0 => array (
                'id' => 1,
                'firstName' => 'test',
                'lastName' => 'testMan',
                'companyId' => 1
            ),
            1 => array (
                'id' => 2,
                'firstName' =>'test2',
                'lastName' => 'testMan2',
                'companyId' => 1
            )
        );

        $this->dbOutputHelper($input);

        $mockDto = $this->createMock('Model\RequestDto');

        $mockDto->expects($this->any())
            ->method('getRequestType')
            ->willReturn('read');

        $requestArray = array (
            'request' => 'showCompanyEmployees',
            'companyName' => 'TestCompany'
        );

        $mockDto->expects($this->any())
            ->method('getRequestQuery')
            ->willReturn('showCompanyEmployees');

        $mockDto->expects($this->any())
            ->method('getRequestArray')
            ->willReturn($requestArray);

        $output = $this->mySqlSource->query($mockDto);
        $this->assertSame($output['showCompanyEmployees'], $input);
    }

    public function testNewCompanyNotExists()
    {
        $this->dbOutputHelperWrites(array());

        $mockDto = $this->createMock('Model\RequestDto');

        $mockDto->expects($this->any())
            ->method('getRequestType')
            ->willReturn('write');

        $requestArray = array (
            'request' => 'newCompany',
            'companyName' => 'TestCompany'
        );

        $mockDto->expects($this->any())
            ->method('getRequestQuery')
            ->willReturn('newCompany');

        $mockDto->expects($this->any())
            ->method('getRequestArray')
            ->willReturn($requestArray);

        $output = $this->mySqlSource->query($mockDto);
        $this->assertSame(
            $output,
            array(
                'newCompany' => true
            )
        );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Company allready exists
     */
    public function testNewCompanyExistsException()
    {
        $this->dbOutputHelperWrites(
            array(
                0 => array (
                'id' => 1
            ))
        );

        $mockDto = $this->createMock('Model\RequestDto');

        $mockDto->expects($this->any())
            ->method('getRequestType')
            ->willReturn('write');

        $requestArray = array (
            'request' => 'newCompany',
            'companyName' => 'TestCompany'
        );

        $mockDto->expects($this->any())
            ->method('getRequestQuery')
            ->willReturn('newCompany');

        $mockDto->expects($this->any())
            ->method('getRequestArray')
            ->willReturn($requestArray);

        $this->mySqlSource->query($mockDto);
    }

    public function testAddEmployeeCompanySuccess()
    {
        $this->dbOutputHelperWrites(
            array(
                0 => array (
                    'id' => 1
                ))
        );

        $mockDto = $this->createMock('Model\RequestDto');

        $mockDto->expects($this->any())
            ->method('getRequestType')
            ->willReturn('write');

        $requestArray = array (
            'request' => 'addEmployee',
            'companyName' => 'TestCompany',
            'employeeForename' => 'forename',
            'employeeSurname' => 'surname'
        );

        $mockDto->expects($this->any())
            ->method('getRequestQuery')
            ->willreturn('addEmployee');

        $mockDto->expects($this->any())
            ->method('getRequestArray')
            ->willReturn($requestArray);

        $output = $this->mySqlSource->query($mockDto);

        $this->assertSame($output, array('addEmployee' => true));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage TestCompany does not exist
     */
    public function testAddEmployeeCompanyDoesNotExist()
    {
        $this->dbOutputHelperWrites(array());

        $mockDto = $this->createMock('Model\RequestDto');

        $mockDto->expects($this->any())
            ->method('getRequestType')
            ->willReturn('write');

        $requestArray = array (
            'request' => 'addEmployee',
            'companyName' => 'TestCompany',
            'employeeForename' => 'forename',
            'employeeSurname' => 'surname'
        );

        $mockDto->expects($this->any())
            ->method('getRequestQuery')
            ->willreturn('addEmployee');

        $mockDto->expects($this->any())
            ->method('getRequestArray')
            ->willReturn($requestArray);

        $this->mySqlSource->query($mockDto);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage employeeSurname is not set
     */
    public function testAddEmployeesMissingParams()
    {
        $this->dbOutputHelperWrites(array());

        $mockDto = $this->createMock('Model\RequestDto');

        $mockDto->expects($this->any())
            ->method('getRequestType')
            ->willReturn('write');

        $requestArray = array (
            'request' => 'addEmployee',
            'companyName' => 'TestCompany',
            'employeeForename' => 'forename',
        );

        $mockDto->expects($this->any())
            ->method('getRequestQuery')
            ->willreturn('addEmployee');

        $mockDto->expects($this->any())
            ->method('getRequestArray')
            ->willReturn($requestArray);

        $this->mySqlSource->query($mockDto);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage companyName not Set
     */
    public function testNewCompanyParamNotSet()
    {
        $this->dbOutputHelperWrites(array());

        $mockDto = $this->createMock('Model\RequestDto');

        $mockDto->expects($this->any())
            ->method('getRequestType')
            ->willReturn('write');

        $requestArray = array (
            'request' => 'newCompany',
        );

        $mockDto->expects($this->any())
            ->method('getRequestQuery')
            ->willReturn('newCompany');

        $mockDto->expects($this->any())
            ->method('getRequestArray')
            ->willReturn($requestArray);

        $this->mySqlSource->query($mockDto);
    }

    public function dbOutputHelperWrites($resultArray)
    {
        $this->mockDbResult = $this->createMock('\mysqli_result');

        $this->mockDbResult->expects($this->any())
            ->method('fetch_all')
            ->willReturn($resultArray);

        $this->mockStatement = $this->createMock('\mysqli_stmt');

        $this->mockStatement->expects($this->any())
            ->method('get_result')
            ->willReturn($this->mockDbResult);

        $this->mockStatement->expects($this->any())
            ->method('execute')
            ->willReturn(true);

        $this->mockDbConnection = $this->createMock('\mysqli');
        $this->mockDbConnection->expects($this->any())
            ->method('prepare')
            ->willReturn($this->mockStatement);

        $this->mySqlSource->setConnection($this->mockDbConnection);
    }

    public function dbOutputHelper($resultArray)
    {
        $this->mockDbResult = $this->createMock('\mysqli_result');

        $this->mockDbResult->expects($this->any())
            ->method('fetch_all')
            ->willReturn($resultArray);

        $this->mockDbResult->expects($this->any())
            ->method('fetch_assoc')
            ->willReturn($resultArray);

        $this->mockStatement = $this->createMock('\mysqli_stmt');

        $this->mockStatement->expects($this->any())
            ->method('get_result')
            ->willReturn($this->mockDbResult);

        $this->mockDbConnection = $this->createMock('\mysqli');
        $this->mockDbConnection->expects($this->any())
            ->method('prepare')
            ->willReturn($this->mockStatement);

        $this->mySqlSource->setConnection($this->mockDbConnection);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid Query
     */
    public function testDtoErrorException()
    {
        $mockDto = $this->createMock('Model\RequestDto');

        $this->mockDbConnection = $this->createMock('\mysqli');
        $this->mySqlSource->setConnection($this->mockDbConnection);

        $this->mySqlSource->query($mockDto);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Unable to connect to internal data source
     */
    public function testNoParamsException()
    {
        $this->mySqlSource->setConnectionParams(array());

        $mockDto = $this->createMock('Model\RequestDto');

        $this->mySqlSource->query($mockDto);
    }
}
