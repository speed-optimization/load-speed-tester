<?php

class Jm_System
{

    /**
     * Clear CLI screen.
     *
     * @return type
     */
    public static function clearScreen()
    {
        return system('clear');
    }

    /**
     * Execute PhantomJS script and return its return value.
     *
     * @param type $script
     * @param type $p1
     * @param type $p2
     * @param type $p3
     * @return type
     */
    public static function execPhantomJs($script, $p1 = null, $p2 = null, $p3 = null)
    {
        $config = Zend_Registry::get('config');

        if (!is_file($config->phantomJsExec)) {
            $error  = PHP_EOL;
            $error .= 'PhantomJs (http://phantomjs.org) needs to be installed at:' . PHP_EOL;
            $error .= '  ' . $config->phantomJsExec . PHP_EOL;
            $error .= PHP_EOL;
            die($error);
        }

        $filename = realpath(PATH_LIBRARY . '/PhantomJs/' . $script);

        if (!is_file($filename)) {
            $error  = PHP_EOL;
            $error .= 'Cannot load PhantomJs script:' . PHP_EOL;
            $error .= '  ' . $script . PHP_EOL;
            $error .= PHP_EOL;
            die($error);
        }

        $exec = sprintf('%s %s %s %s %s', $config->phantomJsExec, $filename, $p1, $p2, $p3);
        $exec = trim($exec);

        $ret = shell_exec($exec);
        $ret = trim($ret);

        return $ret;
    }

}
