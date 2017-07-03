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
 * @category  local
 * @author    Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/*
 * Standard upgrade handler.
 * @param int $oldversion
 */
function xmldb_local_tabbedquickform_upgrade($oldversion = 0) {
    global $DB;

    $result = true;

    $dbman = $DB->get_manager();

    if ($oldversion < 2016120500) {

        /*
         * convert old boolean markers to unset, protecting eventual previously
         * explicit default values.
         */
        $sql = "
            UPDATE
                {config_plugins}
            SET
                value = '%UNSET%'
            WHERE
                plugin = 'local_tabbedquickform' AND
                name LIKE 'mask%' AND
                value = 1
        ";

        $DB->execute($sql);

        upgrade_plugin_savepoint(true, 2016120500, 'local', 'tabbedquickform');
    }

    if ($oldversion < 2017070301) {

        // Deletes eventual moodle_page class diverting settings.
        $sql = '
            DELETE FROM
                {config}
            WHERE
                value LIKE "%jquery_forced_moodle_page%"
        ';
        $DB->execute($sql);

        upgrade_plugin_savepoint(true, 2017070301, 'local', 'tabbedquickform');
    }

    return $result;
}