This component can have a global effect on all Moodle forms based on Quickform (the
standard form strategy). It will render forms as tabbed panels rather than linear
vertical sections. 

Additionnaly, the "feature filter" facility will let the administrator hide any
field item in any form soit will not appear for the user. This way any feature of Moodle
may be masked and pragmatically disabled. The administrator will anyway have to be
very carefull when choosing what to hide.

====== Install the patch ========

At the end of the formslib.php script

/**
 * @global object $GLOBALS['_HTML_QuickForm_default_renderer']
 * @name $_HTML_QuickForm_default_renderer
 */
$GLOBALS['_HTML_QuickForm_default_renderer'] = new MoodleQuickForm_Renderer();
// PATCH : Overloads quickform renderer
$config = get_config('local_tabbedquickform');
if (!empty($config->enable) && is_dir($CFG->dirroot.'/local/tabbedquickform')) {
    $GLOBALS['_HTML_QuickForm_default_renderer'] = new MoodleQuickForm_Tabbed_Renderer();
}
// /PATCH
