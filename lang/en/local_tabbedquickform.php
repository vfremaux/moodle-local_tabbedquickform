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
$string['tabbedquickform:configure'] = 'Can configure forms';

$string['allowmaskingmandatories'] = 'Allow masking mandatory fields';
$string['complete'] = 'Complete';
$string['deleterange'] = 'Selector pattern (LIKE)';
$string['enterconfigure'] = 'Enter form tuning';
$string['excludepagetypes'] = 'Exclude page types';
$string['exitconfigure'] = 'Exit form tuning';
$string['export'] = 'Masks export';
$string['exportprofiles'] = 'Reset profiles';
$string['exportprofilespro'] = 'Exchange profiles';
$string['filterfeatures'] = 'Enter simple mode';
$string['fullfeatured'] = 'Enter full featured';
$string['hasmaskeditems'] = 'This form has masked items';
$string['import'] = 'Masks import';
$string['localtabbedquickformdefaultmode'] = 'Default mode';
$string['localtabbedquickformenable'] = 'Enable';
$string['maskkeys'] = 'Mask keys (one per line)';
$string['masksinsite'] = 'This site has {$a} masks defined.';
$string['plugindist'] = 'Plugin distribution';
$string['pluginname'] = 'Tabbed QuickForms';
$string['reset'] = 'Masks reset';
$string['resetprofiles'] = 'Reset tuning profile';
$string['sectionhaserrors'] = 'There are some errors on this tab';
$string['simple'] = 'Simple';

$string['allowmaskingmandatories_desc'] = '
If enabled, the administrators can mask including mandatory fields. A default value should be available for those fields.
';

$string['excludepagetypes_desc'] = '
Enter a list of page types that will play the traditional vertical layout of the form.
';

$string['localtabbedquickformdefaultmode_desc'] = '
Sets the default forms state for a standard user profile.
';

$string['localtabbedquickformenable_desc'] = '
Enables the tabbed quickform renderer and the feature filtering facility. You need add the associated patch
in /lib/formslib.php to make this feature effective.
';

$string['exportprofiles_desc'] = '
You may want to globally <a href="{$a->reseturl}">reset</a> all form masks that you have setup.
';

$string['exportprofilespro_desc'] = '
You may <a href="{$a->exporturl}">export</a> and <a href="{$a->importurl}">import</a> mask key definitions
as simple list of mask keys. You may also want to globally <a href="{$a->reseturl}">reset</a> all form masks that you have setup.
';

$string['plugindist_desc'] = '
<p>This plugin is the community version and is published for anyone to use as is and check the plugin\'s
core application. A "pro" version of this plugin exists and is distributed under conditions to feed the life cycle, upgrade, documentation
and improvement effort.</p>
<p>Please contact one of our distributors to get "Pro" version support.</p>
<ul><li><a href="http://service.activeprolearn.com/local/shop/front/view.php?id=1">ActiveProLearn SAS</a></li>
<li><a href="http://www.edunao.com">Edunao SAS</a></li></ul>
';

