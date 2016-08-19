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

defined('MOODLE_INTERNAL') || die();

/**
 * @package   local_tabbedquickform
 * @category  blocks
 * @author    Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// settings default init
if (is_dir($CFG->dirroot.'/local/adminsettings')) {
    // Integration driven code 
    require_once($CFG->dirroot.'/local/adminsettings/lib.php');
    list($hasconfig, $hassiteconfig, $capability) = local_adminsettings_access();
} else {
    // Standard Moodle code
    $capability = 'moodle/site:config';
    $hasconfig = $hassiteconfig = has_capability($capability, context_system::instance());
}

if ($hassiteconfig) { 
    // Needs this condition or there is error on login page.
    $settings = new admin_settingpage('local_tabbedquickform', get_string('pluginname', 'local_tabbedquickform'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configcheckbox('local_tabbedquickform/enable', get_string('localtabbedquickformenable', 'local_tabbedquickform'), get_string('localtabbedquickformenabledesc', 'local_tabbedquickform'), 0));

    $options = array(0 => get_string('simple', 'local_tabbedquickform'), 1 => get_string('complete', 'local_tabbedquickform'));
    $settings->add(new admin_setting_configselect('local_tabbedquickform/defaultmode', get_string('localtabbedquickformdefaultmode', 'local_tabbedquickform'), get_string('localtabbedquickformdefaultmodedesc', 'local_tabbedquickform'), 0, $options));
}
