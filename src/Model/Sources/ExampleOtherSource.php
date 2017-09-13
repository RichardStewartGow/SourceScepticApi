<?php
/**
 * Created by PhpStorm.
 * User: Richard Gow
 * Date: 10/07/2016
 * Time: 12:31
 */

namespace Model\Sources;

use Model\RequestDto as RequestDto;


class ExampleOtherSource
{
    public function setConnectionParams(array $params)
    {
        //stub not implemented
    }

    public function getConnectionParams()
    {
        //stub not implemented
    }

    public function query(RequestDto $request)
    {
        return array ('newCompany' => 'Simply a stub to prove that datasource switching is easy');
    }
}