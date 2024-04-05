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
 * This is a standard function to modify the configuration parameters of the
 * module
 */
function scheduler_admin_view(array $args = [], $context = null)
{
    if (!xarSecurity::check('AdminScheduler')) {
        return;
    }

    $data = [];

    $data['trigger'] = xarModVars::get('scheduler', 'trigger');
    $data['checktype'] = xarModVars::get('scheduler', 'checktype');
    $data['checkvalue'] = xarModVars::get('scheduler', 'checkvalue');

    $data['ip'] = xarServer::getVar('REMOTE_ADDR');

    $forwarded = xarServer::getVar('HTTP_X_FORWARDED_FOR');
    if (!empty($forwarded)) {
        $data['proxy'] = $data['ip'];
        $data['ip'] = preg_replace('/,.*/', '', $forwarded);
        $data['ip'] = xarVar::prepForDisplay($data['ip']);
    }

    $jobs = xarModVars::get('scheduler', 'jobs');
    if (empty($jobs)) {
        $data['jobs'] = [];
    } else {
        $data['jobs'] = unserialize($jobs);
    }
    $maxid = xarModVars::get('scheduler', 'maxjobid');
    if (!isset($maxid)) {
        // re-number jobs starting from 1 and save maxid
        $maxid = 0;
        $newjobs = [];
        foreach ($data['jobs'] as $job) {
            $maxid++;
            $newjobs[$maxid] = $job;
        }
        xarModVars::set('scheduler', 'maxjobid', $maxid);
        $serialjobs = serialize($newjobs);
        xarModVars::set('scheduler', 'jobs', $serialjobs);
        $data['jobs'] = $newjobs;
    }

    if (!xarVar::fetch('addjob', 'str', $addjob, '', xarVar::NOT_REQUIRED)) {
        return;
    }
    if (!empty($addjob) && preg_match('/^(\w+);(\w+);(\w+)$/', $addjob, $matches)) {
        $maxid++;
        xarModVars::set('scheduler', 'maxjobid', $maxid);
        $data['jobs'][$maxid] = [
                                      'module' => $matches[1],
                                      'type' => $matches[2],
                                      'func' => $matches[3],
                                      'interval' => '',
                                      'config' => [],
                                      'lastrun' => '',
                                      'result' => '',
                                     ];
    }
    $data['jobs'][0] = [
                             'module' => '',
                             'type' => '',
                             'func' => '',
                             'interval' => '0t',
                             'config' => [],
                             'lastrun' => '',
                             'result' => '',
                            ];
    $data['lastrun'] = xarModVars::get('scheduler', 'lastrun');

    $modules = xarMod::apiFunc(
        'modules',
        'admin',
        'getlist',
        ['filter' => ['AdminCapable' => 1]]
    );
    $data['modules'] = [];
    foreach ($modules as $module) {
        $data['modules'][$module['name']] = $module['displayname'];
    }
    $data['types'] = [ // don't translate API types
                           'scheduler' => 'scheduler',
                           'admin' => 'admin',
                           'user' => 'user',
                          ];
    $data['intervals'] = xarMod::apiFunc('scheduler', 'user', 'intervals');

    $hooks = xarModHooks::call(
        'module',
        'modifyconfig',
        'scheduler',
        ['module' => 'scheduler']
    );
    if (empty($hooks)) {
        $data['hooks'] = '';
    } elseif (is_array($hooks)) {
        $data['hooks'] = join('', $hooks);
    } else {
        $data['hooks'] = $hooks;
    }
    // Return the template variables defined in this function
    return $data;
}
