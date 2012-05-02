<?php

class Jm_Helper
{

    /**
     * Print a Table Of Contents like line.
     *
     * @param type $leftString
     * @param type $rightString
     * @param type $separator
     * @param type $lineLength
     */
    public static function printLine($leftString, $rightString, $separator = '.', $lineLength = 60)
    {
        $extraPadding = 4;

        $padding = $lineLength - ($extraPadding + strlen($rightString));

        $leftString = str_pad($leftString, $padding, $separator, STR_PAD_RIGHT);

        echo sprintf('%s%s', $leftString, $rightString);
        echo PHP_EOL;
    }

}
