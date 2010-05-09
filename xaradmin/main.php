<?php
/**
 * Scheduler module
 *
 * @package modules
 * @copyright (C) 2002-2006 The Digital Development Foundation
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://www.xaraya.com
 *
 * @subpackage Scheduler Module
 * @link http://xaraya.com/index.php/release/189.html
 * @author mikespub
 */
/**
 * the main administration function
 */
function scheduler_admin_main()
{
    if (!xarSecurityCheck('AdminScheduler')) return;

        xarResponse::redirect(xarModURL('scheduler', 'admin', 'modifyconfig'));

    return true;
}

?>
