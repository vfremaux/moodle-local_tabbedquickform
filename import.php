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
 * @package local_tabbedquickform
 * @category local
 *
 * This file provides access to a master shared resources index, intending
 * to allow a public browsing of resources.
 * The catalog is considered as multi-provider, and can federate all resources into
 * browsing results, or provide them as separate catalogs for each resource provider.
 *
 * The index admits browsing remote linked catalogues, and will aggregate the found
 * entries in the current view, after a contextual query has been fired to remote connected
 * resource sets.
 *
 * The index will provide a "top viewed" resources side tray, and a "top used" side tray, 
 * that will count local AND remote inttegration of the resource. The remote query to 
 * bound catalogs will also get information about local catalog resource used by remote courses. 
 *
 * The index is public access. Browsing the catalog should although be done through a Guest identity,
 * having as a default the repository/sharedresources:view capability.
 */
require('../../config.php');
require_once($CFG->dirroot.'/local/tabbedquickform/import_form.php');

$url = new moodle_url('/local/tabbedquickform/import.php');

// Security.

$context = context_system::instance();
require_login();
require_capability('moodle/site:config', $context);

// Prepare the page.

$importstr = get_string('import', 'local_tabbedquickform');

$PAGE->set_context($context);
$PAGE->navbar->add(get_string('pluginname', 'local_tabbedquickform'), new moodle_url('/admin/settings.php', array('section' => 'local_tabbedquickform', 'sesskey' => sesskey())));
$PAGE->navbar->add($importstr);
$PAGE->set_title(get_string('pluginname', 'local_tabbedquickform'));
$PAGE->set_heading($importstr);

$PAGE->set_url($url);

$importform = new Mask_Import_Form();

if ($importform->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php', array('section' => 'local_tabbedquickform', 'sesskey' => sesskey())));
}

if ($data = $importform->get_data()) {

    if (!empty($data->masks)) {
        echo '<pre>';
        $masks = explode("\n", $data->masks);
        echo 'Importing '.count($masks)."...\n";
        foreach ($masks as $m) {
            $m = trim($m);
            set_config($m, 1, 'local_tabbedquickform');
        }
        echo "Done.\n";
        echo '</pre>';
    }
}

$keycount = $DB->count_records('config_plugins', array('plugin' => 'local_tabbedquickform'));

echo $OUTPUT->header();

echo $OUTPUT->notification(get_string('masksinsite', 'local_tabbedquickform', $keycount));

$importform->display();

echo $OUTPUT->footer();