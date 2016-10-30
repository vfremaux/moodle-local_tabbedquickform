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
require_once($CFG->dirroot.'/local/tabbedquickform/reset_form.php');

$url = new moodle_url('/local/tabbedquickform/reset.php');

// Security.

$context = context_system::instance();
require_login();
require_capability('moodle/site:config', $context);

// Prepare the page.

$resetstr = get_string('reset', 'local_tabbedquickform');

$PAGE->set_context($context);
$navurl = new moodle_url('/admin/settings.php', array('section' => 'local_tabbedquickform', 'sesskey' => sesskey()));
$PAGE->navbar->add(get_string('pluginname', 'local_tabbedquickform'), $navurl);
$PAGE->navbar->add($resetstr);
$PAGE->set_title(get_string('pluginname', 'local_tabbedquickform'));
$PAGE->set_heading($resetstr);

$PAGE->set_url($url);

$resetform = new Mask_Reset_Form();

if ($resetform->is_cancelled()) {
    redirect(new moodle_url('/admin/settings.php', array('section' => 'local_tabbedquickform', 'sesskey' => sesskey())));
}

if ($data = $resetform->get_data()) {

    if (empty($data->pattern)) {
        $likesql = $DB->sql_like('name', ':name'); // LIKE.
        $DB->delete_records_select('config_plugins', " plugin = 'local_tabbedquickform' AND $likesql ", array('name' => 'mask_%'));
    } else {
        $likesql = $DB->sql_like_escape('name', ':name');

        $sql = "
            DELETE FROM
               {config_plugins} cp
            WHERE
                cp.plugin = 'local_tabbedquickform' AND
                cp.name $likesql
        ";

        $DB->execute($sql, array('name' => $data->pattern));

        // Invalidate config cache.
        cache_helper::invalidate_by_definition('core', 'config', array(), 'local_tabbedquickform');
    }
}

$keycount = $DB->count_records('config_plugins', array('plugin' => 'local_tabbedquickform'));

echo $OUTPUT->header();

echo $OUTPUT->notification(get_string('masksinsite', 'local_tabbedquickform', $keycount));

$resetform->display();

echo $OUTPUT->footer();