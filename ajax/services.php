<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     local_tabbedquickform
 * @category    local
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require('../../../config.php');

require_login();
if (!is_siteadmin()) {
    exit;
}

$action = optional_param('what', '', PARAM_TEXT);

if ($action == 'maskformitem') {
    $elementid = required_param('fitemid', PARAM_TEXT);
    $bodyid = required_param('bodyid', PARAM_TEXT);
    $value = optional_param('value', '%UNSET%', PARAM_TEXT);
    $bodyid = str_replace('-', '_', $bodyid);
    $maskkey = 'mask_'.$bodyid.'_'.$elementid;
    set_config($maskkey, $value, 'local_tabbedquickform');
    echo 'masked';
}
if ($action == 'unmaskformitem') {
    // If unmasked, remove entirely the mask definition from config.
    $elementid = required_param('fitemid', PARAM_TEXT);
    $bodyid = required_param('bodyid', PARAM_TEXT);
    $bodyid = str_replace('-', '_', $bodyid);
    $maskkey = 'mask_'.$bodyid.'_'.$elementid;
    set_config($maskkey, null, 'local_tabbedquickform');
    echo 'unmasked';
}