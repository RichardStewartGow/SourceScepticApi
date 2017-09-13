<?php
/**
 * Created by PhpStorm.
 * User: Richard Gow
 * Date: 09/07/2016
 * Time: 16:39
 */

namespace Model\Interfaces;

use Model\RequestDto as RequestDto;

interface Source
{
    public function setConnectionParams(array $params);

    public function getConnectionParams();

    /**
     * @param RequestDto $request
     * @return array $data
     */
    public function query(RequestDto $request);
}