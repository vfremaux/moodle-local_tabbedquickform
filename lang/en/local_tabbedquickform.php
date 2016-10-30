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

$string['tabbedquickform:canswitchfeatured'] = 'Can switch full and filtered features';

$string['pluginname'] = 'Tabbed QuickForms';
$string['enterconfigure'] = 'Enter form tuning';
$string['exitconfigure'] = 'Exit form tuning';
$string['filterfeatures'] = 'Enter simple mode';
$string['fullfeatured'] = 'Enter full featured';
$string['simple'] = 'Simple';
$string['complete'] = 'Complete';
$string['hasmaskeditems'] = 'This form has masked items';
$string['localtabbedquickformenable'] = 'Enable';
$string['localtabbedquickformdefaultmode'] = 'Default mode';
$string['localtabbedquickformenable_desc'] = 'Enables the tabbed quickform renderer and the feature filtering facility. You need add the associated patch in /lib/formslib.php to make this feature effective.';
$string['localtabbedquickformdefaultmode_desc'] = 'Sets the default forms state for a standard user profile';
$string['exportprofiles'] = 'Exchange profiles';
$string['exportprofiles_desc'] = 'You may <a href="{$a->exporturl}">export</a> and <a href="{$a->importurl}">import</a> mask key definitions as simple list of mask keys. You may also want to globally <a href="{$a->reseturl}">reset</a> all form masks that you have setup.';
$string['maskkeys'] = 'Mask keys (one per line)';
$string['masksinsite'] = 'This site has {$a} masks defined.';
$string['reset'] = 'Masks reset';
$string['import'] = 'Masks import';
$string['export'] = 'Masks export';
$string['deleterange'] = 'Selector pattern (LIKE)';
