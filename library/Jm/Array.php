<?php

class Jm_Array
{

    const MEAN   = 'mean';
    const MEDIAN = 'median';
    const MODE   = 'mode';
    const RANGE  = 'range';

    /**
     * Return an array of all supported average types.
     * @return type
     */
    public static function types()
    {
        return array(
            self::MEAN,
            self::MEDIAN,
            self::MODE,
            self::RANGE
        );
    }

    /**
     * Calculate an average.
     *
     * @param type $array
     * @param type $output
     * @return type
     */
    public static function average($array, $output = self::MEAN)
    {
        $ret = false;

        if (is_array($array)) {

            switch ($output) {
                case self::MEAN:
                    $count = count($array);
                    $sum = array_sum($array);
                    $ret = $sum / $count;
                    break;
                case self::MEDIAN:
                    rsort($array);
                    $middle = round(count($array) / 2);
                    $ret = $array[$middle - 1];
                    break;
                case self::MODE:
                    $v = array_count_values($array);
                    arsort($v);
                    foreach ($v as $k => $v) {
                        $ret = $k;
                        break;
                    }
                    break;
                case self::RANGE:
                    sort($array);
                    $sml = $array[0];
                    rsort($array);
                    $lrg = $array[0];
                    $ret = $lrg - $sml;
                    break;
            }

            return $ret;
        }
    }

}
