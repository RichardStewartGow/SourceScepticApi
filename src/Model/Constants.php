<?php
/**
 * Created by PhpStorm.
 * User: Richard Gow
 * Date: 09/07/2016
 * Time: 12:18
 */

namespace Model;


class Constants
{
    const TYPE_READ = 'read';
    const TYPE_WRITE = 'write';
    const TYPE_BUSINESS = 'company';
    const TYPE_PERSON = 'employee';
    const TYPE_NONE = 'none';
    const TYPE_ERROR = 'error';
    const TYPE_NEW_COMPANY = 'newCompany';
    const TYPE_ADD_EMPLOYEE = 'addEmployee';
    const TYPE_SHOW_COMPANIES = 'showAllCompanies';
    const TYPE_GET_EMPLOYEES = 'showCompanyEmployees';
    const SOURCES_CONFIG_PATH = '../conf/sources.yaml';
    const PARAM_COMPANY_NAME = 'companyName';
    const PARAM_EMPLOYEE_FIRST = 'employeeForename';
    const PARAM_EMPLOYEE_SURNAME = 'employeeSurname';

    public static function getValidQueries()
    {
        return array (
            self::TYPE_NEW_COMPANY => self::TYPE_WRITE,
            self::TYPE_SHOW_COMPANIES => self::TYPE_READ,
            self::TYPE_ADD_EMPLOYEE => self::TYPE_WRITE,
            self::TYPE_GET_EMPLOYEES => self::TYPE_READ,
        );
    }

    public static function getRequiredParamsNewEmployee()
    {
        return array (
            self::PARAM_COMPANY_NAME,
            self::PARAM_EMPLOYEE_FIRST,
            self::PARAM_EMPLOYEE_SURNAME,
        );
    }

}