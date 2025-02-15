<?php

/**
 * Scheduler Module
 *
 * @package modules
 * @subpackage scheduler module
 * @category Third Party Xaraya Module
 * @version 2.0.0
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://xaraya.com/index.php/release/189.html
 * @author mikespub
 */
/**
 * Instead of triggering the scheduler by retrieving the web page
 * index.php?module=scheduler or by using a trigger block on your
 * site, you can also execute this script directly using the PHP
 * command line interface (CLI) : php run_scheduler.php
 */
if (php_sapi_name() !== 'cli') {
    echo 'Scheduler Module Run Script for running scheduler jobs';
    return;
}
chdir(dirname(__DIR__, 3) . '/html');

/**
 * Load the layout file so we know where to find the Xaraya directories
 */
$systemConfiguration = [];
include 'var/layout.system.php';
if (!isset($systemConfiguration['rootDir'])) {
    $systemConfiguration['rootDir'] = '../';
}
if (!isset($systemConfiguration['libDir'])) {
    $systemConfiguration['libDir'] = 'lib/';
}
if (!isset($systemConfiguration['webDir'])) {
    $systemConfiguration['webDir'] = 'html/';
}
if (!isset($systemConfiguration['codeDir'])) {
    $systemConfiguration['codeDir'] = 'code/';
}
$GLOBALS['systemConfiguration'] = $systemConfiguration;
set_include_path($systemConfiguration['rootDir'] . PATH_SEPARATOR . get_include_path());

/**
 * Load the Xaraya bootstrap so we can get started
 */
include 'bootstrap.php';

/**
 * Set up output caching if enabled
 * Note: this happens first so we can serve cached pages to first-time visitors
 *       without loading the core
 */
sys::import('xaraya.caching');
// Note : we may already exit here if session-less page caching is enabled
xarCache::init();

/**
 * Load the Xaraya core
 */
sys::import('xaraya.core');

// Load the core with all optional systems loaded
xarCore::xarInit(xarCore::SYSTEM_ALL);

$homedir = xarServer::getBaseURL();

// update the last run time
xarModVars::set('scheduler', 'lastrun', time());
xarModVars::set('scheduler', 'running', 1);

// call the API function to run the jobs
$result = xarMod::apiFunc('scheduler', 'user', 'runjobs');
if (empty($result)) {
    $result = "Done";
} elseif (is_array($result)) {
    $result = implode("\n", $result);
}
echo $result;
echo "\n";
