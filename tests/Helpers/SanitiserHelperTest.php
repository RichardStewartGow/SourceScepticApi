<?php

/**
 * Created by PhpStorm.
 * User: LordMondando
 * Date: 10/07/2016
 * Time: 15:00
 */
use Helpers\SanitiserHelper as SanitiserHelper;

class Test extends PHPUnit_Framework_TestCase
{
    private $sanitiserHelper;

    public function setUp()
    {
        $this->sanitiserHelper = new SanitiserHelper();
    }

    public function testMySqliStopped()
    {
        $attackArray = array (
            'companyName' => "anything' OR 'x'='x",
            'employeeForename' => "x' AND 1=(SELECT COUNT(*) FROM companies); --",
            'employeeSurname' => "x'; DROP TABLE employees; --",
        );

        $safeArray = array (
            "companyName" => "anything&#039 OR &#039x&#039&#039x",
            "employeeForename" => "x&#039 AND 1(SELECT COUNT(*) FROM companies) --",
            "employeeSurname" => "x&#039 DROP TABLE employees --"
        );

        $output = $this->sanitiserHelper->sanitiseInput($attackArray);

        $this->assertSame($output, $safeArray);
    }

    public function testMaliciousJavascriptStopped()
    {
        $attackArray = array (
            "companyName" => "<script>alert('hacked')</script>",
            "employeeForename" => "<body onload=alert('hacked')>",
            "employeeSurname" => '<img src="test" onerror=alert("hacked");>'
        );

        $safeArray = array (
            "companyName" => "&ltscript&gtalert(&#039hacked&#039)&lt/script&gt",
            "employeeForename" => "&ltbody onloadalert(&#039hacked&#039)&gt",
            "employeeSurname" => '&ltimg src&quottest&quot onerroralert(&quothacked&quot)&gt'
        );

        $output = $this->sanitiserHelper->sanitiseInput($attackArray);

        $this->assertSame($output, $safeArray);
    }

    public function testCleanInputUnaffected()
    {
        $cleanArray = array (
            'companyName' => 'test & unit',
            'employeeForename' => 'Aimée',
            'employeeSurname' => 'Müller',
            'anotherEmployeeName' => 'Reiß'
        );

        $santisedWithAmpersandChange = array (
            'companyName' => 'test and unit',
            'employeeForename' => 'Aimée',
            'employeeSurname' => 'Müller',
            'anotherEmployeeName' => 'Reiß'
        );

        $output = $this->sanitiserHelper->sanitiseInput($cleanArray);

        $this->assertSame($output, $santisedWithAmpersandChange);
    }

}
