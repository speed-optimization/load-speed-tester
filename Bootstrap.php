<?php

/* -------------------------------------------------------------------------- */

/**
 * Paths
 */
define('PATH_ROOT'   , __DIR__);
define('PATH_LIBRARY', PATH_ROOT . '/library');
define('PATH_CONFIG' , PATH_ROOT . '/config');

/**
 * Default Zend_Console_Getopt values
 */
define('GETOPT_DEFAULT_ROUNDS'  ,  5);
define('GETOPT_DEFAULT_STRATEGY', 'desktop');

/* -------------------------------------------------------------------------- */

/**
 * Set up Zend Framework
 */
set_include_path (PATH_LIBRARY . PATH_SEPARATOR);

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Jm_');

/* -------------------------------------------------------------------------- */

/**
 * Load configuration file
 */
$configFilename = PATH_CONFIG . '/config.ini';
if (!is_file($configFilename)) {
    $configFilename = PATH_CONFIG . '/config.ini.dist';
}

Zend_Registry::set('config', new Zend_Config_Ini($configFilename) );

/* -------------------------------------------------------------------------- */