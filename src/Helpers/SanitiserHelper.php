<?php
/**
 * Created by PhpStorm.
 * User: Richard Gow
 * Date: 09/07/2016
 * Time: 13:21
 */

namespace Helpers;

class SanitiserHelper
{
    /**
     * Simple method for helping to remove
     * potential for sql injection.
     * preapred statements will also be used.
     *
     * limitation is that business with & will be converted to 'and'
     *
     * @param array $input
     * @return array
     */
    public function sanitiseInput(array $input)
    {
        $output = array();

        foreach ($input as $key => $value) {
            $value = strtr($value, array(
                "=" => '',  '&' => 'and'
            ));
            $value = htmlspecialchars($value, ENT_QUOTES);
            $value = strtr($value, (array(";" => '')));
            $output[$key] = $value;
        }

        return $output;
    }
}