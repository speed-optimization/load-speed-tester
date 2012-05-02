<?php

class Jm_Filter_BytesToSize implements Zend_Filter_Interface
{

    /**
     * Convert bytes to human readable format.
     *
     * @param integer $b
     * @param integer $precision
     * @return string
     */
    function filter($b, $precision = 2)
    {
        $ret = '';
        
        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;

        if (($b >= 0) && ($b < $kb)) {
            $ret = $b . ' B';
        } elseif (($b >= $kb) && ($b < $mb)) {
            $ret = round($b / $kb, $precision) . ' KB';
        } elseif (($b >= $mb) && ($b < $gb)) {
            $ret = round($b / $mb, $precision) . ' MB';
        } elseif (($b >= $gb) && ($b < $tb)) {
            $ret = round($b / $gb, $precision) . ' GB';
        } elseif ($b >= $tb) {
            $ret = round($b / $tb, $precision) . ' TB';
        } else {
            $ret = $b . ' B';
        }

        return $ret;
    }

}