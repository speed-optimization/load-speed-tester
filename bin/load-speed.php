<?php

require_once __DIR__ . '/../Bootstrap.php';

$config = Zend_Registry::get('config');

/* -------------------------------------------------------------------------- */

try {

    $opts = new Zend_Console_Getopt(
        array(
            'url|u=s'    => 'URL of page under test.',
            'rounds|r=i' => 'Number of times to download the page in order to calculate load time. ' .
                            'Default = "' . GETOPT_DEFAULT_ROUNDS . '".',
            'help|h'     => 'Show help message.',
            'version|v'  => 'Show version.',
        )
    );

    $opts->parse();

    if ($opts->getOption('help')) {
        throw new Zend_Console_Getopt_Exception('',
                $opts->getUsageMessage());
    }

    if ($opts->getOption('version')) {
        throw new Zend_Console_Getopt_Exception('Version ' . $config->version,
                $opts->getUsageMessage());
    }

    $url = (string) $opts->getOption('url');

    if (empty($url)) {
        throw new Zend_Console_Getopt_Exception('URL is missing.',
                $opts->getUsageMessage());
    }

    $rounds = (integer) $opts->getOption('rounds');

    if (0 === $rounds) {
        $rounds = GETOPT_DEFAULT_ROUNDS;
    }

} catch (Zend_Console_Getopt_Exception $e) {
    echo PHP_EOL . 'Load Speed';
    echo PHP_EOL . '----------';
    if ($e->getMessage()) {
        echo PHP_EOL . PHP_EOL . $e->getMessage();
    }
    echo PHP_EOL . PHP_EOL . $e->getUsageMessage();
    echo PHP_EOL;
    exit();
}

/* -------------------------------------------------------------------------- */

Jm_System::clearScreen();

echo PHP_EOL;

Jm_Helper::printLine('Load Speed Results for:', null, ' ');
Jm_Helper::printLine('  ' . $url              , null, ' ');

echo PHP_EOL;

/* -------------------------------------------------------------------------- */

$results = array();

Jm_Helper::printLine('Rounds (' . $rounds . ')', null, ' ');

for ($i = 1; $i <= $rounds; $i++) {
    $results[$i] = Jm_System::execPhantomJs('loadspeed.js', $url);
    if (0 == $results[$i]) {
        Jm_Helper::printLine('+- ' . $i, 'Error');
    } else {
        Jm_Helper::printLine('+- ' . $i, $results[$i] . ' ms');
    }
}

echo PHP_EOL;

if (array_sum($results) < count($results)) {
    Jm_Helper::printLine('Insufficient data for averages.', null, ' ');
} else {
    $averageTypes = Jm_Array::types();
    Jm_Helper::printLine('Averages (' . count($averageTypes) . ')', null, ' ');
    foreach ($averageTypes as $type) {
        $average = round(Jm_Array::average($results, $type));
        Jm_Helper::printLine('+- ' . ucfirst($type), $average . ' ms');
    }
}

echo PHP_EOL;

/* -------------------------------------------------------------------------- */