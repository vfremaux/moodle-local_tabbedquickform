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
 * Tells wether a feature is supported or not. Gives back the 
 * implementation path where to fetch resources.
 * @param string $feature a feature key to be tested.
 */
function local_tabbedquickform_supports_feature($feature) {
    global $CFG;
    static $supports;

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

    if (is_dir($CFG->dirroot.'/local/tabbedquickform/pro')) {
        $versionkey = 'pro';
    } else {
        $versionkey = 'community';
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
