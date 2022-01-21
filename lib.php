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

require_once($CFG->dirroot.'/local/tabbedquickform/extlibs/extralib.php');

/**
 * This is not the real use of this core hook, but ensures JQuery will be available everywhere
 * in moodle.
 */
function local_tabbedquickform_extend_navigation() {
    global $PAGE;
}

/**
 * This is part of the dual release distribution system.
 * Tells wether a feature is supported or not. Gives back the
 * implementation path where to fetch resources.
 * @param string $feature a feature key (feature/subfeature) to be tested.
 * @param bool $getsupported if true, returns the full supported descriptor. This will serve to plugin autodoc.
 */
function local_tabbedquickform_supports_feature($feature = null, $getsupported = false) {
    global $CFG;
    static $supports;

    if (!during_initial_install()) {
        $config = get_config('local_tabbedquickform');
    }

    if (!isset($supports)) {
        $supports = array(
            'pro' => array(
                'tools' => array('export', 'import', 'reset'),
            ),
            'community' => array(
                'tools' => array('reset'),
            ),
        );
    }

    if ($getsupported) {
        return $supports;
    }

    // Check existance of the 'pro' dir in plugin.
    if (is_dir(__DIR__.'/pro')) {
        if ($feature == 'emulate/community') {
            return 'pro';
        }
        if (empty($config->emulatecommunity)) {
            $versionkey = 'pro';
        } else {
            $versionkey = 'community';
        }
    } else {
        $versionkey = 'community';
    }

    if (empty($feature)) {
        // Just return version.
        return $versionkey;
    }

    list($feat, $subfeat) = explode('/', $feature);

    if (!array_key_exists($feat, $supports[$versionkey])) {
        return false;
    }

    if (!in_array($subfeat, $supports[$versionkey][$feat])) {
        return false;
    }

    return $versionkey;
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
            'page-local-userenrols-import',
            'page-cache-admin',
            'page-lib-editor-atto-plugins-managefiles-manage',
            'page-lib-editor-tinymce-plugins-managefiles-manage'
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
                $excluded = $excluded || preg_match("/{$exc}/", $PAGE->bodyid);
            }
        }

        if (!$excluded) {
            set_globals('_HTML_QuickForm_default_renderer', new MoodleQuickForm_Tabbed_Renderer());
        }
    }
}