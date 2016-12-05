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
 * Form for editing HTML block instances.
 *
 * @package     local_tabbedquickform
 * @category    local
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   Valery Fremaux <valery.fremaux@gmail.com> (MyLearningFactory.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!defined('MOODLE_EARLY_INTERNAL')) {
    defined('MOODLE_INTERNAL') || die();
}

set_include_path(get_include_path().PATH_SEPARATOR.$CFG->dirroot.'/lib/pear');
require_once('HTML/QuickForm.php');
require_once('HTML/QuickForm/DHTMLRulesTableless.php');
require_once('HTML/QuickForm/Renderer/Tableless.php');
require_once($CFG->dirroot.'/local/tabbedquickform/QuickForm_Extensions/MoodleForm_Tabbed_Renderer.php');
$config = get_config('local_tabbedquickform');
$excluded = false;
global $PAGE;
if ($exclusions = explode("\n", @$config->excludepagetypes)) {
    $excluded = in_array($PAGE->bodyid, $exclusions);
}
if (!empty($config->enable) && !$excluded) {
    include($CFG->dirroot.'/local/tabbedquickform/QuickForm_Extensions/invoke.php');
}
