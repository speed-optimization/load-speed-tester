<?php

require_once __DIR__ . '/../Bootstrap.php';

/* -------------------------------------------------------------------------- */

$bytesToSizeFilter    = new Jm_Filter_BytesToSize();
$mimeTypeToTypeFilter = new Jm_Filter_MimeTypeToType();

$config = Zend_Registry::get('config');

/* -------------------------------------------------------------------------- */

try {

    $opts = new Zend_Console_Getopt(
        array(
            'url|u=s'      => 'URL of page under test.',
            'strategy|s=s' => 'The strategy to use when analyzing the page. ' .
                              'Default = "' . GETOPT_DEFAULT_STRATEGY . '".',
            'rounds|r=i'   => 'Number of times to download the page in order to calculate load time. ' .
                              'Default = "' . GETOPT_DEFAULT_ROUNDS   . '".',
            'help|h'       => 'Show help message.',
            'version|v'    => 'Show version.',
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

    $strategy = (string) $opts->getOption('strategy');

    if (empty($strategy)) {
        $strategy = GETOPT_DEFAULT_STRATEGY;
    }

    $rounds = (integer) $opts->getOption('rounds');

    if (empty($rounds)) {
        $rounds = GETOPT_DEFAULT_ROUNDS;
    }

} catch (Zend_Console_Getopt_Exception $e) {
    echo PHP_EOL . 'Load Speed Analysis';
    echo PHP_EOL . '-------------------';
    if ($e->getMessage()) {
        echo PHP_EOL . PHP_EOL . $e->getMessage();
    }
    echo PHP_EOL . PHP_EOL . $e->getUsageMessage();
    echo PHP_EOL;
    exit();
}

/* -------------------------------------------------------------------------- */

Jm_System::clearScreen();

echo 'Performing Net Sniff Test';

echo '.';

$result = Jm_System::execPhantomJs('netsniff.js', $url);

echo '.';

$result = Zend_Json::decode($result);

$mimeTypes = array();

foreach ($result['log']['entries'] as $entry) {

    // @todo: there must be a better way!
    $mimeTypesParts = explode(';', $entry['response']['content']['mimeType']);
    $mimeType = $mimeTypeToTypeFilter->filter($mimeTypesParts[0]);

    if (!isset($mimeTypes[$mimeType])) {
        $mimeTypes[$mimeType] = 0;
    }
    $mimeTypes[$mimeType]++;
}

ksort($mimeTypes);

echo 'Done!' . PHP_EOL;

/* -------------------------------------------------------------------------- */

echo 'Performing Load Speed Test';

$resultsLoadSpeed = array();

for ($i = 1; $i <= $rounds; $i++) {
    echo '.';
    $resultsLoadSpeed[$i] = Jm_System::execPhantomJs('loadspeed.js', $url);
}

echo 'Done!' . PHP_EOL;

/* -------------------------------------------------------------------------- */

echo 'Performing Google PageSpeed Test';

$client = new Zend_Http_Client();

$client->setUri($config->googleApiUri);
$client->setParameterGet('url'        , $url);
$client->setParameterGet('prettyprint', false);
$client->setParameterGet('locale'     , 'en_US');
$client->setParameterGet('strategy'   , $strategy);
$client->setParameterGet('key'        , $config->googleApiKey);

echo '.';

$response = $client->request(Zend_Http_Client::GET);

echo '.';

echo 'Done!' . PHP_EOL;

Jm_System::clearScreen();

/* ---------------------------------------------------------------------- */

echo PHP_EOL;

Jm_Helper::printLine('Load Speed Results for:', null, ' ');
Jm_Helper::printLine('  ' . $url              , null, ' ');

echo PHP_EOL;

/* ---------------------------------------------------------------------- */

Jm_Helper::printLine('Summary'  , null, ' ');

Jm_Helper::printLine('+- Load Speed',
        Jm_Array::average($resultsLoadSpeed) . ' ms');

if ($response->isSuccessful()) {

    $results = Zend_Json::decode($response->getBody());

    Jm_Helper::printLine('+- Score',
            $results['score'] . ' pct');
}

echo PHP_EOL;

/* -------------------------------------------------------------------------- */

Jm_Helper::printLine('Response (count)', array_sum($mimeTypes));

foreach ($mimeTypes as $mimeType => $count) {
    Jm_Helper::printLine('+- ' . $mimeType, $count);
}

echo PHP_EOL;

/* -------------------------------------------------------------------------- */

if ($response->isSuccessful()) {

    Jm_Helper::printLine('Response (size)',
        $bytesToSizeFilter->filter(
                $results['pageStats']['cssResponseBytes']   +
                $results['pageStats']['htmlResponseBytes']  +
                $results['pageStats']['imageResponseBytes'] +
                $results['pageStats']['javascriptResponseBytes'] ) );

    Jm_Helper::printLine('+- CSS',
            $bytesToSizeFilter->filter($results['pageStats']['cssResponseBytes']));

    Jm_Helper::printLine('+- HTML',
            $bytesToSizeFilter->filter($results['pageStats']['htmlResponseBytes']));

    Jm_Helper::printLine('+- Image',
            $bytesToSizeFilter->filter($results['pageStats']['imageResponseBytes']));

    Jm_Helper::printLine('+- JS',
            $bytesToSizeFilter->filter($results['pageStats']['javascriptResponseBytes']));

    echo PHP_EOL;

    /* ---------------------------------------------------------------------- */

    $rules = array();

    if (isset($results['formattedResults']['ruleResults']) &&
            is_array($results['formattedResults']['ruleResults'])) {

        foreach ($results['formattedResults']['ruleResults'] as $rule) {
            if (100 != $rule['ruleScore']) {
                $rules[] = $rule;
            }
        }
    }

    array_multisort(
            array_map(function($rule) {
                        return $rule['ruleImpact'];
                    }, $rules), SORT_DESC, $rules);

    Jm_Helper::printLine('Suggested Improvements (' . count($rules) . ')', null, ' ');

    foreach ($rules as $rule) {
        Jm_Helper::printLine('+- ' . $rule['localizedRuleName'], round($rule['ruleImpact']));
    }

    echo PHP_EOL;

    /* -------------------------------------------------------------------------- */

}