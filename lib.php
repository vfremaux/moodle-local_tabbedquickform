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
defined('MOODLE_INTERNAL') || die();

/**
 * This is not the real use of this core hook, but ensures JQuery will be available everywhere
 * in moodle.
 */
function local_tabbedquickform_extend_navigation() {
    global $PAGE;
}

function local_tabbedquickform_hook() {
    global $PAGE, $CFG;

    if (!during_initial_install()) {
        include_once($CFG->dirroot.'/local/tabbedquickform/QuickForm_Extensions/MoodleForm_Tabbed_Renderer.php');
        $config = get_config('local_tabbedquickform');

        if (empty($config->enable)) {
            // Wuick trap if not enabled.
            return;
        }

        if (defined('AJAX_SCRIPT') && AJAX_SCRIPT) {
            // We cannot tune forms that are invoked through AJAX XHR calls.
            return;
        }

        $exclusions = [
            'page-mod-tracker-reportissue',
            'page-local-vmoodle-view',
            'page-admin-tool-mnetusers*',
            'page-admin-user-user_bulk',
            'page-mod-data-export',
            'page-admin-purgecaches',
            'page-admin-user',
            'page-admin-search',
            'page-admin-tool-usertour',
            'page-local-userenrols-import'
        ];

        // Process eventual exclusions administrators have identified.
        $additionalexclusions = explode("\n", trim(@$config->excludepagetypes));
        if (!empty($additionalexclusions)) {
            foreach ($additionalexclusions as $exc) {
                if (!in_array(trim($exc), $exclusions)) {
                    $exclusions[] = trim($exc);
                }
            }
        }

        $excluded = false;
        foreach ($exclusions as $exc) {
            if (!empty($exc)) {
                $exc = str_replace('*', '.*', trim($exc));
                $exc = str_replace('?', '.', $exc);
                $excluded = $excluded || preg_match("/$exc/", $PAGE->bodyid);
            }
        }

        if (!$excluded) {
            $GLOBALS['_HTML_QuickForm_default_renderer'] = new MoodleQuickForm_Tabbed_Renderer();
        }
    }
}