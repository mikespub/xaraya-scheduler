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
$modversion['name']           = 'Scheduler';
$modversion['id']             = '189';
$modversion['version']        = '2.4.1';
$modversion['displayname']    = xarML('Scheduler');
$modversion['description']    = 'Schedule Xaraya jobs at certain times of the day/week/month (cron)';
$modversion['credits']        = '';
$modversion['help']           = '';
$modversion['changelog']      = '';
$modversion['license']        = '';
$modversion['official']       = true;
$modversion['author']         = 'mikespub';
$modversion['contact']        = 'http://www.xaraya.com/';
$modversion['admin']          = true;
$modversion['user']           = false;
$modversion['class']          = 'Utility';
$modversion['category']       = 'Miscellaneous';
$modversion['namespace']      = 'Xaraya\Modules\Scheduler';
$modversion['twigtemplates']  = true;
$modversion['dependencyinfo'] = [
    0 => [
        'name' => 'Xaraya Core',
        'version_ge' => '2.4.1',
    ],
];
