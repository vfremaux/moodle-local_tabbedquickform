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
 * @package   local_tabbedquickform
 * @category  blocks
 * @author    Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/local/tabbedquickform/lib.php');

if ($hassiteconfig) {
    // Needs this condition or there is error on login page.
    $settings = new admin_settingpage('localsettingtabbedquickform', get_string('pluginname', 'local_tabbedquickform'));
    $ADMIN->add('localplugins', $settings);

    $key = 'local_tabbedquickform/enable';
    $label = get_string('localtabbedquickformenable', 'local_tabbedquickform');
    $desc = get_string('localtabbedquickformenable_desc', 'local_tabbedquickform');
    $settings->add(new admin_setting_configcheckbox($key, $label, $desc, 0));

    $key = 'local_tabbedquickform/allowmaskingmandatories';
    $label = get_string('allowmaskingmandatories', 'local_tabbedquickform');
    $desc = get_string('allowmaskingmandatories_desc', 'local_tabbedquickform');
    $settings->add(new admin_setting_configcheckbox($key, $label, $desc, 0));

    $options = array(0 => get_string('simple', 'local_tabbedquickform'), 1 => get_string('complete', 'local_tabbedquickform'));
    $key = 'local_tabbedquickform/defaultmode';
    $label = get_string('localtabbedquickformdefaultmode', 'local_tabbedquickform');
    $desc = get_string('localtabbedquickformdefaultmode_desc', 'local_tabbedquickform');
    $settings->add(new admin_setting_configselect($key, $label, $desc, 0, $options));

    $key = 'local_tabbedquickform/excludepagetypes';
    $label = get_string('excludepagetypes', 'local_tabbedquickform');
    $desc = get_string('excludepagetypes_desc', 'local_tabbedquickform');
    $defaults = '';
    $settings->add(new admin_setting_configtextarea($key, $label, $desc, $defaults));

    $a = new StdClass;
    $a->reseturl = $CFG->wwwroot.'/local/tabbedquickform/reset.php';
    $label = get_string('exportprofiles', 'local_tabbedquickform');
    $desc = get_string('exportprofiles_desc', 'local_tabbedquickform', $a);
    $settings->add(new admin_setting_heading('h1', $label, $desc));

    if (local_courseindex_supports_feature('emulate/community') == 'pro') {
        include_once($CFG->dirroot.'/local/tabbedquickform/pro/prolib.php');
        $promanager = local_tabbedquickform\pro_manager::instance();
        $promanager->add_settings($ADMIN, $settings);
    } else {
        $label = get_string('plugindist', 'local_tabbedquickform');
        $desc = get_string('plugindist_desc', 'local_tabbedquickform');
        $settings->add(new admin_setting_heading('plugindisthdr', $label, $desc));
    }
}

